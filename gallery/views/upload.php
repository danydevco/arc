<?php
/**
 * Vista de upload - Formulario para subir archivos
 */

// Esta vista ya incluye la autenticación desde el router
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files - Assets Gallery</title>
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
        
        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .upload-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .page-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        
        .upload-dropzone {
            border: 3px dashed #e0e0e0;
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
            transition: all 0.3s;
            background: #fafbfc;
            cursor: pointer;
        }
        
        .upload-dropzone.drag-over {
            border-color: #667eea;
            background: #f0f3ff;
        }
        
        .dropzone-content svg {
            color: #667eea;
            margin-bottom: 20px;
            stroke-width: 1.5;
        }
        
        .dropzone-content h3 {
            color: #333;
            margin: 0 0 10px;
            font-size: 18px;
        }
        
        .dropzone-content p {
            color: #666;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .btn-select {
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-select:hover {
            transform: translateY(-2px);
        }
        
        .preview-container {
            margin-top: 40px;
        }
        
        .preview-container h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
        }
        
        .preview-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .preview-item {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            position: relative;
        }
        
        .preview-image {
            width: 100%;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .preview-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .preview-name {
            font-size: 13px;
            color: #666;
            word-break: break-word;
            text-align: center;
        }
        
        .preview-size {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 4px;
        }
        
        .preview-remove {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            font-weight: bold;
        }
        
        .preview-remove:hover {
            background: #dc2626;
        }
        
        .upload-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn-upload {
            padding: 14px 40px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-upload:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        
        .btn-upload:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-cancel {
            padding: 14px 40px;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background: #4b5563;
        }
        
        .progress-container {
            margin-top: 30px;
        }
        
        .progress-bar {
            width: 100%;
            height: 40px;
            background: #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s;
            width: 0%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .progress-container p {
            text-align: center;
            color: #666;
            font-size: 16px;
        }
        
        .upload-messages {
            margin-top: 30px;
        }
        
        .message {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 15px;
        }
        
        .message-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .message-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .message-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        
        @media (max-width: 768px) {
            .upload-container {
                padding: 20px;
            }
            
            .preview-list {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="container">
        <div class="upload-container">
            <h2 class="page-title">Upload Files to Gallery</h2>
            <p class="page-description">Select or drag files you want to upload to the assets folder</p>
            
            <div class="upload-dropzone" id="dropzone">
                <div class="dropzone-content">
                    <i data-feather="upload-cloud" style="width: 80px; height: 80px; color: #667eea; margin-bottom: 20px;"></i>
                    <h3>Drag files here or click to select</h3>
                    <p>Allowed formats: SVG, PNG, JPG, JPEG, GIF, WEBP, ICO</p>
                    <p>Maximum size: 512KB per file</p>
                    <input type="file" id="fileInput" multiple accept=".svg,.png,.jpg,.jpeg,.gif,.webp,.ico" style="display: none;">
                    <button type="button" class="btn-select" onclick="document.getElementById('fileInput').click()">
                        Select Files
                    </button>
                </div>
            </div>
            
            <!-- Preview de archivos seleccionados -->
            <div id="previewContainer" class="preview-container" style="display: none;">
                <h3>Selected files (<span id="fileCount">0</span>):</h3>
                <div id="previewList" class="preview-list"></div>
                <div class="upload-actions">
                    <button type="button" id="uploadBtn" class="btn-upload">Upload Files</button>
                    <button type="button" id="cancelBtn" class="btn-cancel">Cancel</button>
                </div>
            </div>
            
            <!-- Barra de progreso -->
            <div id="progressContainer" class="progress-container" style="display: none;">
                <div class="progress-bar">
                    <div id="progressBar" class="progress-fill">0%</div>
                </div>
                <p id="progressText">Uploading files...</p>
            </div>
            
            <!-- Mensajes -->
            <div id="uploadMessages" class="upload-messages"></div>
        </div>
    </div>
    
    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const previewList = document.getElementById('previewList');
        const fileCount = document.getElementById('fileCount');
        const uploadBtn = document.getElementById('uploadBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const uploadMessages = document.getElementById('uploadMessages');
        
        let selectedFiles = [];
        
        // Click en dropzone para abrir selector de archivos
        dropzone.addEventListener('click', (e) => {
            // Evitar que el clic en el botón dispare el evento dos veces
            if (e.target.tagName !== 'BUTTON') {
                fileInput.click();
            }
        });
        
        // Drag and drop events
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('drag-over');
        });
        
        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('drag-over');
        });
        
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('drag-over');
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        });
        
        // File input change
        fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            handleFiles(files);
        });
        
        // Handle files
        function handleFiles(files) {
            selectedFiles = files;
            previewList.innerHTML = '';
            
            if (files.length === 0) {
                previewContainer.style.display = 'none';
                return;
            }
            
            // Validar tamaño de archivos (512KB máximo)
            const maxSize = 512 * 1024; // 512KB
            const invalidFiles = [];
            
            files.forEach(file => {
                if (file.size > maxSize) {
                    invalidFiles.push(file.name);
                }
            });
            
            if (invalidFiles.length > 0) {
                Swal.fire({
                    title: 'Files Too Large',
                    html: `The following files exceed the 512KB limit:<br><br><strong>${invalidFiles.join('<br>')}</strong><br><br>Please select smaller files.`,
                    icon: 'error',
                    confirmButtonColor: '#667eea'
                });
                fileInput.value = '';
                previewContainer.style.display = 'none';
                return;
            }
            
            fileCount.textContent = files.length;
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = (e) => {
                    const fileSize = formatFileSize(file.size);
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <button class="preview-remove" onclick="removeFile(${index})">×</button>
                        <div class="preview-image">
                            <img src="${e.target.result}" alt="${file.name}">
                        </div>
                        <div class="preview-name">${file.name}</div>
                        <div class="preview-size">${fileSize}</div>
                    `;
                    previewList.appendChild(previewItem);
                };
                
                reader.readAsDataURL(file);
            });
            
            previewContainer.style.display = 'block';
            uploadMessages.innerHTML = '';
        }
        
        // Format file size
        function formatFileSize(bytes) {
            if (bytes >= 1048576) {
                return (bytes / 1048576).toFixed(2) + ' MB';
            } else if (bytes >= 1024) {
                return (bytes / 1024).toFixed(2) + ' KB';
            } else {
                return bytes + ' bytes';
            }
        }
        
        // Remove file from selection
        window.removeFile = function(index) {
            selectedFiles = Array.from(selectedFiles).filter((_, i) => i !== index);
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            handleFiles(selectedFiles);
        };
        
        // Cancel upload
        cancelBtn.addEventListener('click', () => {
            selectedFiles = [];
            fileInput.value = '';
            previewContainer.style.display = 'none';
            uploadMessages.innerHTML = '';
        });
        
        // Upload files
        uploadBtn.addEventListener('click', async () => {
            if (selectedFiles.length === 0) return;
            
            // Verificar duplicados primero
            const fileNames = Array.from(selectedFiles).map(file => file.name);
            
            try {
                const checkResponse = await fetch('check-files.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ files: fileNames })
                });
                
                const checkResult = await checkResponse.json();
                
                let replaceExisting = false;
                
                // Si hay duplicados, preguntar qué hacer
                if (checkResult.hasDuplicates) {
                    const duplicatesList = checkResult.duplicates.map(dup => 
                        `<li><strong>${dup.name}</strong> (${formatFileSize(dup.size)})</li>`
                    ).join('');
                    
                    const result = await Swal.fire({
                        title: 'Duplicate Files Detected',
                        html: `
                            <p style="text-align: left; margin-bottom: 15px;">The following files already exist:</p>
                            <ul style="text-align: left; margin: 0 0 15px 20px; max-height: 200px; overflow-y: auto;">
                                ${duplicatesList}
                            </ul>
                            <p style="text-align: left; color: #666; font-size: 14px;">What would you like to do?</p>
                        `,
                        icon: 'warning',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Replace',
                        denyButtonText: 'Keep Both',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#ef4444',
                        denyButtonColor: '#667eea',
                        cancelButtonColor: '#6b7280'
                    });
                    
                    if (result.isDismissed) {
                        return; // Usuario canceló
                    }
                    
                    replaceExisting = result.isConfirmed; // true si eligió "Replace"
                }
                
                // Continuar con el upload
                const formData = new FormData();
                selectedFiles.forEach(file => {
                    formData.append('files[]', file);
                });
                
                // Agregar parámetro de reemplazo
                if (replaceExisting) {
                    formData.append('replace', 'true');
                }
                
                // Show progress
                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';
                progressBar.textContent = '0%';
                uploadBtn.disabled = true;
                cancelBtn.disabled = true;
                uploadMessages.innerHTML = '';
            
                try {
                    const response = await fetch('upload.php', {
                        method: 'POST',
                        body: formData
                    });
                
                const result = await response.json();
                
                // Update progress
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                progressText.textContent = 'Completed!';
                
                if (result.success) {
                    // Preparar mensaje de éxito
                    let successMessage = `${result.uploaded} of ${result.total} files uploaded successfully`;
                    
                    // Si hay errores, mostrarlos en el HTML
                    let errorHtml = '';
                    if (result.errors && result.errors.length > 0) {
                        errorHtml = '<div style="margin-top: 15px; text-align: left; font-size: 14px;">';
                        errorHtml += '<strong>Errors:</strong><ul style="margin: 8px 0 0 20px;">';
                        result.errors.forEach(error => {
                            errorHtml += `<li>${error}</li>`;
                        });
                        errorHtml += '</ul></div>';
                    }
                    
                    // Mostrar SweetAlert de éxito
                    await Swal.fire({
                        title: 'Upload Complete!',
                        html: successMessage + errorHtml,
                        icon: 'success',
                        confirmButtonColor: '#667eea',
                        confirmButtonText: 'View Gallery'
                    });
                    
                    // Redirigir a la galería
                    window.location.href = '/gallery';
                } else {
                    // Error general
                    await Swal.fire({
                        title: 'Upload Failed',
                        text: result.message || 'Could not upload files',
                        icon: 'error',
                        confirmButtonColor: '#667eea'
                    });
                }
                
                } catch (error) {
                    await Swal.fire({
                        title: 'Error',
                        text: `Error uploading files: ${error.message}`,
                        icon: 'error',
                        confirmButtonColor: '#667eea'
                    });
                } finally {
                    uploadBtn.disabled = false;
                    cancelBtn.disabled = false;
                    progressContainer.style.display = 'none';
                }
            } catch (error) {
                // Error al verificar duplicados
                await Swal.fire({
                    title: 'Error',
                    text: `Error checking files: ${error.message}`,
                    icon: 'error',
                    confirmButtonColor: '#667eea'
                });
            }
        });
        
        // Inicializar Feather Icons
        feather.replace();
    </script>
</body>
</html>
