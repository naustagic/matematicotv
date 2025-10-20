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
            $testimonial = $db->fetch("SELECT * FROM testimonials WHERE id = ?", [$id]);
            if ($testimonial) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'testimonial' => $testimonial]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Depoimento não encontrado.']);
            }
            break;

        case 'create':
            $input = json_decode(file_get_contents('php://input'), true);
            $client_name = sanitize($input['client_name']);
            $client_photo = sanitize($input['client_photo']);
            $rating = (int)$input['rating'];
            $testimonial_text = sanitize($input['testimonial']);
            $is_active = isset($input['is_active']) ? (bool)$input['is_active'] : true;
            
            // Get max display order
            $maxOrder = $db->fetch("SELECT MAX(display_order) as max_order FROM testimonials")['max_order'] ?? 0;
            
            $db->query(
                "INSERT INTO testimonials (client_name, client_photo, rating, testimonial, is_active, display_order) VALUES (?, ?, ?, ?, ?, ?)",
                [$client_name, $client_photo, $rating, $testimonial_text, $is_active, $maxOrder + 1]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Depoimento adicionado com sucesso!']);
            break;

        case 'update':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = (int)$input['id'];
            $client_name = sanitize($input['client_name']);
            $client_photo = sanitize($input['client_photo']);
            $rating = (int)$input['rating'];
            $testimonial_text = sanitize($input['testimonial']);
            $is_active = isset($input['is_active']) ? (bool)$input['is_active'] : true;
            
            $db->query(
                "UPDATE testimonials SET client_name = ?, client_photo = ?, rating = ?, testimonial = ?, is_active = ? WHERE id = ?",
                [$client_name, $client_photo, $rating, $testimonial_text, $is_active, $id]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Depoimento atualizado com sucesso!']);
            break;

        case 'delete':
            $id = (int)$_GET['id'];
            $db->query("DELETE FROM testimonials WHERE id = ?", [$id]);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Depoimento removido com sucesso!']);
            break;

        case 'reorder':
            $input = json_decode(file_get_contents('php://input'), true);
            $testimonials = $input['testimonials'];
            
            foreach ($testimonials as $testimonial) {
                $db->query(
                    "UPDATE testimonials SET display_order = ? WHERE id = ?",
                    [$testimonial['display_order'], $testimonial['id']]
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