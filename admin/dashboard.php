<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
requireAuth();

// Estatísticas
$db = Database::getInstance();
$accounts_count = $db->fetch("SELECT COUNT(*) as count FROM accounts")['count'];
$services_count = $db->fetch("SELECT COUNT(*) as count FROM services")['count'];
$faqs_count = $db->fetch("SELECT COUNT(*) as count FROM faqs")['count'];
$testimonials_count = $db->fetch("SELECT COUNT(*) as count FROM testimonials")['count'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard • Mir4Mediator</title>
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
                <a href="dashboard.php" class="block px-3 py-2 bg-yellow-500 bg-opacity-10 text-yellow-400 rounded-lg">Dashboard</a>
                <a href="sections.php" class="block px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg">Seções</a>
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
            <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Contas Cadastradas</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo $accounts_count; ?></h3>
                        </div>
                        <div class="text-yellow-400 bg-yellow-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="users"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Serviços Ativos</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo $services_count; ?></h3>
                        </div>
                        <div class="text-blue-400 bg-blue-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="tool"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Perguntas FAQ</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo $faqs_count; ?></h3>
                        </div>
                        <div class="text-green-400 bg-green-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="help-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400">Depoimentos</p>
                            <h3 class="text-2xl font-bold text-white mt-1"><?php echo $testimonials_count; ?></h3>
                        </div>
                        <div class="text-purple-400 bg-purple-400 bg-opacity-10 p-3 rounded-full">
                            <i data-feather="message-square"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Atividade Recente</h2>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-700 rounded-lg">
                        <div class="text-yellow-400 mr-3">
                            <i data-feather="edit"></i>
                        </div>
                        <div>
                            <p class="text-white">Sistema inicializado com sucesso</p>
                            <p class="text-gray-400 text-sm">Agora mesmo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        feather.replace();
    </script>
</body>
</html>