<?php
/**
 * Navbar compartido para todas las páginas
 */

// Determinar la página actual para mostrar el contador de archivos
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$isGallery = ($currentPage === 'gallery' || $_SERVER['REQUEST_URI'] === '/gallery' || strpos($_SERVER['REQUEST_URI'], '/gallery?') === 0);
?>
<header class="header">
    <div class="header-container">
        <div class="header-title">
            <h1>Gallery</h1>
        </div>
        <div class="header-actions">
            <a href="/gallery" class="nav-link">Home</a>
            <a href="/gallery/upload" class="nav-link">Upload</a>
            <a href="/gallery/logout" class="btn-logout">Logout</a>
        </div>
    </div>
</header>

<style>
    .header {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px 0;
        position: sticky;
        top: 0;
        z-index: 100;
    }
    
    .header-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .header-title h1 {
        color: #333;
        font-size: 24px;
    }
    
    .file-count {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .header-actions {
        display: flex;
        gap: 25px;
        align-items: center;
    }
    
    .nav-link {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.2s;
    }
    
    .nav-link:hover {
        color: #667eea;
    }
    
    .btn-logout {
        color: #ef4444;
        text-decoration: none;
        font-weight: 500;
        font-size: 15px;
        transition: all 0.2s;
    }
    
    .btn-logout:hover {
        color: #dc2626;
    }
    
    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            gap: 15px;
        }
        
        .header-title {
            flex-direction: column;
            gap: 10px;
        }
        
        .header-actions {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

<script>
    // Inicializar Feather Icons cuando el DOM esté listo (solo para íconos de la galería)
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
