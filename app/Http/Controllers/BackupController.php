<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function download()
{
    $backupPath = storage_path('app/backups');
    $fileName = 'mongo_backup_' . date('Ymd_His');

    if (!file_exists($backupPath)) {
        mkdir($backupPath, 0755, true);
    }

    $process = new Process([
            'C:\Users\HP\Downloads\mongodb-database-tools-windows-x86_64-100.14.0\mongodb-database-tools-windows-x86_64-100.14.0\bin\mongodump.exe',
        '--host=127.0.0.1',
        '--port=27017',
        '--db=CoffeSoft',
        '--archive=' . $backupPath . DIRECTORY_SEPARATOR . $fileName . '.archive',
        '--gzip',
    ]);

    $process->setTimeout(300); // opcional pero recomendado

    $process->run();

    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    return response()->download(
        $backupPath . DIRECTORY_SEPARATOR . $fileName . '.archive'
    )->deleteFileAfterSend(true);
}
}