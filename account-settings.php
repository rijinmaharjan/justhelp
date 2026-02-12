<?php
$pageTitle = "Account Settings";
include('includes/header.php');

if (!isset($_SESSION['auth']) || empty($_SESSION['loggedInUser']['user_id'])) {
    redirect('login.php', 'Please login to continue');
}

$userId = (int) $_SESSION['loggedInUser']['user_id'];

if (isset($_POST['updateNameBtn'])) {
    $name = validate($_POST['name'] ?? '');

    if ($name === '') {
        redirect('account-settings.php', 'Name is required');
    }

    $updateQuery = "UPDATE users SET name='$name' WHERE id='$userId' LIMIT 1";
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['loggedInUser']['name'] = $name;
        redirect('account-settings.php', 'Name updated successfully');
    } else {
        redirect('account-settings.php', 'Unable to update name');
    }
}

if (isset($_POST['changePasswordBtn'])) {
    $currentPassword = validate($_POST['current_password'] ?? '');
    $newPassword = validate($_POST['new_password'] ?? '');

    if ($currentPassword === '' || $newPassword === '') {
        redirect('account-settings.php', 'Both password fields are required');
    }

    $userQuery = mysqli_query($conn, "SELECT password FROM users WHERE id='$userId' LIMIT 1");
    $userData = $userQuery ? mysqli_fetch_assoc($userQuery) : null;

    if (!$userData || $userData['password'] !== $currentPassword) {
        redirect('account-settings.php', 'Current password is incorrect');
    }

    $passwordUpdateQuery = "UPDATE users SET password='$newPassword' WHERE id='$userId' LIMIT 1";
    if (mysqli_query($conn, $passwordUpdateQuery)) {
        redirect('account-settings.php', 'Password changed successfully');
    } else {
        redirect('account-settings.php', 'Unable to change password');
    }
}

if (isset($_POST['deleteAccountBtn'])) {
    $deleteQuery = "DELETE FROM users WHERE id='$userId' LIMIT 1";

    if (mysqli_query($conn, $deleteQuery)) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['status'] = 'Account deleted successfully';
        header('Location: login.php');
        exit;
    } else {
        redirect('account-settings.php', 'Unable to delete account');
    }
}
?>

<div class="container py-4">
    <div class="account-settings-wrapper">
        <h3>Profile Settings</h3>
        <?php alertMessage(); ?>

        <div class="account-form-section">
            <h5>Edit Name</h5>
            <form method="POST">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($_SESSION['loggedInUser']['name']); ?>" required>
                <button type="submit" name="updateNameBtn" class="btn add-cart-btn">Save Name</button>
            </form>
        </div>

        <div class="account-form-section">
            <h5>Change Password</h5>
            <form method="POST">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>

                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>

                <button type="submit" name="changePasswordBtn" class="btn add-cart-btn">Update Password</button>
            </form>
        </div>

        <div class="account-form-section">
            <h5>Delete Account</h5>
            <p>This action cannot be undone.</p>
            <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
                <button type="submit" name="deleteAccountBtn" class="btn btn-danger-outline">Delete Account</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
