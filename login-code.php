<?php
session_start();
require 'config/function.php';

if (isset($_POST['loginBtn'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {

        $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);

            // PLAIN TEXT PASSWORD CHECK
            if ($password === $row['password']) {

                if ($row['is_ban'] == 1) {
                    redirect('login.php', 'Your account is banned');
                    exit;
                }

                $_SESSION['auth'] = true;
                $_SESSION['loggedInUserRole'] = $row['role'];
                $_SESSION['loggedInUser'] = [
                    'user_id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email']
                ];

                if ($row['role'] === 'admin') {
                    redirect('admin/index.php', 'Logged in successfully');
                } else {
                    redirect('index.php', 'Logged in successfully');
                }

            } else {
                redirect('login.php', 'Password does not match');
            }

        } else {
            redirect('login.php', 'Email not found');
        }

    } else {
        redirect('login.php', 'All fields are mandatory');
    }
}

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

// session_unset();

function showError($error)
{
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm)
{
    return $formName === $activeForm ? 'active' : '';
}

?>