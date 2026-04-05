<?php
/**
 * Funciones para gestión de archivos
 */

require_once __DIR__ . '/config.php';

/**
 * Obtiene todos los archivos de la carpeta assets
 */
function getAssetFiles() {
    $files = [];
    
    if (!is_dir(ASSETS_DIR)) {
        return $files;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(ASSETS_DIR, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $extension = strtolower($file->getExtension());
            
            if (in_array($extension, ALLOWED_EXTENSIONS)) {
                $relativePath = str_replace(ASSETS_DIR . '/', '', $file->getPathname());
                
                $files[] = [
                    'name' => $file->getFilename(),
                    'path' => ASSETS_URL . $relativePath,
                    'full_path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'extension' => $extension,
                    'modified' => $file->getMTime(),
                    'relative_path' => $relativePath
                ];
            }
        }
    }
    
    // Ordenar por nombre
    usort($files, function($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
    
    return $files;
}

/**
 * Formatea el tamaño del archivo
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Obtiene el tipo MIME de una imagen
 */
function getImageType($extension) {
    $types = [
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon'
    ];
    
    return $types[$extension] ?? 'image/png';
}
