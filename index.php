<?php
$pageTitle = "index";
include('includes/header.php');

$sortBy = $_GET['sort'] ?? '';
$rawSearch = trim($_GET['search'] ?? '');
$searchTerm = mysqli_real_escape_string($conn, $rawSearch);
$whereParts = ["status='0'"];

if ($searchTerm !== '') {
    $whereParts[] = "(name LIKE '%$searchTerm%' OR description LIKE '%$searchTerm%' OR size LIKE '%$searchTerm%')";
}

$orderBy = $sortBy === 'new' ? " ORDER BY id DESC" : " ORDER BY id ASC";
$productQuery = "SELECT * FROM products WHERE " . implode(' AND ', $whereParts) . $orderBy;
$result = mysqli_query($conn, $productQuery);
?>
<!-- top dashboard intro -->
<section class="store-hero section-p1">
    <h1>New Era Fashion Store</h1>
    <p>Scroll down to explore all products, latest arrivals, and trending styles.</p>
    <a href="#all-products" class="btn add-cart-btn">Browse Products</a>
</section>

<div class="py-5 bg-light" id="all-products">
    <div class="container">

        <?php if ($rawSearch !== ''): ?>
            <div class="search-result-bar">
                <p>
                    Showing results for: <strong><?= htmlspecialchars($rawSearch); ?></strong>
                    <?php if ($sortBy === 'new'): ?>
                        <span class="search-tag">Sorted: Newest</span>
                    <?php endif; ?>
                </p>
                <a href="index.php" class="btn btn-sm btn-outline-secondary">Clear Filters</a>
            </div>
        <?php endif; ?>

        <div class="row">

            <?php
            if ($result) {
                if (mysqli_num_rows($result) > 0) {

                    foreach ($result as $row) {
                        ?>

                        <div class="col-md-3 mb-3">
                            <div class="card shadow-sm">
                                <?php if ($row['image'] != ''): ?>
                                    <img src="<?= $row['image']; ?>" class="w-100 rounded" alt="Img"
                                        style="min-height:300px;max-height:300px;">
                                <?php else: ?>
                                    <img src="img" class="w-100 rounded" alt="Img" style="min-height:300px;max-height:300px;">
                                <?php endif; ?>

                                <div class="card-body">
                                    <h5><?= $row['name']; ?></h5>
                                    <p>₹<?= $row['price']; ?></p>
                                    <div>
                                        <a href="product-detail.php?id=<?= $row['id']; ?>" class="btn add-cart-btn">
                                            View Product
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col-md-12">
                        <h5>No products found<?= $rawSearch !== '' ? " for \"" . htmlspecialchars($rawSearch) . "\"" : ''; ?>.</h5>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-md-12">
                    <h5>Something Went Wrong!</h5>
                </div>
                <?php
            }
            ?>


        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
