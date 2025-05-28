<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

header('Content-Type: application/json');

include '../config.php';
$db = new Database();

$news_id = $_POST['id'] ?? 0;

if ($news_id) {
    $deleted = $db->delete('news', 'id = ?', [$news_id], 'i');
    if ($deleted) {
        echo json_encode([
            'success' => true,
            'title' => 'Deleted ✅',
            'message' => 'The news item was successfully deleted.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'title' => 'Error ⚠️',
            'message' => 'Failed to delete the news item.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'title' => 'Not Found ❌',
        'message' => 'News ID was not provided.'
    ]);
}
