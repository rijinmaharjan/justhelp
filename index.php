<?php
$pageTitle = "index";
include('includes/header.php'); ?>
<!-- features-->
<section id="feature" class="section-p1">
    <h2>Featured Product</h2>
    <p>Summer Collection New Morden Design</p>
</section>

<div class="py-5 bg-light">
    <div class="container">
        <div class="row">

            <?php
            $productQuery = "SELECT * FROM products WHERE status='0'";
            $result = mysqli_query($conn, $productQuery);

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
                                    <p>
                                        <?= $row['price']; ?>
                                    </p>
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
                        <h5>No record Found</h5>
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