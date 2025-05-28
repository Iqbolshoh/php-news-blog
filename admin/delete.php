<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

include '../config.php';
$db = new Database();

$news_id = $_POST['id'] ?? 0;

if ($news_id) {
    $deleted = $db->delete('news', 'id = ?', [$news_id], 'i');
    if ($deleted) {
        echo json_encode([
            'success' => true,
            'title' => 'O‘chirildi ✅',
            'message' => 'Yangilik muvaffaqiyatli o‘chirildi.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'title' => 'Xatolik ⚠️',
            'message' => 'Yangilikni o‘chirishda muammo yuz berdi.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'title' => 'Topilmadi ❌',
        'message' => 'Yangilik ID topilmadi.'
    ]);
}