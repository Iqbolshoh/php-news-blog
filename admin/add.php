<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login/');
    exit;
}

include '../config.php';
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!empty($_POST['title']) && !empty($_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user']['id'];

        $inserted = $db->insert(
            'news',
            [
                'title' => $title,
                'content' => $content,
                'user_id' => $user_id
            ]
        );
        if ($inserted) {
            header('Location: ./');
        } else {
            echo "Yangilik qoshilmadi!!";
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
        <input type="text" name="title">
        <input type="text" name="content">
        <button type="submit">Submit</button>
    </form>

</body>

</html>