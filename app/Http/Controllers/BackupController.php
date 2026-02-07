<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use MongoDB\Client;
use Carbon\Carbon;
use ZipArchive;

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
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($filePath),
                        'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'path' => $filePath
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
        
        // Crear directorio temporal
        $tempDir = $this->backupPath . 'temp_' . time() . '/';
        mkdir($tempDir, 0755, true);
        
        try {
            $client = new Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
            $database = env('MONGODB_DATABASE', 'tu_base_datos');
            $db = $client->selectDatabase($database);
            
            $exportedFiles = [];
            
            foreach ($selectedCollections as $collectionName) {
                $collection = $db->selectCollection($collectionName);
                $documents = $collection->find()->toArray();
                
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
            
            // Crear archivo de metadatos
            $metadata = [
                'backup_name' => $backupName,
                'created_at' => Carbon::now()->toDateTimeString(),
                'collections' => $selectedCollections,
                'format' => $format,
                'include_structure' => $includeStructure,
                'database' => $database,
                'total_collections' => count($selectedCollections)
            ];
            
            file_put_contents($tempDir . 'metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
            $exportedFiles[] = $tempDir . 'metadata.json';
            
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
            
            return redirect()->route('backups.index')
                ->with('success', 'Backup creado exitosamente: ' . $finalFilename);
                
        } catch (\Exception $e) {
            // Limpiar en caso de error
            if (file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            
            return back()->withErrors(['error' => 'Error al crear backup: ' . $e->getMessage()]);
        }
    }
    
    public function downloadBackup($filename)
    {
        $filePath = $this->backupPath . $filename;
        
        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'El archivo de backup no existe.']);
        }
        
        return response()->download($filePath);
    }
    
    public function deleteBackup($filename)
    {
        $filePath = $this->backupPath . $filename;
        
        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'El archivo de backup no existe.']);
        }
        
        unlink($filePath);
        
        return redirect()->route('backups.index')
            ->with('success', 'Backup eliminado exitosamente.');
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
            
            // Conectar a MongoDB
            $client = new Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
            $database = env('MONGODB_DATABASE', 'tu_base_datos');
            $db = $client->selectDatabase($database);
            
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
                    $collection->deleteMany([]);
                }
                
                // Restaurar datos
                $extension = pathinfo($dataFile, PATHINFO_EXTENSION);
                if ($extension === 'json') {
                    $data = json_decode(file_get_contents($dataFile), true);
                    if (!empty($data)) {
                        $collection->insertMany($data);
                    }
                } elseif ($extension === 'csv') {
                    $this->importFromCSV($collection, $dataFile);
                }
            }
            
            // Limpiar directorio temporal
            $this->deleteDirectory($tempDir);
            
            return redirect()->route('backups.index')
                ->with('success', 'Backup restaurado exitosamente.');
                
        } catch (\Exception $e) {
            if (file_exists($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            
            return back()->withErrors(['error' => 'Error al restaurar backup: ' . $e->getMessage()]);
        }
    }
    
    // Métodos auxiliares
    
    private function getMongoCollections()
    {
        try {
            $client = new Client(env('MONGODB_URI', 'mongodb://localhost:27017'));
            $database = env('MONGODB_DATABASE', 'tu_base_datos');
            $db = $client->selectDatabase($database);
            
            $collections = [];
            foreach ($db->listCollections() as $collectionInfo) {
                $collections[] = $collectionInfo->getName();
            }
            
            return $collections;
        } catch (\Exception $e) {
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
            }
        }
        
        fclose($fp);
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
        }
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
}