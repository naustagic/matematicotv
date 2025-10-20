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
            $faq = $db->fetch("SELECT * FROM faqs WHERE id = ?", [$id]);
            if ($faq) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'faq' => $faq]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'FAQ não encontrada.']);
            }
            break;

        case 'create':
            $input = json_decode(file_get_contents('php://input'), true);
            $question = sanitize($input['question']);
            $answer = sanitize($input['answer']);
            $is_active = isset($input['is_active']) ? (bool)$input['is_active'] : true;
            
            // Get max display order
            $maxOrder = $db->fetch("SELECT MAX(display_order) as max_order FROM faqs")['max_order'] ?? 0;
            
            $db->query(
                "INSERT INTO faqs (question, answer, is_active, display_order) VALUES (?, ?, ?, ?)",
                [$question, $answer, $is_active, $maxOrder + 1]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Pergunta adicionada com sucesso!']);
            break;

        case 'update':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = (int)$input['id'];
            $question = sanitize($input['question']);
            $answer = sanitize($input['answer']);
            $is_active = isset($input['is_active']) ? (bool)$input['is_active'] : true;
            
            $db->query(
                "UPDATE faqs SET question = ?, answer = ?, is_active = ? WHERE id = ?",
                [$question, $answer, $is_active, $id]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Pergunta atualizada com sucesso!']);
            break;

        case 'delete':
            $id = (int)$_GET['id'];
            $db->query("DELETE FROM faqs WHERE id = ?", [$id]);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Pergunta removida com sucesso!']);
            break;

        case 'toggle':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = (int)$input['id'];
            $is_active = (bool)$input['is_active'];
            
            $db->query("UPDATE faqs SET is_active = ? WHERE id = ?", [$is_active, $id]);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso!']);
            break;

        case 'reorder':
            $input = json_decode(file_get_contents('php://input'), true);
            $faqs = $input['faqs'];
            
            foreach ($faqs as $faq) {
                $db->query(
                    "UPDATE faqs SET display_order = ? WHERE id = ?",
                    [$faq['display_order'], $faq['id']]
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