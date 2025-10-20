<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';
requireAuth();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

try {
    $db = Database::getInstance();

    switch ($action) {
        case 'get':
            $id = (int)$_GET['id'];
            $section = $db->fetch("SELECT * FROM sections WHERE id = ?", [$id]);
            if ($section) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'section' => $section]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Seção não encontrada.']);
            }
            break;

        case 'update':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = (int)$input['id'];
            $title = sanitize($input['title']);
            $subtitle = sanitize($input['subtitle']);
            $background_image = sanitize($input['background_image']);
            
            $db->query(
                "UPDATE sections SET title = ?, subtitle = ?, background_image = ? WHERE id = ?",
                [$title, $subtitle, $background_image, $id]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Seção atualizada com sucesso!']);
            break;

        case 'toggle':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = (int)$input['id'];
            $is_active = (bool)$input['is_active'];
            
            $db->query("UPDATE sections SET is_active = ? WHERE id = ?", [$is_active, $id]);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso!']);
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}
?>