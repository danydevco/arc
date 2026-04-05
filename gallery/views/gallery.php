<?php
/**
 * Vista de la galería
 */

// Obtener archivos
$files = getAssetFiles();
$totalFiles = count($files);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Assets - Finan</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .search-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .search-input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        
        .search-input-wrapper input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
            background: white;
        }
        
        .search-input-wrapper input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 20px;
            display: flex;
            align-items: center;
        }
        
        .search-icon svg {
            width: 20px;
            height: 20px;
        }
        
        .filters-wrapper {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-group select {
            width: 100%;
            padding: 15px 40px 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            height: 54px;
        }
        
        .filter-group select:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }
        
        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }
        
        .btn-clear-filters {
            padding: 15px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            height: 54px;
        }
        
        .btn-clear-filters:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-clear-filters:active {
            transform: translateY(0);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .file-list {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-top: 20px;
        }
        
        .file-item {
            background: white;
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }
        
        .file-item:first-child {
            border-radius: 12px 12px 0 0;
        }
        
        .file-item:last-child {
            border-radius: 0 0 12px 12px;
            border-bottom: none;
        }
        
        .file-item:only-child {
            border-radius: 12px;
        }
        
        .file-item:hover {
            background: #f9fafb;
            padding-left: 25px;
            box-shadow: inset 4px 0 0 #667eea;
        }
        
        .file-item:hover .file-actions {
            opacity: 1;
        }
        
        .file-actions {
            display: flex;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .btn-download,
        .btn-delete {
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        
        .btn-download {
            background: #667eea;
            color: white;
        }
        
        .btn-download svg {
            width: 16px;
            height: 16px;
        }
        
        .btn-download:hover {
            background: #5568d3;
            transform: scale(1.05);
        }
        
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        
        .btn-delete svg {
            width: 16px;
            height: 16px;
        }
        
        .btn-delete:hover {
            background: #dc2626;
            transform: scale(1.05);
        }
        
        .file-icon {
            width: 64px;
            height: 64px;
            min-width: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .file-icon img {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }
        
        .file-info {
            flex: 1;
            min-width: 0;
        }
        
        .file-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 15px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            transition: color 0.2s;
            user-select: none;
        }
        
        .file-name:hover {
            color: #667eea;
        }
        
        .file-name.copied {
            color: #10b981;
        }
        
        .file-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 13px;
            color: #6b7280;
        }
        
        .file-detail {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .file-detail-label {
            font-weight: 500;
            color: #9ca3af;
        }
        
        .extension-badge {
            display: inline-flex;
            padding: 0;
            background: transparent;
            color: #6b7280;
            border-radius: 0;
            font-size: 13px;
            font-weight: 400;
            align-items: center;
        }
        
        .file-path {
            color: #9ca3af;
            font-size: 12px;
            font-family: 'Monaco', 'Courier New', monospace;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .no-results h2 {
            font-size: 24px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .no-results h2 svg {
            width: 32px;
            height: 32px;
        }
        
        @media (max-width: 768px) {
            .filters-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }
            
            .filter-group {
                min-width: 100%;
            }
            
            .btn-clear-filters {
                width: 100%;
            }
            
            .file-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="search-container">
        <div class="search-input-wrapper">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Search files by name or path..."
                autocomplete="off"
            >
            <span class="search-icon"><i data-feather="search"></i></span>
        </div>
        
        <div class="filters-wrapper">
            <div class="filter-group">
                <select id="typeFilter">
                    <option value="all">All Types</option>
                    <option value="svg">SVG</option>
                    <option value="png">PNG</option>
                    <option value="jpg">JPG</option>
                    <option value="jpeg">JPEG</option>
                    <option value="gif">GIF</option>
                    <option value="webp">WEBP</option>
                    <option value="ico">ICO</option>
                </select>
            </div>
            
            <div class="filter-group">
                <select id="sortFilter">
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="name-desc">Name (Z-A)</option>
                    <option value="size-asc">Size (Small → Large)</option>
                    <option value="size-desc">Size (Large → Small)</option>
                </select>
            </div>
            
            <button id="clearFilters" class="btn-clear-filters">Clear All</button>
        </div>
    </div>
    
    <div class="container">
        <div class="file-list" id="fileList">
            <?php foreach ($files as $file): ?>
                <div class="file-item" 
                     data-name="<?php echo htmlspecialchars(strtolower($file['name'])); ?>"
                     data-path="<?php echo htmlspecialchars(strtolower($file['relative_path'])); ?>"
                     data-extension="<?php echo htmlspecialchars(strtolower($file['extension'])); ?>"
                     data-size="<?php echo $file['size']; ?>"
                     data-fullpath="<?php echo htmlspecialchars($file['relative_path']); ?>">
                    
                    <div class="file-icon">
                        <img 
                            src="<?php echo htmlspecialchars($file['path']); ?>" 
                            alt="<?php echo htmlspecialchars($file['name']); ?>"
                            loading="lazy"
                        >
                    </div>
                    
                    <div class="file-info">
                        <div class="file-name" onclick="copyFileName('<?php echo htmlspecialchars($file['name']); ?>', this)" title="Click to copy filename">
                            <?php echo htmlspecialchars($file['name']); ?>
                        </div>
                        <div class="file-details">
                            <div class="file-detail">
                                <span class="file-detail-label">Size:</span>
                                <span><?php echo formatFileSize($file['size']); ?></span>
                            </div>
                            <div class="file-detail">
                                <span class="file-detail-label">Type:</span>
                                <span class="extension-badge"><?php echo htmlspecialchars($file['extension']); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="file-actions">
                        <a href="/gallery/download?file=<?php echo urlencode($file['relative_path']); ?>" class="btn-download" title="Download">
                            <i data-feather="download"></i>
                        </a>
                        <button class="btn-delete" onclick="deleteFile('<?php echo htmlspecialchars($file['relative_path']); ?>', '<?php echo htmlspecialchars($file['name']); ?>', this)" title="Delete">
                            <i data-feather="trash-2"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="no-results" id="noResults" style="display: none;">
            <h2><i data-feather="frown"></i> No se encontraron resultados</h2>
            <p>Intenta con otros términos de búsqueda</p>
        </div>
    </div>
    
    <script>
        // Referencias a elementos
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');
        const sortFilter = document.getElementById('sortFilter');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const fileList = document.getElementById('fileList');
        const noResults = document.getElementById('noResults');
        let items = Array.from(document.querySelectorAll('.file-item'));
        
        // Función para aplicar todos los filtros
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const typeValue = typeFilter.value;
            const sortValue = sortFilter.value;
            
            let visibleCount = 0;
            
            // Filtrar items
            items.forEach(item => {
                const name = item.getAttribute('data-name');
                const path = item.getAttribute('data-path');
                const extension = item.getAttribute('data-extension');
                
                // Aplicar filtro de búsqueda
                const matchesSearch = !searchTerm || name.includes(searchTerm) || path.includes(searchTerm);
                
                // Aplicar filtro de tipo
                const matchesType = typeValue === 'all' || extension === typeValue;
                
                if (matchesSearch && matchesType) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Mostrar/ocultar mensaje de "no results"
            if (visibleCount === 0) {
                fileList.style.display = 'none';
                noResults.innerHTML = '<h2><i data-feather="frown"></i> No results found</h2><p>Try different search terms or filters</p>';
                noResults.style.display = 'block';
                feather.replace();
            } else {
                fileList.style.display = 'flex';
                noResults.style.display = 'none';
            }
            
            // Aplicar ordenamiento
            applySorting(sortValue);
        }
        
        // Función para ordenar items
        function applySorting(sortValue) {
            const visibleItems = items.filter(item => item.style.display !== 'none');
            
            visibleItems.sort((a, b) => {
                switch(sortValue) {
                    case 'name-asc':
                        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                    case 'name-desc':
                        return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
                    case 'size-asc':
                        return parseInt(a.getAttribute('data-size')) - parseInt(b.getAttribute('data-size'));
                    case 'size-desc':
                        return parseInt(b.getAttribute('data-size')) - parseInt(a.getAttribute('data-size'));
                    default:
                        return 0;
                }
            });
            
            // Reordenar en el DOM
            visibleItems.forEach(item => {
                fileList.appendChild(item);
            });
        }
        
        // Event listeners para filtros
        searchInput.addEventListener('input', applyFilters);
        typeFilter.addEventListener('change', applyFilters);
        sortFilter.addEventListener('change', applyFilters);
        
        // Limpiar filtros
        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            typeFilter.value = 'all';
            sortFilter.value = 'name-asc';
            applyFilters();
        });
        
        // Función para eliminar archivo
        async function deleteFile(filePath, fileName, button) {
            // Confirmación con SweetAlert2
            const result = await Swal.fire({
                title: 'Delete file?',
                text: `Are you sure you want to delete "${fileName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            });
            
            if (!result.isConfirmed) {
                return;
            }
            
            // Deshabilitar botón
            button.disabled = true;
            
            // Mostrar loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const response = await fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        file: filePath
                    })
                });
                
                const deleteResult = await response.json();
                
                if (deleteResult.success) {
                    // Cerrar loading y mostrar éxito
                    await Swal.fire({
                        title: 'Deleted!',
                        text: 'File has been deleted successfully',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    // Eliminar el item del DOM con animación
                    const fileItem = button.closest('.file-item');
                    fileItem.style.opacity = '0';
                    fileItem.style.transform = 'translateX(-20px)';
                    
                    setTimeout(() => {
                        fileItem.remove();
                        
                        // Verificar si quedan archivos
                        const remainingItems = document.querySelectorAll('.file-item');
                        if (remainingItems.length === 0) {
                            fileList.style.display = 'none';
                            noResults.innerHTML = '<h2><i data-feather="folder"></i> No files</h2><p>Upload files to get started</p>';
                            noResults.style.display = 'block';
                            feather.replace();
                        }
                    }, 300);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: deleteResult.message || 'Could not delete file',
                        icon: 'error',
                        confirmButtonColor: '#667eea'
                    });
                    button.disabled = false;
                    button.innerHTML = '<i data-feather="trash-2"></i>';
                    feather.replace();
                }
                
            } catch (error) {
                Swal.fire({
                    title: 'Error',
                    text: `Error deleting file: ${error.message}`,
                    icon: 'error',
                    confirmButtonColor: '#667eea'
                });
                button.disabled = false;
                button.innerHTML = '<i data-feather="trash-2"></i>';
                feather.replace();
            }
        }
        
        // Función para copiar el nombre del archivo
        async function copyFileName(fileName, element) {
            try {
                await navigator.clipboard.writeText(fileName);
                
                // Feedback visual
                element.classList.add('copied');
                const originalTitle = element.getAttribute('title');
                element.setAttribute('title', 'Copied!');
                
                // Mostrar toast
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                
                Toast.fire({
                    icon: 'success',
                    title: 'Filename copied!'
                });
                
                // Restaurar después de 2 segundos
                setTimeout(() => {
                    element.classList.remove('copied');
                    element.setAttribute('title', originalTitle);
                }, 2000);
                
            } catch (error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Could not copy to clipboard',
                    icon: 'error',
                    confirmButtonColor: '#667eea',
                    timer: 2000
                });
            }
        }
        
        // Hacer las funciones globales
        window.deleteFile = deleteFile;
        window.copyFileName = copyFileName;
        
        // Inicializar Feather Icons
        feather.replace();
    </script>
</body>
</html>
