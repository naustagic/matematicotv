<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/database.php';
requireAuth();

// Buscar todas as configurações
$settings = [];
$db = Database::getInstance();
$result = $db->fetchAll("SELECT setting_key, setting_value FROM site_settings");
foreach ($result as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações • Mir4Mediator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Oxanium', sans-serif;
        }
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
                <a href="partners.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Parceiros</a>
                <a href="settings.php" class="block px-3 py-2 bg-yellow-500 bg-opacity-10 text-yellow-400 rounded-lg">Configurações</a>
                <a href="logout.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Sair</a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Configurações do Site</h1>
            
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-white mb-6">Configurações Gerais</h2>
                
                <form id="settingsForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-300 mb-1">Nome do Site</label>
                            <input type="text" id="site_name" value="<?php echo $settings['site_name'] ?? ''; ?>" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label for="site_logo" class="block text-sm font-medium text-gray-300 mb-1">Logo (URL)</label>
                            <div class="flex items-center space-x-4">
                                <input type="url" id="site_logo" value="<?php echo $settings['site_logo'] ?? ''; ?>" class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                                <button type="button" onclick="openUploadModal('site_logo')" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-300 mb-1">Cor Primária</label>
                            <div class="flex items-center space-x-4">
                                <input type="color" id="primary_color" value="<?php echo $settings['primary_color'] ?? '#f5b915'; ?>" class="w-16 h-10 bg-gray-700 border border-gray-600 rounded-lg cursor-pointer">
                                <input type="text" id="primary_color_text" value="<?php echo $settings['primary_color'] ?? '#f5b915'; ?>" class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            </div>
                        </div>
                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-300 mb-1">Cor Secundária</label>
                            <div class="flex items-center space-x-4">
                                <input type="color" id="secondary_color" value="<?php echo $settings['secondary_color'] ?? '#2a2e35'; ?>" class="w-16 h-10 bg-gray-700 border border-gray-600 rounded-lg cursor-pointer">
                                <input type="text" id="secondary_color_text" value="<?php echo $settings['secondary_color'] ?? '#2a2e35'; ?>" class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            </div>
                        </div>
                        <div>
                            <label for="accent_color" class="block text-sm font-medium text-gray-300 mb-1">Cor de Destaque</label>
                            <div class="flex items-center space-x-4">
                                <input type="color" id="accent_color" value="<?php echo $settings['accent_color'] ?? '#e74c3c'; ?>" class="w-16 h-10 bg-gray-700 border border-gray-600 rounded-lg cursor-pointer">
                                <input type="text" id="accent_color_text" value="<?php echo $settings['accent_color'] ?? '#e74c3c'; ?>" class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-300 mb-1">Email de Contato</label>
                            <input type="email" id="contact_email" value="<?php echo $settings['contact_email'] ?? ''; ?>" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-300 mb-1">Telefone</label>
                            <input type="text" id="contact_phone" value="<?php echo $settings['contact_phone'] ?? ''; ?>" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label for="contact_discord" class="block text-sm font-medium text-gray-300 mb-1">Discord</label>
                            <input type="text" id="contact_discord" value="<?php echo $settings['contact_discord'] ?? ''; ?>" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-6 rounded-lg transition duration-300">
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de upload -->
    <div id="uploadModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-75">
        <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full relative">
            <button onclick="closeUploadModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <i data-feather="x"></i>
            </button>
            <h3 class="text-2xl font-bold text-yellow-400 mb-6">Upload de Imagem</h3>
            <div class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center mb-4">
                <i data-feather="upload" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-400 mb-2">Arraste e solte uma imagem ou clique para selecionar</p>
                <input type="file" id="fileInput" class="hidden" accept="image/*">
                <button onclick="document.getElementById('fileInput').click()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300">
                    Selecionar Arquivo
                </button>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeUploadModal()" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                    Cancelar
                </button>
                <button type="button" onclick="uploadImage()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-6 rounded-lg transition duration-300">
                    Fazer Upload
                </button>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
        let currentImageField = '';

        // Sincronizar inputs de cor
        document.getElementById('primary_color').addEventListener('input', function() {
            document.getElementById('primary_color_text').value = this.value;
        });
        document.getElementById('primary_color_text').addEventListener('input', function() {
            document.getElementById('primary_color').value = this.value;
        });

        document.getElementById('secondary_color').addEventListener('input', function() {
            document.getElementById('secondary_color_text').value = this.value;
        });
        document.getElementById('secondary_color_text').addEventListener('input', function() {
            document.getElementById('secondary_color').value = this.value;
        });

        document.getElementById('accent_color').addEventListener('input', function() {
            document.getElementById('accent_color_text').value = this.value;
        });
        document.getElementById('accent_color_text').addEventListener('input', function() {
            document.getElementById('accent_color').value = this.value;
        });

        // Formulário de configurações
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                site_name: document.getElementById('site_name').value,
                site_logo: document.getElementById('site_logo').value,
                primary_color: document.getElementById('primary_color').value,
                secondary_color: document.getElementById('secondary_color').value,
                accent_color: document.getElementById('accent_color').value,
                contact_email: document.getElementById('contact_email').value,
                contact_phone: document.getElementById('contact_phone').value,
                contact_discord: document.getElementById('contact_discord').value
            };

            fetch('api/settings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Configurações salvas!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message
                    });
                }
            });
        });

        function openUploadModal(field) {
            currentImageField = field;
            document.getElementById('uploadModal').classList.remove('hidden');
            feather.replace();
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }

        function uploadImage() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            
            if (!file) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Selecione um arquivo!'
                });
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            fetch('api/upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(currentImageField).value = data.url;
                    closeUploadModal();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Upload realizado!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message
                    });
                }
            });
        }

        // Drag and drop para upload
        const uploadArea = document.querySelector('#uploadModal .border-dashed');
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-yellow-500', 'bg-yellow-500', 'bg-opacity-10');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-yellow-500', 'bg-yellow-500', 'bg-opacity-10');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-yellow-500', 'bg-yellow-500', 'bg-opacity-10');
            const fileInput = document.getElementById('fileInput');
            fileInput.files = e.dataTransfer.files;
        });
    </script>
</body>
</html>