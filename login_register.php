<?php
// require_once 'config.php';
// require 'config/function.php';

// =============================
// REGISTER USER
// =============================


// if (isset($_POST['register'])) {

//     $name = validate($_POST['name']);
//     $phone = validate($_POST['phone']);
//     $email = validate($_POST['email']);
//     $password = validate($_POST['password']);
//     $is_ban = isset($_POST['is_ban']) ? 1 : 0;

//     if ($name != '' && $email != '' && $phone != '' && $password != '') {

//         $query = "INSERT INTO users (name, phone, email, password, is_ban)
//                   VALUES ('$name','$phone','$email','$password','$is_ban')";
//         $result = mysqli_query($conn, $query);


//         $_SESSION['active_form'] = "login";
//         header("Location: login.php");
//         exit();
//     }
// }

// =============================
// LOGIN USER
// =============================

// if (isset($_POST['loginBtn'])) {

//     $email = trim($_POST['email']);
//     $password = trim($_POST['password']);

//     if (!empty($email) && !empty($password)) {

//         $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
//         $result = mysqli_query($conn, $query);

//         if ($result && mysqli_num_rows($result) === 1) {

//             $row = mysqli_fetch_assoc($result);

//             // PLAIN TEXT PASSWORD CHECK
//             if ($password === $row['password']) {

//                 if ($row['is_ban'] == 1) {
//                     redirect('login.php', 'Your account is banned');
//                     exit;
//                 }

//                 $_SESSION['auth'] = true;
//                 $_SESSION['loggedInUserRole'] = $row['role'];
//                 $_SESSION['loggedInUser'] = [
//                     'name' => $row['name'],
//                     'email' => $row['email']
//                 ];

//                 if ($row['role'] === 'admin') {
//                     redirect('admin/index.php', 'Logged in successfully');
//                 } else {
//                     redirect('index.php', 'Logged in successfully');
//                 }

//             } else {
//                 redirect('login.php', 'Password does not match');
//             }

//         } else {
//             redirect('login.php', 'Email not found');
//         }

//     } else {
//         redirect('login.php', 'All fields are mandatory');
//     }
// }
?>



<?php
require 'config/login-function.php';

/* ================= REGISTER ================= */
if (isset($_POST['register'])) {

    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if ($name == '' || $phone == '' || $email == '' || $password == '') {
        redirect('login.php', 'All fields are required', 'register');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect('login.php', 'Invalid email format', 'register');
    }

    if (strlen($password) < 6) {
        redirect('login.php', 'Password must be at least 6 characters', 'register');
    }

    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' OR phone='$phone' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        redirect('login.php', 'Email or phone already exists', 'register');
    }

    mysqli_query(
        $conn,
        "INSERT INTO users (name, phone, email, password, is_ban)
         VALUES ('$name','$phone','$email','$password',0)"
    );

    redirect('login.php', 'Registration successful. Please login', 'login');
}


/* ================= LOGIN ================= */
if (isset($_POST['loginBtn'])) {

    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if ($email == '' || $password == '') {
        redirect('login.php', 'Email and password required', 'login');
    }

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

    if (mysqli_num_rows($query) !== 1) {
        redirect('login.php', 'Email not found', 'login');
    }

    $user = mysqli_fetch_assoc($query);

    if ($user['password'] !== $password) {
        redirect('login.php', 'Incorrect password', 'login');
    }

    if ($user['is_ban'] == 1) {
        redirect('login.php', 'Your account is banned', 'login');
    }

    $_SESSION['auth'] = true;
    $_SESSION['loggedInUser'] = [
        'user_id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];

    redirect(
        $user['role'] === 'admin' ? 'admin/index.php' : 'index.php',
        'Logged in successfully',
        'login'
    );
}
