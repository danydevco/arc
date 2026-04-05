<?php
/**
 * Check de archivos - Verifica si archivos ya existen en la galería
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Verificar autenticación
requireAuth();

// Respuesta JSON
header('Content-Type: application/json');

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Obtener los nombres de archivos a verificar
$data = json_decode(file_get_contents('php://input'), true);
$fileNames = $data['files'] ?? [];

if (empty($fileNames)) {
    echo json_encode(['success' => false, 'message' => 'No files provided']);
    exit;
}

$duplicates = [];

foreach ($fileNames as $fileName) {
    // Limpiar nombre de archivo (mismo proceso que en upload)
    $cleanFileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
    $filePath = ASSETS_DIR . '/' . $cleanFileName;
    
    if (file_exists($filePath)) {
        $fileSize = filesize($filePath);
        $duplicates[] = [
            'name' => $cleanFileName,
            'size' => $fileSize
        ];
    }
}

echo json_encode([
    'success' => true,
    'duplicates' => $duplicates,
    'hasDuplicates' => count($duplicates) > 0
]);
