<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

include '../config.php';
$db = new Database();

$news_id = $_GET['id'] ?? 0;
$deleted = $db->delete('news', 'id = ?', [$news_id], 'i');
if ($deleted) {
    header('Location: ./');
}