<?php
$pageTitle = "View Order";
include('includes/header.php');

if (!isset($_SESSION['auth']) || empty($_SESSION['loggedInUser']['user_id'])) {
    redirect('login.php', 'Please login to view your order details');
}

$userId = (int) $_SESSION['loggedInUser']['user_id'];
ensureOrdersTableExists($conn);
$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($orderId <= 0) {
    redirect('my-orders.php', 'Invalid order');
}

$orderQuery = "SELECT * FROM orders WHERE id='$orderId' AND user_id='$userId' LIMIT 1";
$orderRun = mysqli_query($conn, $orderQuery);
$order = ($orderRun && mysqli_num_rows($orderRun) === 1) ? mysqli_fetch_assoc($orderRun) : null;

if (!$order) {
    redirect('my-orders.php', 'Order not found');
}

$orderItems = json_decode($order['order_items'], true);
if (!is_array($orderItems)) {
    $orderItems = [];
}
?>

<div class="container py-4">
    <div class="account-settings-wrapper">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="mb-0">Order #<?= $order['id']; ?></h3>
            <a href="my-orders.php" class="btn btn-sm btn-outline-secondary">Back to My Orders</a>
        </div>

        <div class="order-meta-grid mt-3">
            <div>
                <strong>Status:</strong> <?= htmlspecialchars($order['order_status']); ?>
            </div>
            <div>
                <strong>Payment:</strong> <?= htmlspecialchars(strtoupper($order['payment_method'])); ?>
            </div>
            <div>
                <strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])); ?>
            </div>
            <div>
                <strong>Total:</strong> ₹<?= $order['total_amount']; ?>
            </div>
        </div>

        <div class="account-form-section mt-3">
            <h5>Delivery Details</h5>
            <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($order['full_name']); ?></p>
            <p class="mb-1"><strong>Contact:</strong> <?= htmlspecialchars($order['phone']); ?></p>
            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>
            <p class="mb-1">
                <strong>Address:</strong>
                <?= htmlspecialchars($order['address_line1']); ?>,
                <?= htmlspecialchars($order['address_line2']); ?>,
                <?= htmlspecialchars($order['city']); ?>,
                <?= htmlspecialchars($order['state']); ?> - <?= htmlspecialchars($order['pincode']); ?>
            </p>
        </div>

        <div class="account-form-section mt-3">
            <h5>Items</h5>
            <?php if (!empty($orderItems)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['name'] ?? ''); ?></td>
                                    <td><?= htmlspecialchars($item['size'] ?? ''); ?></td>
                                    <td><?= (int) ($item['quantity'] ?? 0); ?></td>
                                    <td>₹<?= htmlspecialchars($item['price'] ?? '0'); ?></td>
                                    <td>₹<?= htmlspecialchars($item['line_total'] ?? '0'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No item details available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
