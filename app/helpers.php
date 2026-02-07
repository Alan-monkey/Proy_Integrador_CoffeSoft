<?php

if (!function_exists('formatBytes')) {
    /**
     * Formatea bytes a unidades legibles (KB, MB, GB, etc.)
     *
     * @param int $bytes
     * @param int $decimals
     * @return string
     */
    function formatBytes($bytes, $decimals = 2)
    {
        if ($bytes === 0) {
            return '0 Bytes';
        }
        
        $k = 1024;
        $dm = $decimals < 0 ? 0 : $decimals;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        
        $i = floor(log($bytes) / log($k));
        
        return number_format($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
    }
}

if (!function_exists('isMongoDate')) {
    /**
     * Verifica si un valor es una fecha de MongoDB
     *
     * @param mixed $value
     * @return bool
     */
    function isMongoDate($value)
    {
        return $value instanceof \MongoDB\BSON\UTCDateTime;
    }
}