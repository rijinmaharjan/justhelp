<?php
include('includes/header.php');

// Check the correct session key
if (!isset($_SESSION['loggedInUser'])) {
    echo "<div class='container mt-5 text-center'>
            <h4>Please <a href='login.php'>Login</a> to view your cart items.</h4>
          </div>";
    include('includes/footer.php');
    exit();
}

// Get user_id from the correct session key
$userId = $_SESSION['loggedInUser']['user_id'];

// SQL JOIN to combine cart and product data
$query = "SELECT c.id as cart_id, c.size, c.quantity, p.name, p.price, p.image 
          FROM cart c 
          INNER JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = '$userId'";

$queryRun = mysqli_query($conn, $query);
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
                <?php if (mysqli_num_rows($queryRun) > 0): ?>
                    <?php while ($item = mysqli_fetch_assoc($queryRun)): ?>
                        <tr>
                            <td>
                                <img src="<?= $item['image']; ?>" width="60px" class="rounded me-2" alt="">
                                <?= $item['name']; ?> <small class="text-muted">(<?= $item['size']; ?>)</small>
                            </td>
                            <td>₹<?= $item['price']; ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>₹<?= $item['price'] * $item['quantity']; ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm">Remove</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Your cart is empty.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>