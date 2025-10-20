<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';
requireAuth();

$db = Database::getInstance();
$partners = $db->fetchAll("SELECT * FROM partners ORDER BY display_order");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parceiros • Mir4Mediator</title>
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
                <a href="sections.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Seções</a>
                <a href="accounts.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Contas</a>
                <a href="faqs.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">FAQ</a>
                <a href="testimonials.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Depoimentos</a>
                <a href="partners.php" class="block px-3 py-2 bg-yellow-500 bg-opacity-10 text-yellow-400 rounded-lg">Parceiros</a>
                <a href="settings.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Configurações</a>
                <a href="logout.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Sair</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Parceiros</h1>
                <button onclick="openAddModal()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                    <i data-feather="plus" class="mr-2 w-4 h-4"></i> Novo Parceiro
                </button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total de Parceiros</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count($partners); ?></h3>
                        </div>
                        <div class="text-blue-400 bg-blue-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="users"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Ativos</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($partners, fn($p) => $p['is_active'])); ?></h3>
                        </div>
                        <div class="text-green-400 bg-green-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="check-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Inativos</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($partners, fn($p) => !$p['is_active'])); ?></h3>
                        </div>
                        <div class="text-red-400 bg-red-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partners Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="partners-list">
                <?php foreach ($partners as $partner): ?>
                <div class="bg-gray-800 rounded-lg p-6 text-center" data-id="<?php echo $partner['id']; ?>">
                    <div class="flex justify-center mb-4">
                        <img src="<?php echo $partner['logo'] ?: 'https://via.placeholder.com/80/6b7280/1f2937?text=LOGO'; ?>" 
                             alt="<?php echo htmlspecialchars($partner['name']); ?>" 
                             class="h-20 w-20 object-contain rounded-lg">
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2"><?php echo htmlspecialchars($partner['name']); ?></h3>
                    <?php if ($partner['website_url']): ?>
                    <a href="<?php echo htmlspecialchars($partner['website_url']); ?>" 
                       target="_blank" 
                       class="text-yellow-400 hover:text-yellow-300 text-sm block mb-3 truncate">
                        <?php echo htmlspecialchars($partner['website_url']); ?>
                    </a>
                    <?php endif; ?>
                    <div class="flex justify-center space-x-2">
                        <button onclick="editPartner(<?php echo $partner['id']; ?>)" 
                                class="text-gray-400 hover:text-yellow-400 p-2 rounded-lg hover:bg-gray-700 transition duration-300">
                            <i data-feather="edit" class="w-4 h-4"></i>
                        </button>
                        <button onclick="deletePartner(<?php echo $partner['id']; ?>)" 
                                class="text-gray-400 hover:text-red-400 p-2 rounded-lg hover:bg-gray-700 transition duration-300">
                            <i data-feather="trash" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="mt-3">
                        <span class="px-2 py-1 text-xs rounded-full <?php echo $partner['is_active'] ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'; ?>">
                            <?php echo $partner['is_active'] ? 'Ativo' : 'Inativo'; ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($partners)): ?>
            <div class="text-center py-12">
                <i data-feather="users" class="w-16 h-16 text-gray-600 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-400">Nenhum parceiro cadastrado</h3>
                <p class="text-gray-500 mt-2">Adicione parceiros para mostrar no seu site.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="partnerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75 p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-yellow-400" id="modalTitle">Novo Parceiro</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white transition duration-300">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            
            <form id="partnerForm" class="p-6 space-y-6">
                <input type="hidden" id="partner_id">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nome do Parceiro</label>
                    <input type="text" id="name" required 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                </div>

                <div>
                    <label for="website_url" class="block text-sm font-medium text-gray-300 mb-2">Website (URL)</label>
                    <input type="url" id="website_url" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300"
                           placeholder="https://exemplo.com">
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Logo do Parceiro</label>
                    <div id="logoUploadArea" class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center transition duration-300 hover:border-yellow-500 cursor-pointer">
                        <i data-feather="image" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-400 mb-2">Arraste e solte um logo ou clique para selecionar</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, WEBP até 5MB</p>
                        <input type="file" id="logoInput" class="hidden" accept="image/*">
                        <input type="hidden" id="logo">
                    </div>
                    <div id="logoPreview" class="mt-4 hidden">
                        <img id="previewLogo" class="w-32 h-32 object-contain mx-auto shadow-lg rounded-lg">
                        <button type="button" onclick="removeLogo()" class="mt-2 text-red-400 hover:text-red-300 text-sm flex items-center justify-center">
                            <i data-feather="trash" class="w-4 h-4 mr-1"></i> Remover logo
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is_active" class="w-4 h-4 text-yellow-500 bg-gray-700 border-gray-600 rounded focus:ring-yellow-500 focus:ring-2">
                    <label for="is_active" class="ml-2 text-sm text-gray-300">Parceiro ativo</label>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-700">
                    <button type="button" onclick="closeModal()" 
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
        let currentPartnerId = null;

        // Logo Upload
        const logoUploadArea = document.getElementById('logoUploadArea');
        const logoInput = document.getElementById('logoInput');
        const logoPreview = document.getElementById('logoPreview');
        const previewLogo = document.getElementById('previewLogo');
        const logo = document.getElementById('logo');

        logoUploadArea.addEventListener('click', () => logoInput.click());
        
        logoUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            logoUploadArea.classList.add('drag-over');
        });

        logoUploadArea.addEventListener('dragleave', () => {
            logoUploadArea.classList.remove('drag-over');
        });

        logoUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            logoUploadArea.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                logoInput.files = e.dataTransfer.files;
                handleLogoUpload(e.dataTransfer.files[0]);
            }
        });

        logoInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleLogoUpload(e.target.files[0]);
            }
        });

        function handleLogoUpload(file) {
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
                    logo.value = data.url;
                    previewLogo.src = data.url;
                    logoPreview.classList.remove('hidden');
                    logoUploadArea.classList.add('hidden');
                    Swal.fire('Sucesso!', 'Logo enviado com sucesso.', 'success');
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire('Erro', 'Falha no upload do logo.', 'error');
            });
        }

        function removeLogo() {
            logo.value = '';
            logoPreview.classList.add('hidden');
            logoUploadArea.classList.remove('hidden');
            logoInput.value = '';
        }

        // Initialize sortable
        new Sortable(document.getElementById('partners-list'), {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const partners = Array.from(document.getElementById('partners-list').children).map((item, index) => {
                    return {
                        id: item.dataset.id,
                        display_order: index
                    };
                });
                
                fetch('api/partners.php?action=reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ partners: partners })
                });
            }
        });

        function openAddModal() {
            currentPartnerId = null;
            document.getElementById('modalTitle').textContent = 'Novo Parceiro';
            document.getElementById('partnerForm').reset();
            document.getElementById('partner_id').value = '';
            document.getElementById('is_active').checked = true;
            removeLogo();
            document.getElementById('partnerModal').classList.remove('hidden');
            feather.replace();
        }

        function closeModal() {
            document.getElementById('partnerModal').classList.add('hidden');
        }

        function editPartner(id) {
            fetch(`api/partners.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentPartnerId = id;
                        document.getElementById('modalTitle').textContent = 'Editar Parceiro';
                        document.getElementById('partner_id').value = data.partner.id;
                        document.getElementById('name').value = data.partner.name;
                        document.getElementById('website_url').value = data.partner.website_url;
                        document.getElementById('is_active').checked = data.partner.is_active;
                        
                        if (data.partner.logo) {
                            logo.value = data.partner.logo;
                            previewLogo.src = data.partner.logo;
                            logoPreview.classList.remove('hidden');
                            logoUploadArea.classList.add('hidden');
                        } else {
                            removeLogo();
                        }
                        
                        document.getElementById('partnerModal').classList.remove('hidden');
                        feather.replace();
                    }
                });
        }

        function deletePartner(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não pode ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f5b915',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`api/partners.php?action=delete&id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deletado!', 'Parceiro removido com sucesso.', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire('Erro', data.message, 'error');
                        }
                    });
                }
            });
        }

        // Form submission
        document.getElementById('partnerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('name').value,
                website_url: document.getElementById('website_url').value,
                logo: logo.value,
                is_active: document.getElementById('is_active').checked
            };

            if (currentPartnerId) {
                formData.id = currentPartnerId;
            }

            const url = currentPartnerId ? 'api/partners.php?action=update' : 'api/partners.php?action=create';
            const method = currentPartnerId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sucesso!', data.message, 'success');
                    closeModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            });
        });

        // Close modal on outside click
        document.getElementById('partnerModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>