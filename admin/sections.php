<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';
requireAuth();

$db = Database::getInstance();
$sections = $db->fetchAll("SELECT * FROM sections ORDER BY display_order");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seções • Mir4Mediator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&display=swap');
        body { font-family: 'Oxanium', sans-serif; }
        .drag-over { border-color: #f5b915; background-color: rgba(245, 185, 21, 0.1); }
        .sortable-ghost { opacity: 0.4; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 border-r border-gray-700">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="<?php echo getSetting('site_logo'); ?>" alt="Logo">
                    <span class="ml-2 text-yellow-400 font-bold text-xl"><?php echo getSetting('site_name'); ?></span>
                </div>
                <div class="mt-2 text-sm text-gray-400">Painel Administrativo</div>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="dashboard.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Dashboard</a>
                <a href="sections.php" class="block px-3 py-2 bg-yellow-500 bg-opacity-10 text-yellow-400 rounded-lg">Seções</a>
                <a href="accounts.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Contas</a>
                <a href="faqs.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">FAQ</a>
                <a href="testimonials.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Depoimentos</a>
                <a href="partners.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Parceiros</a>
                <a href="settings.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Configurações</a>
                <a href="logout.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Sair</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Gerenciar Seções</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <?php foreach ($sections as $section): ?>
                <div class="bg-gray-800 rounded-lg p-6" data-id="<?php echo $section['id']; ?>">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white capitalize"><?php echo $section['name']; ?></h3>
                        <div class="flex items-center space-x-2">
                            <button onclick="editSection(<?php echo $section['id']; ?>)" 
                                    class="text-gray-400 hover:text-yellow-400 p-2 rounded-lg hover:bg-gray-700 transition duration-300">
                                <i data-feather="edit" class="w-4 h-4"></i>
                            </button>
                            <button onclick="toggleSection(<?php echo $section['id']; ?>, <?php echo $section['is_active'] ? 'false' : 'true'; ?>)" 
                                    class="text-gray-400 hover:text-<?php echo $section['is_active'] ? 'red' : 'green'; ?>-400 p-2 rounded-lg hover:bg-gray-700 transition duration-300">
                                <i data-feather="<?php echo $section['is_active'] ? 'eye-off' : 'eye'; ?>" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Título:</label>
                            <p class="text-white"><?php echo htmlspecialchars($section['title']); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Subtítulo:</label>
                            <p class="text-gray-300 text-sm"><?php echo htmlspecialchars($section['subtitle']); ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Imagem de Fundo:</label>
                            <div class="mt-2 h-32 bg-cover bg-center rounded-lg" style="background-image: url('<?php echo $section['background_image']; ?>')"></div>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $section['is_active'] ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'; ?>">
                            <?php echo $section['is_active'] ? 'Ativa' : 'Inativa'; ?>
                        </span>
                        <span class="text-gray-400 text-sm">Ordem: <?php echo $section['display_order']; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div id="sectionModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75 p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-yellow-400">Editar Seção</h3>
                    <button onclick="closeSectionModal()" class="text-gray-400 hover:text-white transition duration-300">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            
            <form id="sectionForm" class="p-6 space-y-6">
                <input type="hidden" id="section_id">
                <input type="hidden" id="section_name">
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                    <input type="text" id="section_title" required 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Subtítulo</label>
                    <textarea id="section_subtitle" rows="3" required 
                              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300"></textarea>
                </div>
                
                <!-- Background Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Imagem de Fundo</label>
                    <div id="bgUploadArea" class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center transition duration-300 hover:border-yellow-500 cursor-pointer">
                        <i data-feather="image" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-400 mb-2">Arraste e solte uma imagem ou clique para selecionar</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, WEBP até 5MB</p>
                        <input type="file" id="bgImageInput" class="hidden" accept="image/*">
                        <input type="hidden" id="background_image">
                    </div>
                    <div id="bgImagePreview" class="mt-4 hidden">
                        <img id="bgPreviewImage" class="w-full h-48 object-cover rounded-lg shadow-lg">
                        <button type="button" onclick="removeBgImage()" class="mt-2 text-red-400 hover:text-red-300 text-sm flex items-center">
                            <i data-feather="trash" class="w-4 h-4 mr-1"></i> Remover imagem
                        </button>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
                    <button type="button" onclick="closeSectionModal()" 
                            class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition duration-300">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold rounded-lg transition duration-300 flex items-center">
                        <i data-feather="save" class="mr-2 w-4 h-4"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        feather.replace();

        // Background Image Upload
        const bgUploadArea = document.getElementById('bgUploadArea');
        const bgImageInput = document.getElementById('bgImageInput');
        const bgImagePreview = document.getElementById('bgImagePreview');
        const bgPreviewImage = document.getElementById('bgPreviewImage');
        const backgroundImage = document.getElementById('background_image');

        bgUploadArea.addEventListener('click', () => bgImageInput.click());
        
        bgUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            bgUploadArea.classList.add('drag-over');
        });

        bgUploadArea.addEventListener('dragleave', () => {
            bgUploadArea.classList.remove('drag-over');
        });

        bgUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            bgUploadArea.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                bgImageInput.files = e.dataTransfer.files;
                handleBgImageUpload(e.dataTransfer.files[0]);
            }
        });

        bgImageInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleBgImageUpload(e.target.files[0]);
            }
        });

        function handleBgImageUpload(file) {
            if (!file.type.startsWith('image/')) {
                Swal.fire('Erro', 'Por favor, selecione apenas imagens.', 'error');
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                Swal.fire('Erro', 'A imagem deve ter no máximo 5MB.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            Swal.fire({
                title: 'Fazendo upload...',
                text: 'Por favor, aguarde.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('api/upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    backgroundImage.value = data.url;
                    bgPreviewImage.src = data.url;
                    bgImagePreview.classList.remove('hidden');
                    bgUploadArea.classList.add('hidden');
                    Swal.fire('Sucesso!', 'Imagem enviada com sucesso.', 'success');
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire('Erro', 'Falha no upload da imagem.', 'error');
            });
        }

        function removeBgImage() {
            backgroundImage.value = '';
            bgImagePreview.classList.add('hidden');
            bgUploadArea.classList.remove('hidden');
            bgImageInput.value = '';
        }

        // Section functions
        function editSection(id) {
            fetch(`api/sections.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('section_id').value = data.section.id;
                        document.getElementById('section_name').value = data.section.name;
                        document.getElementById('section_title').value = data.section.title;
                        document.getElementById('section_subtitle').value = data.section.subtitle;
                        
                        if (data.section.background_image) {
                            backgroundImage.value = data.section.background_image;
                            bgPreviewImage.src = data.section.background_image;
                            bgImagePreview.classList.remove('hidden');
                            bgUploadArea.classList.add('hidden');
                        } else {
                            removeBgImage();
                        }
                        
                        document.getElementById('sectionModal').classList.remove('hidden');
                        feather.replace();
                    }
                });
        }

        function closeSectionModal() {
            document.getElementById('sectionModal').classList.add('hidden');
        }

        function toggleSection(id, active) {
            fetch('api/sections.php?action=toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, is_active: active })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', `Seção ${active ? 'ativada' : 'desativada'} com sucesso.`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            });
        }

        // Form submission
        document.getElementById('sectionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                id: document.getElementById('section_id').value,
                title: document.getElementById('section_title').value,
                subtitle: document.getElementById('section_subtitle').value,
                background_image: backgroundImage.value
            };

            fetch('api/sections.php?action=update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', data.message, 'success');
                    closeSectionModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            });
        });

        // Close modal on outside click
        document.getElementById('sectionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSectionModal();
            }
        });
    </script>
</body>
</html>