<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';
requireAuth();

header('Content-Type: application/json');

// Criar diretório de uploads se não existir
if (!file_exists('../../assets/uploads')) {
    mkdir('../../assets/uploads', 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Nenhuma imagem enviada.']);
            exit;
        }

        $file = $_FILES['image'];
        
        // Validar tipo de arquivo
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed_types)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Tipo de arquivo não permitido. Use apenas JPEG, PNG, GIF ou WEBP.']);
            exit;
        }

        // Validar tamanho (5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Arquivo muito grande. Tamanho máximo: 5MB.']);
            exit;
        }

        // Gerar nome único para o arquivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $upload_path = '../../assets/uploads/' . $filename;

        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $url = '/assets/uploads/' . $filename;
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'url' => $url,
                'message' => 'Upload realizado com sucesso!'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erro ao mover arquivo.']);
        }
        
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>