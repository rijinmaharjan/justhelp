<?php
$pageTitle = "My Orders";
include('includes/header.php');

if (!isset($_SESSION['auth']) || empty($_SESSION['loggedInUser']['user_id'])) {
    redirect('login.php', 'Please login to view your orders');
}

$userId = (int) $_SESSION['loggedInUser']['user_id'];
ensureOrdersTableExists($conn);
$orderQuery = "SELECT * FROM orders WHERE user_id = '$userId' ORDER BY id DESC";
$orderRun = mysqli_query($conn, $orderQuery);
?>

<div class="container py-4">
    <div class="account-settings-wrapper">
        <h3>My Orders</h3>
        <?php alertMessage(); ?>

        <?php if ($orderRun && mysqli_num_rows($orderRun) > 0): ?>
            <div class="table-responsive mt-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($orderRun)): ?>
                            <tr>
                                <td>#<?= $order['id']; ?></td>
                                <td><?= date('d M Y, h:i A', strtotime($order['created_at'])); ?></td>
                                <td>₹<?= $order['total_amount']; ?></td>
                                <td><span class="order-status-pill"><?= htmlspecialchars($order['order_status']); ?></span></td>
                                <td>
                                    <a href="view-order.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-outline-dark">View Order</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="mt-3">No orders yet. Place an order from your cart to see it here.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
