<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: ../admin/');
        exit;
    } elseif ($_SESSION['user']['role'] === 'user') {
        header('Location: ../');
        exit;
    } else {
        exit("Bunday role mavjud emas!");
    }
}

include "../config.php";
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!empty($_POST['name']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            exit("Parollar mos emas!");
        }

        $name = trim($_POST['name']);
        $username = strtolower(trim($_POST['username']));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = "user"; // standart role

        $id = $db->insert('users', [
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'role' => $role
        ]);

        if ($id) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user']['id'] = $id;
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['role'] = $role;

            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: ../admin/');
                exit;
            } elseif ($_SESSION['user']['role'] === 'user') {
                header('Location: ../');
                exit;
            } else {
                exit("Bunday role mavjud emas!");
            }
        } else {
            echo "Malumot qo'shishda xatolik!";
        }
    } else {
        echo "Iltimos, barcha maydonlarini to‘ldiring!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Sign Up</h2>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter full name" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Enter username" required>
                            </div>

                            <!-- PASSWORD FIELD WITH TOGGLE -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="Enter password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- CONFIRM PASSWORD FIELD WITH TOGGLE -->
                            <div class="mb-3 position-relative">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="confirm_password" class="form-control"
                                        name="confirm_password" placeholder="Confirm password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </form>

                        <p class="text-center mt-3">Already have an account? <a href="../login/">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- PASSWORD TOGGLE FUNCTION -->
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>