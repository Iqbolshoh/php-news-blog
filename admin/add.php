<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

include '../config.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (!empty($_POST['title']) && !empty($_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user']['id'];

        $id = $db->insert('news', [
            'title' => $title,
            'content' => $content,
            'user_id' => $user_id
        ]);

        if ($id) {
            echo json_encode([
                'success' => true,
                'title' => 'Success ✅',
                'message' => 'News added successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'title' => 'Save Error ❌',
                'message' => 'Failed to save the news.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'title' => 'Incomplete Form 📄',
            'message' => 'Please fill out all required fields.'
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
    <title>Add News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">Add News</h3>

                        <form id="newsForm">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Enter title..." required>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="5"
                                    placeholder="Write your news content..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('newsForm').addEventListener('submit', function (e) {
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
                            document.getElementById('newsForm').reset();
                            window.location.href = './';
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error ❌',
                        text: 'Something went wrong while connecting to the server.'
                    });
                    console.error('Fetch error:', error);
                });
        });
    </script>
</body>

</html>