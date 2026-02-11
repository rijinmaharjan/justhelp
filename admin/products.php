<?php include('includes/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    Product
                    <a href="product-add.php" class="btn btn-primary float-end">Product Add</a>
                </h4>
            </div>
            <div class="card-body">

                <?= alertMessage(); ?>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Img</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Size</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $products = getAll('products');
                        if ($products) {
                            if (mysqli_num_rows($products) > 0) {
                                foreach ($products as $item) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $item['id']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (!empty($item['image']) && file_exists('../' . $item['image'])) {
                                                echo '<img src="../' . $item['image'] . '" alt="' . $item['name'] . '" style="width:50px;height:50px;object-fit:cover;">';
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?= $item['name']; ?>
                                        </td>
                                        <td>Rs.
                                            <?= $item['price']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (!empty($item['size'])) {
                                                $sizes = explode(',', $item['size']); // handle multiple sizes
                                                foreach ($sizes as $s) {
                                                    echo '<span class="badge bg-primary me-1">' . $s . '</span>';
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                        <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            <?= $item['description']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($item['status'] == 1) {
                                                echo '<span class="badge bg-danger text-white">Hidden</span>';
                                            } else {
                                                echo '<span class="badge bg-success text-white">Visible</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="products-edit.php?id=<?= $item['id']; ?>"
                                                class="btn btn-success btn-sm mb-0">Edit</a>
                                            <a href="products-delete.php?id=<?= $item['id']; ?>"
                                                class="btn btn-danger btn-sm mb-0 mx-1"
                                                onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8" class="text-center">No products found</td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="8" class="text-center">Something went wrong</td>
                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php'); ?>