<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'mir4mediator');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações do site
define('SITE_URL', 'http://localhost:8000');
define('SITE_NAME', 'Mir4Mediator');

// Configurações de upload
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Iniciar sessão
session_start();

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Exibir erros (remover em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>