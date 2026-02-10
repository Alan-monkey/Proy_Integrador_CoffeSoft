<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use MongoDB\Client;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    private $backupPath;
    
    public function __construct()
    {
        $this->backupPath = storage_path('app/backups/');
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }
    
    public function index()
    {
        // Obtener backups existentes
        $backups = [];
        if (file_exists($this->backupPath)) {
            $files = scandir($this->backupPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $this->backupPath . $file;
                    
                    // Leer metadatos del backup si existe
                    $metadata = $this->getBackupMetadata($filePath);
                    
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($filePath),
                        'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'path' => $filePath,
                        'created_by' => $metadata['created_by'] ?? 'Desconocido',
                        'created_by_id' => $metadata['created_by_id'] ?? null,
                        'database' => $metadata['database'] ?? 'Desconocido',
                        'collections_count' => $metadata['total_collections'] ?? 0,
                        'format' => $metadata['format'] ?? 'Desconocido'
                    ];
                }
            }
        }
        
        // Ordenar por fecha (más reciente primero)
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return view('admin.backups.index', compact('backups'));
    }
    
    public function createBackupForm()
    {
        // Obtener todas las colecciones de MongoDB
        $collections = $this->getMongoCollections();
        
        return view('admin.backups.create', compact('collections'));
    }
    
    public function createBackup(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'collections' => 'required|array',
            'collections.*' => 'string',
            'format' => 'required|in:json,csv,zip',
            'include_structure' => 'boolean'
        ]);
        
        $backupName = $request->name ?: 'backup_' . date('Y-m-d_H-i-s');
        $selectedCollections = $request->collections;
        $format = $request->format;
        $includeStructure = $request->boolean('include_structure');
        
        // Obtener información del usuario actual
        $user = Auth::guard('usuarios')->user();
        $userId = $user ? $user->_id : null;
        $userName = $user ? $user->nombre : 'Sistema';
        $userEmail = $user ? $user->email : 'sistema@localhost';
        
        Log::info("Iniciando backup por usuario: {$userName} (ID: {$userId})");
        
        // Crear directorio temporal
        $tempDir = $this->backupPath . 'temp_' . time() . '/';
        mkdir($tempDir, 0755, true);
        
        try {
            $client = new Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
            $database = env('MONGODB_DATABASE', 'CoffeSoft');
            $db = $client->selectDatabase($database);
            
            $exportedFiles = [];
            $exportedCollections = [];
            
            foreach ($selectedCollections as $collectionName) {
                $collection = $db->selectCollection($collectionName);
                $documents = $collection->find()->toArray();
                
                // Registrar colección exportada
                $exportedCollections[] = [
                    'name' => $collectionName,
                    'documents_count' => count($documents)
                ];
                
                // Exportar datos
                if ($format === 'json') {
                    $filename = $tempDir . $collectionName . '.json';
                    file_put_contents($filename, json_encode($documents, JSON_PRETTY_PRINT));
                    $exportedFiles[] = $filename;
                } elseif ($format === 'csv') {
                    $filename = $this->exportToCSV($collectionName, $documents, $tempDir);
                    $exportedFiles[] = $filename;
                }
                
                // Exportar estructura si se solicita
                if ($includeStructure) {
                    $structureFile = $tempDir . $collectionName . '_structure.json';
                    $indexes = $collection->listIndexes()->toArray();
                    file_put_contents($structureFile, json_encode($indexes, JSON_PRETTY_PRINT));
                    $exportedFiles[] = $structureFile;
                }
            }
            
            // Crear archivo de metadatos con información del usuario
            $metadata = [
                'backup_name' => $backupName,
                'created_at' => Carbon::now()->toDateTimeString(),
                'created_by' => $userName,
                'created_by_id' => $userId,
                'created_by_email' => $userEmail,
                'collections' => $selectedCollections,
                'collections_details' => $exportedCollections,
                'format' => $format,
                'include_structure' => $includeStructure,
                'database' => $database,
                'total_collections' => count($selectedCollections),
                'total_documents' => array_sum(array_column($exportedCollections, 'documents_count')),
                'system_info' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido'
                ]
            ];
            
            file_put_contents($tempDir . 'metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
            $exportedFiles[] = $tempDir . 'metadata.json';
            
            // Crear archivo de resumen en texto plano
            $summaryContent = $this->createSummaryContent($metadata);
            file_put_contents($tempDir . 'RESUMEN.txt', $summaryContent);
            $exportedFiles[] = $tempDir . 'RESUMEN.txt';
            
            // Comprimir si es necesario o si hay múltiples archivos
            $finalFilename = $backupName . '_' . date('Y-m-d_H-i-s');
            
            if ($format === 'zip' || count($exportedFiles) > 1) {
                $finalFilename .= '.zip';
                $zipPath = $this->backupPath . $finalFilename;
                $this->createZip($exportedFiles, $zipPath, $tempDir);
                
                // Limpiar archivos temporales
                $this->deleteDirectory($tempDir);
            } else {
                // Mover el único archivo
                $finalFilename .= '.' . $format;
                rename($exportedFiles[0], $this->backupPath . $finalFilename);
                rmdir($tempDir);
            }
            
            Log::info("Backup creado por {$userName}: {$finalFilename}");
            
            return redirect()->route('backups.index')
                ->with('success', 'Backup creado exitosamente: ' . $finalFilename)
                ->with('info', "Creado por: {$userName}");
                
        } catch (\Exception $e) {
            // Limpiar en caso de error
            if (file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            
            Log::error("Error al crear backup por {$userName}: " . $e->getMessage());
            
            return back()->withErrors(['error' => 'Error al crear backup: ' . $e->getMessage()]);
        }
    }
    
    public function downloadBackup($filename)
    {
        $filePath = $this->backupPath . $filename;
        
        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'El archivo de backup no existe.']);
        }
        
        // Registrar quién descargó el backup
        $user = Auth::guard('usuarios')->user();
        if ($user) {
            Log::info("Backup '{$filename}' descargado por: {$user->nombre} ({$user->email})");
        }
        
        return response()->download($filePath);
    }
    
    public function deleteBackup($filename)
    {
        $filePath = $this->backupPath . $filename;
        
        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'El archivo de backup no existe.']);
        }
        
        // Obtener información del backup antes de eliminarlo
        $metadata = $this->getBackupMetadata($filePath);
        $backupName = $metadata['backup_name'] ?? $filename;
        
        // Registrar quién eliminó el backup
        $user = Auth::guard('usuarios')->user();
        if ($user) {
            Log::warning("Backup '{$backupName}' eliminado por: {$user->nombre} ({$user->email})");
        }
        
        unlink($filePath);
        
        return redirect()->route('backups.index')
            ->with('success', 'Backup eliminado exitosamente.')
            ->with('info', "Backup '{$backupName}' eliminado por {$user->nombre}");
    }
    
    public function restoreBackupForm($filename = null)
    {
        $backups = [];
        if (file_exists($this->backupPath)) {
            $files = scandir($this->backupPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $backups[] = $file;
                }
            }
        }
        
        return view('admin.backups.restore', compact('backups', 'filename'));
    }
    
    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string',
            'collections_to_restore' => 'required|array',
            'restore_mode' => 'required|in:replace,merge'
        ]);
        
        $filename = $request->backup_file;
        $filePath = $this->backupPath . $filename;
        
        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'El archivo de backup no existe.']);
        }
        
        // Obtener información del usuario que está restaurando
        $user = Auth::guard('usuarios')->user();
        $userName = $user ? $user->nombre : 'Sistema';
        
        try {
            // Extraer si es ZIP
            $tempDir = $this->backupPath . 'restore_temp_' . time() . '/';
            mkdir($tempDir, 0755, true);
            
            if (pathinfo($filename, PATHINFO_EXTENSION) === 'zip') {
                $zip = new ZipArchive;
                if ($zip->open($filePath) === TRUE) {
                    $zip->extractTo($tempDir);
                    $zip->close();
                } else {
                    throw new \Exception('No se pudo abrir el archivo ZIP.');
                }
            } else {
                copy($filePath, $tempDir . $filename);
            }
            
            // Leer metadatos
            $metadataPath = $tempDir . 'metadata.json';
            if (!file_exists($metadataPath)) {
                throw new \Exception('Archivo de metadatos no encontrado en el backup.');
            }
            
            $metadata = json_decode(file_get_contents($metadataPath), true);
            $originalCreator = $metadata['created_by'] ?? 'Desconocido';
            $backupName = $metadata['backup_name'] ?? $filename;
            
            Log::info("Iniciando restauración de backup '{$backupName}' (creado por {$originalCreator}) por {$userName}");
            
            // Conectar a MongoDB
            $client = new Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
            $database = env('MONGODB_DATABASE', 'CoffeSoft');
            $db = $client->selectDatabase($database);
            
            $restoredCollections = [];
            
            foreach ($request->collections_to_restore as $collectionName) {
                $collection = $db->selectCollection($collectionName);
                
                // Buscar archivo de datos
                $dataFile = null;
                foreach (['json', 'csv'] as $ext) {
                    $possibleFile = $tempDir . $collectionName . '.' . $ext;
                    if (file_exists($possibleFile)) {
                        $dataFile = $possibleFile;
                        break;
                    }
                }
                
                if (!$dataFile) {
                    continue;
                }
                
                if ($request->restore_mode === 'replace') {
                    // Eliminar todos los documentos existentes
                    $deletedCount = $collection->deleteMany([])->getDeletedCount();
                    Log::info("Colección {$collectionName}: eliminados {$deletedCount} documentos existentes");
                }
                
                // Restaurar datos
                $extension = pathinfo($dataFile, PATHINFO_EXTENSION);
                $restoredCount = 0;
                
                if ($extension === 'json') {
                    $data = json_decode(file_get_contents($dataFile), true);
                    if (!empty($data)) {
                        $result = $collection->insertMany($data);
                        $restoredCount = $result->getInsertedCount();
                    }
                } elseif ($extension === 'csv') {
                    $restoredCount = $this->importFromCSV($collection, $dataFile);
                }
                
                $restoredCollections[] = [
                    'name' => $collectionName,
                    'restored_count' => $restoredCount
                ];
                
                Log::info("Colección {$collectionName}: restaurados {$restoredCount} documentos");
            }
            
            // Limpiar directorio temporal
            $this->deleteDirectory($tempDir);
            
            // Registrar la restauración
            Log::info("Backup '{$backupName}' restaurado exitosamente por {$userName}");
            Log::info("Colecciones restauradas: " . json_encode($restoredCollections));
            
            $totalRestored = array_sum(array_column($restoredCollections, 'restored_count'));
            
            return redirect()->route('backups.index')
                ->with('success', 'Backup restaurado exitosamente.')
                ->with('info', "Backup original creado por: {$originalCreator} | Restaurado por: {$userName} | Documentos restaurados: {$totalRestored}");
                
        } catch (\Exception $e) {
            if (file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            
            Log::error("Error al restaurar backup por {$userName}: " . $e->getMessage());
            
            return back()->withErrors(['error' => 'Error al restaurar backup: ' . $e->getMessage()]);
        }
    }
    
    // Métodos auxiliares
    
    private function getMongoCollections()
    {
        try {
            Log::info('Intentando obtener colecciones de CoffeSoft');
            
            // Obtener la conexión de MongoDB desde config/database.php
            $connection = config('database.connections.mongodb');
            
            // Construir URI de conexión
            $host = $connection['host'] ?? '127.0.0.1';
            $port = $connection['port'] ?? 27017;
            $database = $connection['database'] ?? 'CoffeSoft';
            $username = $connection['username'] ?? null;
            $password = $connection['password'] ?? null;
            
            // Crear URI de conexión
            $uri = "mongodb://";
            if ($username && $password) {
                $uri .= "{$username}:{$password}@";
            }
            $uri .= "{$host}:{$port}";
            
            $client = new \MongoDB\Client($uri);
            $db = $client->selectDatabase($database);
            
            $collections = [];
            foreach ($db->listCollections() as $collectionInfo) {
                $collectionName = $collectionInfo->getName();
                $collections[] = $collectionName;
            }
            
            Log::info('Colecciones encontradas en CoffeSoft: ' . implode(', ', $collections));
            
            return $collections;
            
        } catch (\Exception $e) {
            Log::error('Error al obtener colecciones: ' . $e->getMessage());
            return [];
        }
    }
    
    private function exportToCSV($collectionName, $documents, $tempDir)
    {
        $filename = $tempDir . $collectionName . '.csv';
        
        if (empty($documents)) {
            file_put_contents($filename, '');
            return $filename;
        }
        
        $fp = fopen($filename, 'w');
        
        // Obtener headers del primer documento
        $firstDoc = (array) $documents[0];
        $headers = array_keys($firstDoc);
        fputcsv($fp, $headers);
        
        // Escribir datos
        foreach ($documents as $doc) {
            $doc = (array) $doc;
            
            // Asegurar que todos los campos existan
            $row = [];
            foreach ($headers as $header) {
                $row[] = isset($doc[$header]) ? 
                    (is_array($doc[$header]) ? json_encode($doc[$header]) : $doc[$header]) : 
                    '';
            }
            
            fputcsv($fp, $row);
        }
        
        fclose($fp);
        return $filename;
    }
    
    private function importFromCSV($collection, $csvFile)
    {
        $fp = fopen($csvFile, 'r');
        $headers = fgetcsv($fp);
        $insertedCount = 0;
        
        while (($row = fgetcsv($fp)) !== FALSE) {
            $document = [];
            foreach ($headers as $index => $header) {
                if (isset($row[$index])) {
                    // Intentar decodificar JSON si parece ser un array/objeto
                    $value = $row[$index];
                    if (!empty($value) && ($value[0] === '[' || $value[0] === '{')) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $value = $decoded;
                        }
                    }
                    $document[$header] = $value;
                }
            }
            
            if (!empty($document)) {
                $collection->insertOne($document);
                $insertedCount++;
            }
        }
        
        fclose($fp);
        return $insertedCount;
    }
    
    private function createZip($files, $zipPath, $basePath)
    {
        $zip = new ZipArchive;
        
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $relativePath = str_replace($basePath, '', $file);
                $zip->addFile($file, $relativePath);
            }
            $zip->close();
            return true;
        }
        
        return false;
    }
    
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) return;
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . $file;
            is_dir($path) ? $this->deleteDirectory($path . '/') : unlink($path);
        }
        rmdir($dir);
    }
    
    /**
     * Obtiene los metadatos de un archivo de backup
     */
    private function getBackupMetadata($filePath)
    {
        $metadata = [];
        
        // Si es un archivo ZIP, extraer y leer metadata
        if (pathinfo($filePath, PATHINFO_EXTENSION) === 'zip') {
            $zip = new ZipArchive;
            if ($zip->open($filePath) === TRUE) {
                $metadataContent = $zip->getFromName('metadata.json');
                if ($metadataContent) {
                    $metadata = json_decode($metadataContent, true);
                }
                $zip->close();
            }
        }
        
        return $metadata;
    }
    
    /**
     * Crea contenido de resumen en texto plano
     */
    private function createSummaryContent($metadata)
    {
        $content = "========================================\n";
        $content .= "         RESUMEN DEL BACKUP\n";
        $content .= "========================================\n\n";
        
        $content .= "INFORMACIÓN GENERAL:\n";
        $content .= "-------------------\n";
        $content .= "Nombre: {$metadata['backup_name']}\n";
        $content .= "Fecha: {$metadata['created_at']}\n";
        $content .= "Creado por: {$metadata['created_by']}\n";
        $content .= "Email: {$metadata['created_by_email']}\n";
        $content .= "Base de datos: {$metadata['database']}\n";
        $content .= "Formato: {$metadata['format']}\n";
        $content .= "Incluye estructura: " . ($metadata['include_structure'] ? 'Sí' : 'No') . "\n\n";
        
        $content .= "COLECCIONES INCLUIDAS:\n";
        $content .= "----------------------\n";
        foreach ($metadata['collections_details'] as $collection) {
            $content .= "- {$collection['name']}: {$collection['documents_count']} documentos\n";
        }
        $content .= "\nTotal colecciones: {$metadata['total_collections']}\n";
        $content .= "Total documentos: {$metadata['total_documents']}\n\n";
        
        $content .= "INFORMACIÓN DEL SISTEMA:\n";
        $content .= "------------------------\n";
        $content .= "PHP: {$metadata['system_info']['php_version']}\n";
        $content .= "Laravel: {$metadata['system_info']['laravel_version']}\n";
        $content .= "Servidor: {$metadata['system_info']['server']}\n";
        $content .= "\n========================================\n";
        $content .= "Backup generado automáticamente por el sistema\n";
        $content .= "========================================\n";
        
        return $content;
    }
    
    /**
     * Método para ver detalles del backup
     */
    public function viewBackupDetails($filename)
    {
        $filePath = $this->backupPath . $filename;
        
        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'El archivo de backup no existe.']);
        }
        
        $metadata = $this->getBackupMetadata($filePath);
        
        if (empty($metadata)) {
            return back()->withErrors(['error' => 'No se pudieron leer los metadatos del backup.']);
        }
        
        return view('admin.backups.details', [
            'metadata' => $metadata,
            'filename' => $filename
        ]);
    }
}