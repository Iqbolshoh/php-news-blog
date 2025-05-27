<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'user') {
    header('Location: ./login/');
    exit;
}

include 'config.php';
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
    <!-- <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow text-center">
                    <div class="card-body p-4">
                        <h1 class="mb-4">Welcome User</h1>
                        <p class="mb-4">Welcome to our platform! Please login or sign up
                            to continue.</p>
                        <div class="d-grid gap-2">
                            <a href="./logout/" class="btn btn-primary">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <div class="container mt-5">
        <h2 class="text-center mb-4">Yangiliklar 📰</h2>
        <div class="row">
            <?php foreach ($news as $new): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($new['title']) ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($new['content'])) ?></p>
                        </div>
                        <div class="card-footer bg-white text-muted">
                            <small>📅 Joylangan sana: <?= date("d M Y, H:i", strtotime($new['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>