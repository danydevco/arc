<?php
/**
 * Download de archivos - Permite descargar archivos de la galería
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Verificar autenticación
requireAuth();

// Obtener el archivo solicitado
$file = $_GET['file'] ?? '';

if (empty($file)) {
    http_response_code(400);
    die('No file specified');
}

// Sanitizar el path para evitar directory traversal
$file = str_replace(['../', '..\\'], '', $file);

// Construir path completo
$filePath = ASSETS_DIR . '/' . $file;

// Verificar que el archivo existe
if (!file_exists($filePath)) {
    http_response_code(404);
    die('File not found');
}

// Verificar que es un archivo y no un directorio
if (!is_file($filePath)) {
    http_response_code(403);
    die('Invalid file');
}

// Verificar que el archivo está dentro del directorio de assets
$realFilePath = realpath($filePath);
$realAssetsDir = realpath(ASSETS_DIR);

if (strpos($realFilePath, $realAssetsDir) !== 0) {
    http_response_code(403);
    die('Access denied');
}

// Obtener información del archivo
$fileName = basename($filePath);
$fileSize = filesize($filePath);
$mimeType = mime_content_type($filePath);

// Establecer headers para descarga
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . $fileSize);
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Limpiar buffer de salida
if (ob_get_level()) {
    ob_end_clean();
}

// Enviar el archivo
readfile($filePath);
exit;
