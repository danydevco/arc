<?php
/**
 * Redirección automática a la galería de assets
 * 
 * Este archivo es el punto de entrada principal del subdominio.
 * Verifica si el usuario está autenticado y redirige apropiadamente.
 */

// Incluir el sistema de autenticación
require_once __DIR__ . '/gallery/auth.php';

// Verificar si el usuario está autenticado
if (isLoggedIn()) {
    // Si está autenticado, redirigir directamente a la galería
    header('Location: /gallery');
    exit;
} else {
    // Si no está autenticado, redirigir al login
    header('Location: /gallery/login');
    exit;
}
