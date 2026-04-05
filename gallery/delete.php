<?php
/**
 * Delete de archivos - Maneja la eliminación de archivos
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Verificar autenticación
requireAuth();

// Respuesta JSON
header('Content-Type: application/json');

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener datos
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['file']) || empty($data['file'])) {
    echo json_encode(['success' => false, 'message' => 'No se especificó el archivo']);
    exit;
}

$filePath = $data['file'];

// Sanitizar la ruta del archivo
$filePath = str_replace(['../', '..\\'], '', $filePath);

// Construir ruta completa
$fullPath = ASSETS_DIR . '/' . $filePath;

// Verificar que el archivo existe
if (!file_exists($fullPath)) {
    echo json_encode(['success' => false, 'message' => 'El archivo no existe']);
    exit;
}

// Verificar que está dentro del directorio de assets
$realPath = realpath($fullPath);
$assetsPath = realpath(ASSETS_DIR);

if (strpos($realPath, $assetsPath) !== 0) {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

// Verificar que es un archivo (no un directorio)
if (!is_file($fullPath)) {
    echo json_encode(['success' => false, 'message' => 'Solo se pueden eliminar archivos']);
    exit;
}

// Intentar eliminar el archivo
if (unlink($fullPath)) {
    echo json_encode([
        'success' => true,
        'message' => 'Archivo eliminado correctamente',
        'file' => $filePath
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo eliminar el archivo. Verifica los permisos.'
    ]);
}
