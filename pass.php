<?php
// reset_admin_password.php
require_once 'includes/config.php';
require_once 'includes/database.php';

// Nova senha (altere para a senha desejada)
$nova_senha = 'admin123';

// Hash da nova senha
$password_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

// Atualizar no banco de dados
$db = Database::getInstance();
$stmt = $db->getConnection()->prepare("UPDATE admins SET password_hash = ? WHERE username = 'admin'");
$stmt->execute([$password_hash]);

if ($stmt->rowCount() > 0) {
    echo "Senha do admin atualizada com sucesso!<br>";
    echo "Nova senha: " . $nova_senha;
} else {
    echo "Erro ao atualizar a senha.";
}
?>