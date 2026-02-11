<?php
$pageTitle = "Product Detail";
include('includes/header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php', 'Invalid product');
    exit;
}

$productId = $_GET['id'];

$query = "SELECT * FROM products WHERE id = '$productId' AND status = '0' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    redirect('index.php', 'Product not found');
    exit;
}

$rowData = mysqli_fetch_assoc($result);
$sizes = !empty($rowData['size']) ? explode(',', $rowData['size']) : [];

?>

<style>
    /* Size boxes styling */
    .size-options {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .size-options input[type="radio"] {
        display: none;
        /* hide the default radio button */
    }

    .size-box {
        border: 1px solid #ccc;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.2s;
        user-select: none;
        text-align: center;
        font-weight: bold;
    }

    .size-box:hover {
        border-color: #7a1e2c;
        background-color: #7a1e2c;
        color: white;
    }

    .size-options input[type="radio"]:checked+.size-box {
        border-color: #7a1e2c;
        background-color: #7a1e2c;
        color: #fff;
    }

    /* Quantity selector styling */
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 15px;
    }

    .quantity-selector input[type="number"] {
        width: 60px;
        text-align: center;
        padding: 5px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .qty-btn {
        padding: 5px 12px;
        border: 1px solid #7a1e2c;
        background-color: #fff;
        color: #7a1e2c;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.2s;
    }

    .qty-btn:hover {
        background-color: #7a1e2c;
        color: #fff;
    }
</style>

<form action="cart-code.php" method="POST" class="product-detail">
    <input type="hidden" name="product_id" value="<?= $rowData['id']; ?>">
    <div class="product-right">
        <div class="product-img">
            <div class="thumbnail-list">
                <?php if (!empty($rowData['image'])): ?>
                    <img src="<?= $rowData['image']; ?>" class="w-100 rounded" alt="Product Image">
                <?php else: ?>
                    <img src="assets/images/cart-2-1.jpg" class="w-100 rounded" alt="Default Image">
                <?php endif; ?>
            </div>

            <div class="main-img">
                <div class="md-3">
                    <?php if (!empty($rowData['image'])): ?>
                        <img src="<?= $rowData['image']; ?>" class="w-100 rounded" alt="Product Image">
                    <?php else: ?>
                        <img src="assets/images/cart-2-1.jpg" class="w-100 rounded" alt="Default Image">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="product-info">
        <h2 class="title">
            <?= $rowData['name']; ?>
        </h2>

        <span class="price">₹
            <?= $rowData['price']; ?>
        </span>

        <p>Select Size:</p>

        <?php if (!empty($sizes)): ?>
            <div class="size-options">
                <?php foreach ($sizes as $size):
                    $size = trim($size);
                    ?>
                    <label>
                        <input type="radio" name="size" value="<?= $size; ?>" required>
                        <div class="size-box">
                            <?= $size; ?>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Quantity Selector -->
        <p>Select Quantity:</p>
        <div class="quantity-selector">
            <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="99" required>
            <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
        </div>

        <p class="description">
            <?= $rowData['description']; ?>
        </p>

        <button type="submit" name="add_to_cart" class="btn add-cart-btn">
            Add to Cart
        </button>

        <div class="product-policy">
            <p>100% Original Product.</p>
            <p>Easy return and exchange policy within 14 days.</p>
        </div>
    </div>
</form>

<script>
    function changeQty(amount) {
        const qtyInput = document.getElementById('quantity');
        let current = parseInt(qtyInput.value);
        let newQty = current + amount;
        if (newQty < 1) newQty = 1;
        if (newQty > 99) newQty = 99;
        qtyInput.value = newQty;
    }
</script>

<?php include('includes/footer.php'); ?>