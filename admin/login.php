<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php'; // ADICIONAR ESTA LINHA

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Usuário ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • Mir4Mediator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oxanium:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Oxanium', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-gray-800 rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-yellow-400">Mir4Mediator</h1>
            <p class="text-gray-400 mt-2">Painel Administrativo</p>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="bg-red-900 text-red-200 p-3 rounded-lg mb-6">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-gray-300 mb-2">Usuário</label>
                <input type="text" id="username" name="username" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
            </div>
            <div>
                <label for="password" class="block text-gray-300 mb-2">Senha</label>
                <input type="password" id="password" name="password" required 
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-white">
            </div>
            <button type="submit" 
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-6 rounded-lg transition duration-300 text-lg">
                Entrar
            </button>
        </form>
    </div>
    
    <script>
        feather.replace();
    </script>
</body>
</html>