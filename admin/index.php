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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - News Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
        }

        .card-title {
            font-weight: 600;
        }

        .btn-sm {
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="./">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="./"><i class="bi bi-house-door-fill"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i>
                            Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- News List -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center flex-grow-1">📰 Latest News</h2>
            <a href="add.php" class="btn btn-success btn-sm ms-3"><i class="bi bi-plus-lg"></i> Add News</a>
        </div>

        <div class="row">
            <?php foreach ($news as $new): ?>
                <div class="col-md-6 mb-4" id="card-<?= $new['id'] ?>">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($new['title']) ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($new['content'])) ?></p>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-calendar-event"></i>
                                <?= date("d M Y, H:i", strtotime($new['created_at'])) ?></small>
                            <div>
                                <a href="edit.php?id=<?= $new['id'] ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="#" class="btn btn-sm btn-danger delete-btn" id="delete-<?= $new['id'] ?>">
                                    <i class="bi bi-trash3"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Delete with confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.id.replace('delete-', '');
                const card = document.getElementById('card-' + id);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This news item will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('delete.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'id=' + encodeURIComponent(id)
                        })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    icon: data.success ? 'success' : 'error',
                                    title: data.title,
                                    text: data.message
                                });
                                if (data.success && card) {
                                    card.remove();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Server error occurred.', 'error');
                            });
                    }
                });
            });
        });

        // Logout confirmation
        document.getElementById('logoutBtn').addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirm Logout',
                text: "Do you really want to logout? 😢",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../logout/';
                }
            });
        });
    </script>

</body>

</html>