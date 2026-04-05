<?php
/**
 * Upload de archivos - Maneja la subida de múltiples archivos
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

// Verificar que hay archivos
if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron archivos']);
    exit;
}

// Verificar si debe reemplazar archivos existentes
$replaceExisting = isset($_POST['replace']) && $_POST['replace'] === 'true';

$uploadedFiles = [];
$errors = [];
$totalFiles = count($_FILES['files']['name']);

// Procesar cada archivo
for ($i = 0; $i < $totalFiles; $i++) {
    $fileName = $_FILES['files']['name'][$i];
    $fileTmpName = $_FILES['files']['tmp_name'][$i];
    $fileSize = $_FILES['files']['size'][$i];
    $fileError = $_FILES['files']['error'][$i];
    
    // Verificar errores
    if ($fileError !== UPLOAD_ERR_OK) {
        $errors[] = "Error al subir {$fileName}";
        continue;
    }
    
    // Obtener extensión
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Validar extensión
    if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
        $errors[] = "{$fileName}: Tipo de archivo no permitido. Solo se permiten: " . implode(', ', ALLOWED_EXTENSIONS);
        continue;
    }
    
    // Validar tamaño (512KB máximo)
    $maxSize = 512 * 1024; // 512KB
    if ($fileSize > $maxSize) {
        $errors[] = "{$fileName}: File too large. Maximum 512KB";
        continue;
    }
    
    // Limpiar nombre de archivo
    $cleanFileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
    
    // Verificar si el archivo ya existe
    $destination = ASSETS_DIR . '/' . $cleanFileName;
    
    // Si no debe reemplazar y el archivo existe, agregar sufijo
    if (!$replaceExisting && file_exists($destination)) {
        $counter = 1;
        $fileNameWithoutExt = pathinfo($cleanFileName, PATHINFO_FILENAME);
        
        while (file_exists($destination)) {
            $cleanFileName = $fileNameWithoutExt . '_' . $counter . '.' . $fileExt;
            $destination = ASSETS_DIR . '/' . $cleanFileName;
            $counter++;
        }
    }
    // Si debe reemplazar, usar el mismo nombre (sobrescribe)
    
    // Mover archivo
    if (move_uploaded_file($fileTmpName, $destination)) {
        $uploadedFiles[] = [
            'name' => $cleanFileName,
            'size' => $fileSize,
            'type' => $fileExt,
            'path' => ASSETS_URL . $cleanFileName
        ];
    } else {
        $errors[] = "{$fileName}: Error al guardar el archivo";
    }
}

// Preparar respuesta
$response = [
    'success' => count($uploadedFiles) > 0,
    'uploaded' => count($uploadedFiles),
    'total' => $totalFiles,
    'files' => $uploadedFiles
];

if (!empty($errors)) {
    $response['errors'] = $errors;
}

echo json_encode($response);
