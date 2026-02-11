<?php
require 'config/login-function.php';
$activeForm = $_SESSION['active_form'] ?? 'login';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login & Register</title>
    <link rel="stylesheet" href="Css/style.css">
    <style>
        /* ===== ALERT MESSAGES ===== */
        .alert {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            box-sizing: border-box;
            position: relative;
            opacity: 1;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        /* Error alert (danger) */
        .alert-danger {
            background-color: #ffe6e6;
            color: #b10000;
            border: 1px solid #ffb3b3;
        }

        /* Success alert */
        .alert-success {
            background-color: #85ff9d;
            color: #000000;
            border: 1px solid #45cd62;
        }

        /* Smooth fade-in animation */
        .alert {
            animation: alertFade 0.3s ease-in-out;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- LOGIN FORM -->
        <div class="form-box <?= $activeForm === 'login' ? 'active' : '' ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Login</h2>
                <?php alertMessage('login'); ?>
                <input type="email" name="email" required>
                <input type="password" name="password" required>
                <button type="submit" name="loginBtn">Login</button>
                <p>Don't have an account?
                    <a href="#" onclick="showForm('register-form')">Register</a>
                </p>
            </form>
        </div>

        <!-- REGISTER FORM -->
        <div class="form-box <?= $activeForm === 'register' ? 'active' : '' ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h2>Register</h2>
                <?php alertMessage('register'); ?>
                <input type="text" name="name" required>
                <input type="text" name="phone" required>
                <input type="email" name="email" required>
                <input type="password" name="password" required>
                <button type="submit" name="register">Register</button>
                <p>Already have an account?
                    <a href="#" onclick="showForm('login-form')">Login</a>
                </p>
            </form>
        </div>

    </div>

    <!-- JS -->
    <script src="JS/script.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 500); // remove after transition
            });
        }, 5000);
    </script>
</body>

</html>