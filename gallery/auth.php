<?php
/**
 * Funciones de autenticación
 */

require_once __DIR__ . '/config.php';

/**
 * Verifica las credenciales del usuario
 */
function authenticate($username, $password) {
    return ($username === VALID_USERNAME && $password === VALID_PASSWORD);
}

/**
 * Inicia la sesión del usuario
 */
function loginUser() {
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();
    $_SESSION['username'] = VALID_USERNAME;
}

/**
 * Verifica si el usuario está autenticado
 */
function isLoggedIn() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        return false;
    }
    
    // Verificar timeout de sesión
    if (isset($_SESSION['login_time'])) {
        $elapsed = time() - $_SESSION['login_time'];
        if ($elapsed > SESSION_TIMEOUT) {
            logoutUser();
            return false;
        }
        // Actualizar tiempo de actividad
        $_SESSION['login_time'] = time();
    }
    
    return true;
}

/**
 * Cierra la sesión del usuario
 */
function logoutUser() {
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

/**
 * Redirige a la página de login si no está autenticado
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
