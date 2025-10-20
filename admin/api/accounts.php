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
            $account = $db->fetch("SELECT * FROM accounts WHERE id = ?", [$id]);
            if ($account) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'account' => $account]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Conta não encontrada.']);
            }
            break;

        case 'create':
            $input = json_decode(file_get_contents('php://input'), true);
            $image = sanitize($input['image']);
            $title = sanitize($input['title']);
            $power_score = sanitize($input['power_score']);
            $level = sanitize($input['level']);
            $price = (float)$input['price'];
            $status = sanitize($input['status']);
            
            // Get max display order
            $maxOrder = $db->fetch("SELECT MAX(display_order) as max_order FROM accounts")['max_order'] ?? 0;
            
            $db->query(
                "INSERT INTO accounts (image, title, power_score, level, price, status, display_order) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$image, $title, $power_score, $level, $price, $status, $maxOrder + 1]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Conta adicionada com sucesso!']);
            break;

        case 'update':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = (int)$input['id'];
            $image = sanitize($input['image']);
            $title = sanitize($input['title']);
            $power_score = sanitize($input['power_score']);
            $level = sanitize($input['level']);
            $price = (float)$input['price'];
            $status = sanitize($input['status']);
            
            $db->query(
                "UPDATE accounts SET image = ?, title = ?, power_score = ?, level = ?, price = ?, status = ? WHERE id = ?",
                [$image, $title, $power_score, $level, $price, $status, $id]
            );
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Conta atualizada com sucesso!']);
            break;

        case 'delete':
            $id = (int)$_GET['id'];
            $db->query("DELETE FROM accounts WHERE id = ?", [$id]);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Conta removida com sucesso!']);
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