<?php
/**
 * Router principal - Maneja todas las rutas amigables
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/file_manager.php';

// Obtener la ruta solicitada
$request = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($scriptName, '', $request);
$path = trim(parse_url($path, PHP_URL_PATH), '/');

// Eliminar query string de la ruta
$path = explode('?', $path)[0];

// Remover "gallery/" del inicio si existe para normalizar
$path = preg_replace('#^gallery/?#', '', $path);

// Router simple
switch ($path) {
    case '':
        requireAuth();
        require __DIR__ . '/views/gallery.php';
        break;
        
    case 'login':
        if (isLoggedIn()) {
            header('Location: /gallery');
            exit;
        }
        require __DIR__ . '/views/login.php';
        break;
        
    case 'logout':
        logoutUser();
        header('Location: /gallery/login');
        exit;
        break;
        
    case 'upload':
        requireAuth();
        require __DIR__ . '/views/upload.php';
        break;
        
    case 'upload-handler':
        requireAuth();
        require __DIR__ . '/upload.php';
        break;
        
    case 'delete':
        requireAuth();
        require __DIR__ . '/delete.php';
        break;
        
    case 'download':
        requireAuth();
        require __DIR__ . '/download.php';
        break;
        
    case 'check-files':
        requireAuth();
        require __DIR__ . '/check-files.php';
        break;
        
    default:
        http_response_code(404);
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Página no encontrada</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-align: center;
                    padding: 20px;
                }
                h1 { font-size: 72px; margin: 0; }
                p { font-size: 24px; margin: 20px 0; }
                a { color: white; text-decoration: underline; }
                .debug { 
                    margin-top: 20px; 
                    font-size: 14px; 
                    opacity: 0.7; 
                    font-family: monospace;
                }
            </style>
        </head>
        <body>
            <div>
                <h1>404</h1>
                <p>Página no encontrada</p>
                <p class="debug">Ruta solicitada: ' . htmlspecialchars($path) . '</p>
                <a href="/gallery/login">Ir al login</a>
            </div>
        </body>
        </html>';
        break;
}
