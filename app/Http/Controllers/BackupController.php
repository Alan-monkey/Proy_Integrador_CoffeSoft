<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Response;

$backupPath = storage_path('app/backups');
$fileName = 'mongo_backup_' . now()->format('Ymd_His');

if (!file_exists($backupPath)) {
    mkdir($backupPath, 0755, true);
}

$mongodumpPath = 'C:\Users\HP\Downloads\mongodb-database-tools-windows-x86_64-100.14.0\mongodb-database-tools-windows-x86_64-100.14.0\bin\mongodump.exe';

$process = new Process([
    $mongodumpPath,
    '--uri=mongodb://127.0.0.1:27017/coffeesoft',
    "--archive={$backupPath}\\{$fileName}.archive",
    '--gzip'
]);

$process->setTimeout(300);
$process->run();

if (!$process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

return Response::download(
    "{$backupPath}\\{$fileName}.archive"
)->deleteFileAfterSend(true);

