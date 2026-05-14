<?php
session_start();

$error_message = '';
$success_message = '';

$toast = '';

if (isset($_GET['success'])) {
    $toast = "Account created successfully!";
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid') {
        $toast = "Invalid credentials.";
    }
}

if (!empty($_SESSION['logged_in']) && !empty($_SESSION['role'])){
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/admin_dashboard.php');
         exit;
         }
        if ($_SESSION['role'] === 'user') {
            header('Location: index.php');
            exit;
        }
}

require_once 'config.php';

$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    
    if ($action === 'login') {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {

                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                header('Location: ' . ($user['role'] === 'admin' ? 'admin/admin_dashboard.php' : 'index.php'));
                exit;
            } else {
                $error_message = "Password incorrect.";
            }
        } else {
            $error_message = "User not found.";
        }
    }

    if ($action === 'register') {

        $name = $conn->real_escape_string(trim($_POST['name']));
        $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($check && $check->num_rows > 0) {
            $error_message = "Email already used.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password, role)
                    VALUES ('$name', '$email', '$hashedPassword', 'user')";

            if ($conn->query($sql)) {
                header("Location: login.php?success=1");
                exit;
            } else {
                $error_message = "Error occurred during registration." . $conn->error;
            }
        }
    }
}
closeConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TravelToday</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>Who are you ?</h1>
                <p>- TravelToday -</p>
            </div>
            <div class="toggle-buttons">
                <button type="button" class="toggle-button" id="loginTab" onclick="showForm('login')">Login</button>
                <button type="button" class="toggle-button" id="registerTab" onclick="showForm('register')">Register</button>
            </div>

            <form method="POST" action="login.php" class="login-form">
                <input type="hidden" name="action" id="action" value="login">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                <div class="form-group" id="nameField" style="display:none;">
                    <label for="name">
                        <i class="fas fa-user"></i> Name
                    </label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="login-button" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span id="btnText">Login</span>
                </button>
            </form>
            <script>
                function showForm(type) {
                    document.getElementById("action").value = type;

                    if (type === "register") {
                        document.getElementById("nameField").style.display = "block";
                        document.getElementById("name").setAttribute("required", "true");
                        document.getElementById("btnText").textContent = "Create an account";

                        document.getElementById("loginTab").classList.remove('active');
                        document.getElementById("registerTab").classList.add('active');

                    } else {
                        document.getElementById("nameField").style.display = "none";
                        document.getElementById("name").removeAttribute("required");
                        document.getElementById("btnText").textContent = "Login";

                        document.getElementById("loginTab").classList.add('active');
                        document.getElementById("registerTab").classList.remove('active');
                    }
                }
            </script>
            <div class="login-footer">
                <a href="index.php"><i class="fas fa-home"></i> Return to home</a>
            </div>
        </div>
    </div>
    <?php if ($toast): ?>
    <div id="toast" class="toast">
        <?php echo $toast; ?>
    </div>
    <?php endif; ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toast = document.getElementById("toast");
            if (toast) {
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        });
    </script>
</body>
</html>