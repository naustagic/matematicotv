<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';
requireAuth();

$db = Database::getInstance();
$testimonials = $db->fetchAll("SELECT * FROM testimonials ORDER BY display_order");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depoimentos • Mir4Mediator</title>
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
                <a href="testimonials.php" class="block px-3 py-2 bg-yellow-500 bg-opacity-10 text-yellow-400 rounded-lg">Depoimentos</a>
                <a href="partners.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Parceiros</a>
                <a href="settings.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Configurações</a>
                <a href="logout.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Sair</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Depoimentos</h1>
                <button onclick="openAddModal()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                    <i data-feather="plus" class="mr-2 w-4 h-4"></i> Novo Depoimento
                </button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count($testimonials); ?></h3>
                        </div>
                        <div class="text-blue-400 bg-blue-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="message-square"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">5 Estrelas</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($testimonials, fn($t) => $t['rating'] == 5)); ?></h3>
                        </div>
                        <div class="text-yellow-400 bg-yellow-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="star"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Ativos</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($testimonials, fn($t) => $t['is_active'])); ?></h3>
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
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo count(array_filter($testimonials, fn($t) => !$t['is_active'])); ?></h3>
                        </div>
                        <div class="text-red-400 bg-red-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="testimonials-list">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="bg-gray-800 rounded-lg p-6" data-id="<?php echo $testimonial['id']; ?>">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-3 flex-1">
                            <img src="<?php echo $testimonial['client_photo'] ?: 'https://via.placeholder.com/48/6b7280/1f2937?text=?'; ?>" 
                                 alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>" 
                                 class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h3 class="font-semibold text-white"><?php echo htmlspecialchars($testimonial['client_name']); ?></h3>
                                <div class="flex text-yellow-400 mt-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i data-feather="star" class="w-4 h-4 <?php echo $i <= $testimonial['rating'] ? 'fill-current' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <button onclick="editTestimonial(<?php echo $testimonial['id']; ?>)" 
                                    class="text-gray-400 hover:text-yellow-400 p-1 rounded-lg hover:bg-gray-700 transition duration-300">
                                <i data-feather="edit" class="w-4 h-4"></i>
                            </button>
                            <button onclick="deleteTestimonial(<?php echo $testimonial['id']; ?>)" 
                                    class="text-gray-400 hover:text-red-400 p-1 rounded-lg hover:bg-gray-700 transition duration-300">
                                <i data-feather="trash" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    <p class="text-gray-300 text-sm mb-4 italic">"<?php echo htmlspecialchars($testimonial['testimonial']); ?>"</p>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="px-2 py-1 rounded-full <?php echo $testimonial['is_active'] ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300'; ?>">
                            <?php echo $testimonial['is_active'] ? 'Ativo' : 'Inativo'; ?>
                        </span>
                        <span class="text-gray-400">Ordem: <?php echo $testimonial['display_order']; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($testimonials)): ?>
            <div class="text-center py-12">
                <i data-feather="message-square" class="w-16 h-16 text-gray-600 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-400">Nenhum depoimento cadastrado</h3>
                <p class="text-gray-500 mt-2">Adicione depoimentos de clientes satisfeitos.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="testimonialModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75 p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-yellow-400" id="modalTitle">Novo Depoimento</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white transition duration-300">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            
            <form id="testimonialForm" class="p-6 space-y-6">
                <input type="hidden" id="testimonial_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-300 mb-2">Nome do Cliente</label>
                        <input type="text" id="client_name" required 
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                    </div>
                    
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-300 mb-2">Avaliação</label>
                        <select id="rating" required 
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300">
                            <option value="5">★★★★★ (5 estrelas)</option>
                            <option value="4">★★★★☆ (4 estrelas)</option>
                            <option value="3">★★★☆☆ (3 estrelas)</option>
                            <option value="2">★★☆☆☆ (2 estrelas)</option>
                            <option value="1">★☆☆☆☆ (1 estrela)</option>
                        </select>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Foto do Cliente</label>
                    <div id="photoUploadArea" class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center transition duration-300 hover:border-yellow-500 cursor-pointer">
                        <i data-feather="user" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-400 mb-2">Arraste e solte uma foto ou clique para selecionar</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, WEBP até 5MB</p>
                        <input type="file" id="photoInput" class="hidden" accept="image/*">
                        <input type="hidden" id="client_photo">
                    </div>
                    <div id="photoPreview" class="mt-4 hidden">
                        <img id="previewPhoto" class="w-24 h-24 rounded-full object-cover mx-auto shadow-lg">
                        <button type="button" onclick="removePhoto()" class="mt-2 text-red-400 hover:text-red-300 text-sm flex items-center justify-center">
                            <i data-feather="trash" class="w-4 h-4 mr-1"></i> Remover foto
                        </button>
                    </div>
                </div>
                
                <div>
                    <label for="testimonial" class="block text-sm font-medium text-gray-300 mb-2">Depoimento</label>
                    <textarea id="testimonial" rows="4" required 
                              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition duration-300"
                              placeholder="Digite o depoimento do cliente..."></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is_active" class="w-4 h-4 text-yellow-500 bg-gray-700 border-gray-600 rounded focus:ring-yellow-500 focus:ring-2">
                    <label for="is_active" class="ml-2 text-sm text-gray-300">Depoimento ativo</label>
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
        let currentTestimonialId = null;

        // Photo Upload
        const photoUploadArea = document.getElementById('photoUploadArea');
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const previewPhoto = document.getElementById('previewPhoto');
        const clientPhoto = document.getElementById('client_photo');

        photoUploadArea.addEventListener('click', () => photoInput.click());
        
        photoUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            photoUploadArea.classList.add('drag-over');
        });

        photoUploadArea.addEventListener('dragleave', () => {
            photoUploadArea.classList.remove('drag-over');
        });

        photoUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            photoUploadArea.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                photoInput.files = e.dataTransfer.files;
                handlePhotoUpload(e.dataTransfer.files[0]);
            }
        });

        photoInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handlePhotoUpload(e.target.files[0]);
            }
        });

        function handlePhotoUpload(file) {
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
                    clientPhoto.value = data.url;
                    previewPhoto.src = data.url;
                    photoPreview.classList.remove('hidden');
                    photoUploadArea.classList.add('hidden');
                    Swal.fire('Sucesso!', 'Foto enviada com sucesso.', 'success');
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire('Erro', 'Falha no upload da foto.', 'error');
            });
        }

        function removePhoto() {
            clientPhoto.value = '';
            photoPreview.classList.add('hidden');
            photoUploadArea.classList.remove('hidden');
            photoInput.value = '';
        }

        // Initialize sortable
        new Sortable(document.getElementById('testimonials-list'), {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const testimonials = Array.from(document.getElementById('testimonials-list').children).map((item, index) => {
                    return {
                        id: item.dataset.id,
                        display_order: index
                    };
                });
                
                fetch('api/testimonials.php?action=reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ testimonials: testimonials })
                });
            }
        });

        function openAddModal() {
            currentTestimonialId = null;
            document.getElementById('modalTitle').textContent = 'Novo Depoimento';
            document.getElementById('testimonialForm').reset();
            document.getElementById('testimonial_id').value = '';
            document.getElementById('rating').value = '5';
            document.getElementById('is_active').checked = true;
            removePhoto();
            document.getElementById('testimonialModal').classList.remove('hidden');
            feather.replace();
        }

        function closeModal() {
            document.getElementById('testimonialModal').classList.add('hidden');
        }

        function editTestimonial(id) {
            fetch(`api/testimonials.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentTestimonialId = id;
                        document.getElementById('modalTitle').textContent = 'Editar Depoimento';
                        document.getElementById('testimonial_id').value = data.testimonial.id;
                        document.getElementById('client_name').value = data.testimonial.client_name;
                        document.getElementById('rating').value = data.testimonial.rating;
                        document.getElementById('testimonial').value = data.testimonial.testimonial;
                        document.getElementById('is_active').checked = data.testimonial.is_active;
                        
                        if (data.testimonial.client_photo) {
                            clientPhoto.value = data.testimonial.client_photo;
                            previewPhoto.src = data.testimonial.client_photo;
                            photoPreview.classList.remove('hidden');
                            photoUploadArea.classList.add('hidden');
                        } else {
                            removePhoto();
                        }
                        
                        document.getElementById('testimonialModal').classList.remove('hidden');
                        feather.replace();
                    }
                });
        }

        function deleteTestimonial(id) {
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
                    fetch(`api/testimonials.php?action=delete&id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deletado!', 'Depoimento removido com sucesso.', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire('Erro', data.message, 'error');
                        }
                    });
                }
            });
        }

        // Form submission
        document.getElementById('testimonialForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                client_name: document.getElementById('client_name').value,
                rating: parseInt(document.getElementById('rating').value),
                testimonial: document.getElementById('testimonial').value,
                client_photo: clientPhoto.value,
                is_active: document.getElementById('is_active').checked
            };

            if (currentTestimonialId) {
                formData.id = currentTestimonialId;
            }

            const url = currentTestimonialId ? 'api/testimonials.php?action=update' : 'api/testimonials.php?action=create';
            const method = currentTestimonialId ? 'PUT' : 'POST';

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
        document.getElementById('testimonialModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>