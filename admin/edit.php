<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

include '../config.php';
$db = new Database();

$news_id = $_GET['id'] ?? 0;
$news = $db->select('news', '*', 'id = ?', [$news_id], 'i')[0];


if ($_SERVER['REQUEST_METHOD'] == "POST") {
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
            header('Location: ./');
        } else {
            echo "Yangilik yangilanmadi!!";
        }
    } else {
        echo "Iltimos hamma maydonlarini to‘ldiring!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
</head>

<body>

    <form action="" method="post">
        <input type="text" name="title" value="<?= $news['title'] ?>">
        <input type="text" name="content" value="<?= $news['content'] ?>">>
        <button type="submit">Submit</button>
    </form>

</body>

</html>