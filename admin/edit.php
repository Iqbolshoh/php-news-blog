<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

include '../config.php';
$db = new Database();

$news_id = $_GET['id'] ?? 0;
$news = $db->select('news', '*', 'id = ?', [$news_id], 'i')[0] ?? null;

if (!$news) {
    echo "News not found!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (!empty($_POST['title']) && !empty($_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $updated = $db->update(
            'news',
            ['title' => $title, 'content' => $content],
            'id = ?',
            [$news_id],
            'i'
        );

        if ($updated) {
            echo json_encode([
                'success' => true,
                'title' => 'Updated ✅',
                'message' => 'The news has been successfully updated!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'title' => 'Error ❌',
                'message' => 'Failed to update the news.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'title' => 'Incomplete Fields 📄',
            'message' => 'Please fill in all the fields!'
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="./">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navMenu">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="./">🏠 Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout/">🚪 Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Edit Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h3 class="text-center mb-4">✏️ Edit News</h3>

                        <form id="editForm">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?= htmlspecialchars($news['title']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="5"
                                    required><?= htmlspecialchars($news['content']) ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">💾 Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(response => response.json())
                .then(result => {
                    Swal.fire({
                        icon: result.success ? 'success' : 'error',
                        title: result.title,
                        text: result.message
                    }).then(() => {
                        if (result.success) {
                            window.location.href = './';
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Something went wrong with the server.', 'error');
                });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>