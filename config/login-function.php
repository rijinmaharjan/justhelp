<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'dbcon.php';

function validate($input)
{
    global $conn;
    return trim(mysqli_real_escape_string($conn, $input));
}

function redirect($url, $message, $form = 'login')
{
    $_SESSION['status'] = $message;
    $_SESSION['active_form'] = $form;
    header("Location: $url");
    exit;
}

function alertMessage($form)
{
    if (
        !empty($_SESSION['status']) &&
        isset($_SESSION['active_form']) &&
        $_SESSION['active_form'] === $form
    ) {
        echo "<div class='alert alert-danger'>
                <h6>{$_SESSION['status']}</h6>
              </div>";
        unset($_SESSION['status']);
        unset($_SESSION['active_form']);
    }
}
