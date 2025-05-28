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
    echo "Yangilik topilmadi!";
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
                'title' => 'Yangilandi ✅',
                'message' => 'Yangilik muvaffaqiyatli yangilandi!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'title' => 'Xatolik ❌',
                'message' => 'Yangilashda xatolik yuz berdi.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'title' => 'To‘liq emas 📄',
            'message' => 'Iltimos, barcha maydonlarni to‘ldiring!'
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yangilikni tahrirlash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">Yangilikni tahrirlash</h3>

                        <form id="editForm">
                            <div class="mb-3">
                                <label for="title" class="form-label">Sarlavha</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?= htmlspecialchars($news['title']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Kontent</label>
                                <textarea class="form-control" id="content" name="content" rows="5"
                                    required><?= htmlspecialchars($news['content']) ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Saqlash</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            window.location.href = './'; // orqaga qaytish
                        }
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xatolik!',
                        text: 'Serverga ulanishda muammo yuz berdi 😵‍💫'
                    });
                    console.error('Fetch xatolik:', error);
                });
        });
    </script>
</body>

</html>