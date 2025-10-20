<?php
require_once 'database.php';

function getSetting($key) {
    $db = Database::getInstance();
    $result = $db->fetch("SELECT setting_value FROM site_settings WHERE setting_key = ?", [$key]);
    return $result ? $result['setting_value'] : null;
}

function getSections() {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM sections WHERE is_active = TRUE ORDER BY display_order");
}

function getSection($name) {
    $db = Database::getInstance();
    return $db->fetch("SELECT * FROM sections WHERE name = ? AND is_active = TRUE", [$name]);
}

function getServices() {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM services WHERE is_active = TRUE ORDER BY display_order");
}

function getAccounts($limit = null) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM accounts WHERE is_active = TRUE ORDER BY display_order";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    return $db->fetchAll($sql);
}

function getFAQs() {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM faqs WHERE is_active = TRUE ORDER BY display_order");
}

function getTestimonials($limit = null) {
    $db = Database::getInstance();
    $sql = "SELECT * FROM testimonials WHERE is_active = TRUE ORDER BY display_order";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    return $db->fetchAll($sql);
}

function getPartners() {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM partners WHERE is_active = TRUE ORDER BY display_order");
}

function getSocialMedia() {
    $db = Database::getInstance();
    return $db->fetchAll("SELECT * FROM social_media WHERE is_active = TRUE ORDER BY display_order");
}

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Função jsonResponse removida - usando echo direto nos arquivos
?>