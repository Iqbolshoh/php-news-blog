<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

include '../config.php';
$db = new Database();

$news = $db->select('news', '*');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center flex-grow-1">Yangiliklar 📰</h2>
            <a href="add.php" class="btn btn-success btn-sm ms-3">📝 Yangilik qo‘shish</a>
        </div>
        <div class="row">
            <?php foreach ($news as $new): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($new['title']) ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($new['content'])) ?></p>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <small class="text-muted">📅 <?= date("d M Y, H:i", strtotime($new['created_at'])) ?></small>
                            <div>
                                <a href="edit.php?id=<?= $new['id'] ?>" class="btn btn-sm btn-warning me-1">✏️
                                    Tahrirlash</a>
                                <a href="delete.php?id=<?= $new['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Aniq o‘chirilsinmi?')">🗑️ O‘chirish</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>