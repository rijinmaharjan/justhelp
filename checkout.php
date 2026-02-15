<?php
include('includes/header.php');

if (!isset($_SESSION['loggedInUser'])) {
    redirect('login.php', 'Please login to continue checkout');
}

$userId = (int) $_SESSION['loggedInUser']['user_id'];

ensureOrdersTableExists($conn);

$cartQuery = "SELECT c.product_id, c.quantity, c.size, p.name, p.price
              FROM cart c
              INNER JOIN products p ON c.product_id = p.id
              WHERE c.user_id = '$userId'";
$cartRun = mysqli_query($conn, $cartQuery);

$cartItems = [];
$grandTotal = 0;
if ($cartRun) {
    while ($row = mysqli_fetch_assoc($cartRun)) {
        $row['line_total'] = $row['price'] * $row['quantity'];
        $grandTotal += $row['line_total'];
        $cartItems[] = $row;
    }
}

if (isset($_POST['placeOrderBtn'])) {
    $fullName = validate($_POST['full_name'] ?? '');
    $phone = validate($_POST['phone'] ?? '');
    $email = validate($_POST['email'] ?? '');
    $addressLine1 = validate($_POST['address_line1'] ?? '');
    $addressLine2 = validate($_POST['address_line2'] ?? '');
    $city = validate($_POST['city'] ?? '');
    $state = validate($_POST['state'] ?? '');
    $pincode = validate($_POST['pincode'] ?? '');
    $paymentMethod = validate($_POST['payment_method'] ?? '');

    if (empty($cartItems)) {
        redirect('cart.php', 'Your cart is empty');
    }

    if (
        $fullName === '' || $phone === '' || $email === '' || $addressLine1 === ''
        || $city === '' || $state === '' || $pincode === ''
    ) {
        redirect('checkout.php', 'Please fill all required details');
    }

    if ($paymentMethod !== 'cod') {
        redirect('checkout.php', 'Only Cash on Delivery is available right now');
    }

    $orderItemsJson = mysqli_real_escape_string($conn, json_encode($cartItems));

    $orderQuery = "INSERT INTO orders
        (user_id, full_name, phone, email, address_line1, address_line2, city, state, pincode, payment_method, total_amount, order_items, order_status)
        VALUES
        ('$userId', '$fullName', '$phone', '$email', '$addressLine1', '$addressLine2', '$city', '$state', '$pincode', 'cod', '$grandTotal', '$orderItemsJson', 'Placed')";

    if (mysqli_query($conn, $orderQuery)) {
        mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$userId'");
        redirect('index.php', 'Order placed successfully with Cash on Delivery');
    }

    redirect('checkout.php', 'Unable to place order right now');
}
?>

<div class="container py-5">
    <h3 class="mb-4">Checkout</h3>
    <?php alertMessage(); ?>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-warning">Your cart is empty. <a href="index.php">Continue shopping</a>.</div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-md-7">
                <div class="checkout-card">
                    <h5 class="mb-3">Delivery Details</h5>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Full Name *</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Contact Number *</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Pincode *</label>
                                <input type="text" name="pincode" class="form-control" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Address Line 1 *</label>
                                <input type="text" name="address_line1" class="form-control" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Address Line 2</label>
                                <input type="text" name="address_line2" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>City *</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>State *</label>
                                <input type="text" name="state" class="form-control" required>
                            </div>
                        </div>

                        <h6 class="mt-3">Payment Method</h6>
                        <div class="checkout-payment-option">
                            <label>
                                <input type="radio" name="payment_method" value="cod" checked>
                                Cash on Delivery
                            </label>
                        </div>

                        <button type="submit" name="placeOrderBtn" class="btn add-cart-btn mt-3">Save and Continue</button>
                    </form>
                </div>
            </div>

            <div class="col-md-5">
                <div class="checkout-card">
                    <h5 class="mb-3">Order Summary</h5>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?= htmlspecialchars($item['name']); ?> (<?= htmlspecialchars($item['size']); ?>) x <?= $item['quantity']; ?></span>
                            <span>₹<?= $item['line_total']; ?></span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total</strong>
                        <strong>₹<?= $grandTotal; ?></strong>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
