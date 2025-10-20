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
            $partner = $db->fetch("SELECT * FROM partners WHERE id = ?", [$id]);
            if ($partner) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'partner' => $partner]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Parceiro não encontrado.']);
            }
            break;

        case 'create':
            $input = json_decode(file_get_contents('php://input'), true);
            $name = sanitize($input['name']);
            $website_url = sanitize($input['website_url']);
            $logo = sanitize($input['logo']);
            $is_active = isset($input['is_active']) ? (bool)$input['is_active'] : true;
            
            // Get max display order
            $maxOrder = $db->fetch("SELECT MAX(display_order) as max_order FROM partners")['max_order'] ?? 0;
            
            $db->query(
                "INSERT INTO partners (name, website_url, logo, is_active, display_order) VALUES (?, ?, ?, ?, ?)",
                [$name, $website_url, $logo, $is_active, $maxOrder + 1]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Parceiro adicionado com sucesso!']);
            break;

        case 'update':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = (int)$input['id'];
            $name = sanitize($input['name']);
            $website_url = sanitize($input['website_url']);
            $logo = sanitize($input['logo']);
            $is_active = isset($input['is_active']) ? (bool)$input['is_active'] : true;
            
            $db->query(
                "UPDATE partners SET name = ?, website_url = ?, logo = ?, is_active = ? WHERE id = ?",
                [$name, $website_url, $logo, $is_active, $id]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Parceiro atualizado com sucesso!']);
            break;

        case 'delete':
            $id = (int)$_GET['id'];
            $db->query("DELETE FROM partners WHERE id = ?", [$id]);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Parceiro removido com sucesso!']);
            break;

        case 'reorder':
            $input = json_decode(file_get_contents('php://input'), true);
            $partners = $input['partners'];
            
            foreach ($partners as $partner) {
                $db->query(
                    "UPDATE partners SET display_order = ? WHERE id = ?",
                    [$partner['display_order'], $partner['id']]
                );
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
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