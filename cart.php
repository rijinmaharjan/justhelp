<?php
include('includes/header.php');

if (!isset($_SESSION['loggedInUser'])) {
    echo "<div class='container mt-5 text-center'>
            <h4>Please <a href='login.php'>Login</a> to view your cart items.</h4>
          </div>";
    include('includes/footer.php');
    exit();
}

$userId = $_SESSION['loggedInUser']['user_id'];

$query = "SELECT c.id as cart_id, c.size, c.quantity, p.name, p.price, p.image
          FROM cart c
          INNER JOIN products p ON c.product_id = p.id
          WHERE c.user_id = '$userId'";

$queryRun = mysqli_query($conn, $query);
$grandTotal = 0;
?>

<div class="container mt-5">
    <h4 class="mb-4">My Shopping Cart</h4>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($queryRun && mysqli_num_rows($queryRun) > 0): ?>
                    <?php while ($item = mysqli_fetch_assoc($queryRun)): ?>
                        <?php $itemTotal = $item['price'] * $item['quantity'];
                        $grandTotal += $itemTotal; ?>
                        <tr>
                            <td>
                                <img src="<?= $item['image']; ?>" width="60px" class="rounded me-2" alt="">
                                <?= $item['name']; ?> <small class="text-muted">(<?= $item['size']; ?>)</small>
                            </td>
                            <td>₹<?= $item['price']; ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>₹<?= $itemTotal; ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" disabled>Remove</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                        <td><strong>₹<?= $grandTotal; ?></strong></td>
                        <td></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Your cart is empty.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($grandTotal > 0): ?>
        <div class="d-flex justify-content-end mt-3">
            <a href="checkout.php" class="btn add-cart-btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
