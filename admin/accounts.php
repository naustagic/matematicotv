<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';
requireAuth();

// Buscar todas as contas
$db = Database::getInstance();
$accounts = $db->fetchAll("SELECT * FROM accounts ORDER BY display_order");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas • Mir4Mediator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Oxanium', sans-serif;
        }
        .drag-over {
            border-color: #f5b915;
            background-color: rgba(245, 185, 21, 0.1);
        }
        .image-preview {
            transition: all 0.3s ease;
        }
        .status-available { border-left: 4px solid #10b981; }
        .status-sold { border-left: 4px solid #ef4444; }
        .status-premium { border-left: 4px solid #f59e0b; }
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
                <a href="accounts.php" class="block px-3 py-2 bg-yellow-500 bg-opacity-10 text-yellow-400 rounded-lg">Contas</a>
                <a href="faqs.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">FAQ</a>
                <a href="testimonials.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Depoimentos</a>
                <a href="partners.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Parceiros</a>
                <a href="settings.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Configurações</a>
                <a href="logout.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Sair</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Gerenciar Contas</h1>
                <button onclick="openAddModal()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                    <i data-feather="plus" class="mr-2 w-4 h-4"></i> Nova Conta
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count($accounts); ?></h3>
                        </div>
                        <div class="text-blue-400 bg-blue-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="users"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Disponíveis</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($accounts, fn($a) => $a['status'] === 'available')); ?></h3>
                        </div>
                        <div class="text-green-400 bg-green-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="shopping-cart"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Vendidas</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($accounts, fn($a) => $a['status'] === 'sold')); ?></h3>
                        </div>
                        <div class="text-red-400 bg-red-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="dollar-sign"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Premium</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($accounts, fn($a) => $a['status'] === 'premium')); ?></h3>
                        </div>
                        <div class="text-yellow-400 bg-yellow-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($accounts as $account): ?>
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg status-<?php echo $account['status']; ?>">
                    <div class="relative group">
                        <img src="<?php echo $account['image'] ?: 'https://via.placeholder.com/400x300/1f2937/6b7280?text=Sem+Imagem'; ?>" 
                             alt="<?php echo htmlspecialchars($account['title']); ?>" 
                             class="w-full h-48 object-cover transition duration-300 group-hover:scale-105">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php echo $account['status'] === 'available' ? 'bg-green-900 text-green-300' : 
                                      ($account['status'] === 'sold' ? 'bg-red-900 text-red-300' : 'bg-yellow-900 text-yellow-300'); ?>">
                                <?php echo $account['status'] === 'available' ? 'Disponível' : 
                                      ($account['status'] === 'sold' ? 'Vendida' : 'Premium'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white mb-2"><?php echo htmlspecialchars($account['title']); ?></h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">PS:</span>
                                <span class="text-yellow-400 font-semibold"><?php echo htmlspecialchars($account['power_score']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Nível:</span>
                                <span class="text-white"><?php echo htmlspecialchars($account['level']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Preço:</span>
                                <span class="text-green-400 font-bold">R$ <?php echo number_format($account['price'], 2, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 pb-4 flex justify-between items-center">
                        <button onclick="editAccount(<?php echo $account['id']; ?>)" 
                                class="text-gray-400 hover:text-yellow-400 transition duration-300 p-2 rounded-lg hover:bg-gray-700">
                            <i data-feather="edit" class="w-4 h-4"></i>
                        </button>
                        <button onclick="deleteAccount(<?php echo $account['id']; ?>)" 
                                class="text-gray-400 hover:text-red-400 transition duration-300 p-2 rounded-lg hover:bg-gray-700">
                            <i data-feather="trash" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($accounts)): ?>
            <div class="text-center py-12">
                <i data-feather="package" class="w-16 h-16 text-gray-600 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-400">Nenhuma conta cadastrada</h3>
                <p class="text-gray-500 mt-2">Comece adicionando sua primeira conta à galeria.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="accountModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75 p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-yellow-400" id="modalTitle">Adicionar Conta</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white transition duration-300">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            
            <form id="accountForm" class="p-6 space-y-6">
                <input type="hidden" id="account_id">
                
                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Imagem da Conta</label>
                    <div id="uploadArea" class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center transition duration-300 hover:border-yellow-500 cursor-pointer">
                        <i data-feather="upload" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-400 mb-2">Arraste e solte uma imagem ou clique para selecionar</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, WEBP até 5MB</p>
                        <input type="file" id="imageInput" class="hidden" accept="image/*">
                        <input type="hidden" id="image_url">
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImage" class="w-full h-48 object-cover rounded-lg shadow-lg">
                        <button type="button" onclick="removeImage()" class="mt-2 text-red-400 hover:text-red-300 text-sm flex items-center">
                            <i data-feather="trash" class="w-4 h-4 mr-1"></i> Remover imagem
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Título</label>
                        <input type="text" id="title" required 
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                    </div>
                    
                    <div>
                        <label for="power_score" class="block text-sm font-medium text-gray-300 mb-2">Poder (PS)</label>
                        <input type="text" id="power_score" required 
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-300 mb-2">Nível</label>
                        <input type="text" id="level" required 
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-300 mb-2">Preço (R$)</label>
                        <input type="number" step="0.01" id="price" required 
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select id="status" required 
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                        <option value="available">Disponível</option>
                        <option value="sold">Vendida</option>
                        <option value="premium">Premium</option>
                    </select>
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

    <script>
        feather.replace();
        let currentAccountId = null;

        // Upload functionality
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        const imageUrl = document.getElementById('image_url');

        uploadArea.addEventListener('click', () => imageInput.click());
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                imageInput.files = e.dataTransfer.files;
                handleImageUpload(e.dataTransfer.files[0]);
            }
        });

        imageInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleImageUpload(e.target.files[0]);
            }
        });

        function handleImageUpload(file) {
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

            // Show loading
            Swal.fire({
                title: 'Fazendo upload...',
                text: 'Por favor, aguarde.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('api/upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    imageUrl.value = data.url;
                    previewImage.src = data.url;
                    imagePreview.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
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

        function removeImage() {
            imageUrl.value = '';
            imagePreview.classList.add('hidden');
            uploadArea.classList.remove('hidden');
            imageInput.value = '';
        }

        // Modal functions
        function openAddModal() {
            currentAccountId = null;
            document.getElementById('modalTitle').textContent = 'Adicionar Conta';
            document.getElementById('accountForm').reset();
            document.getElementById('account_id').value = '';
            removeImage();
            document.getElementById('accountModal').classList.remove('hidden');
            feather.replace();
        }

        function closeModal() {
            document.getElementById('accountModal').classList.add('hidden');
        }

        function editAccount(id) {
            fetch(`api/accounts.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentAccountId = id;
                        document.getElementById('modalTitle').textContent = 'Editar Conta';
                        document.getElementById('account_id').value = data.account.id;
                        document.getElementById('title').value = data.account.title;
                        document.getElementById('power_score').value = data.account.power_score;
                        document.getElementById('level').value = data.account.level;
                        document.getElementById('price').value = data.account.price;
                        document.getElementById('status').value = data.account.status;
                        
                        if (data.account.image) {
                            imageUrl.value = data.account.image;
                            previewImage.src = data.account.image;
                            imagePreview.classList.remove('hidden');
                            uploadArea.classList.add('hidden');
                        } else {
                            removeImage();
                        }
                        
                        document.getElementById('accountModal').classList.remove('hidden');
                        feather.replace();
                    }
                });
        }

        function deleteAccount(id) {
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
                    fetch(`api/accounts.php?action=delete&id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deletado!', 'Conta removida com sucesso.', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire('Erro', data.message, 'error');
                        }
                    });
                }
            });
        }

        // Form submission
        document.getElementById('accountForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                title: document.getElementById('title').value,
                power_score: document.getElementById('power_score').value,
                level: document.getElementById('level').value,
                price: parseFloat(document.getElementById('price').value),
                status: document.getElementById('status').value,
                image: imageUrl.value
            };

            if (currentAccountId) {
                formData.id = currentAccountId;
            }

            const url = currentAccountId ? 'api/accounts.php?action=update' : 'api/accounts.php?action=create';
            const method = currentAccountId ? 'PUT' : 'POST';

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
            })
            .catch(error => {
                Swal.fire('Erro', 'Falha ao salvar conta.', 'error');
            });
        });

        // Close modal on outside click
        document.getElementById('accountModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>