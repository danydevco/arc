<?php
/**
 * Configuración general del sistema
 */

// Credenciales hardcodeadas
define('VALID_USERNAME', 'admin');
define('VALID_PASSWORD', 'r2uM9i4;i£1');

// Configuración de sesión
define('SESSION_NAME', 'finan_gallery_session');
define('SESSION_TIMEOUT', 3600); // 1 hora en segundos

// Directorios base
define('BASE_DIR', dirname(__DIR__));
define('GALLERY_DIR', __DIR__);

// Directorio de assets
define('ASSETS_DIR', BASE_DIR . '/assets');
define('ASSETS_URL', '../assets/');

// Extensiones de archivo permitidas
define('ALLOWED_EXTENSIONS', ['svg', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'ico']);

// URLs base
define('BASE_URL', '/gallery');

// Iniciar sesión
session_name(SESSION_NAME);
session_start();
