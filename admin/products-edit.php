<?php include('includes/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    Edit Products
                    <a href="products.php" class="btn btn-danger float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">

                <?= alertMessage(); ?>

                <form action="code.php" method="POST" enctype="multipart/form-data">

                    <?php
                    $paramResult = checkParamId('id');
                    if (!is_numeric($paramResult)) {
                        echo '<h5>' . $paramResult . '</h5>';
                        return false;
                    }

                    $product = getById('products', $paramResult);
                    if ($product) {

                        if ($product['status'] == 200) {
                            // Convert product sizes to array
                            $productSizes = !empty($product['data']['size']) ? explode(',', $product['data']['size']) : [];
                            ?>
                            <input type="hidden" name="productId" value="<?= $product['data']['id']; ?>">

                            <div class="mb-3">
                                <label for="#">Product Image:</label>

                                <input type="file" name="image" class="form-control">
                                <img src="<?= '../' . $product['data']['image'] ?>" style="width:70px;height:70px;" alt="Img" />
                            </div>

                            <div class="mb-3">
                                <label for="#">Product Name:</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($product['data']['name']); ?>"
                                    required class="form-control">
                            </div>


                            <div class="mb-3">
                                <label for="#">Price:</label>
                                <input type="number" name="price" value="<?= $product['data']['price']; ?>"
                                    class="form-control">
                                </di v>

                                <div class="mb-3">
                                    <label><strong>Select Size:</strong></label><br><br>
                                    <?php
                                    $sizes = ['S' => 'Small', 'M' => 'Medium', 'L' => 'Large', 'XL' => 'Extra Large'];
                                    foreach ($sizes as $key => $label) {
                                        $checked = in_array($key, $productSizes) ? 'checked' : '';
                                        echo "<input type='checkbox' name='size[]' value='$key' $checked> $label <br>";
                                    }
                                    ?>
                                </div>

                                <div class="mb-3">
                                    <label for="#">Description:</label>
                                    <textarea name="description" class="form-control"
                                        rows="3"><?= htmlspecialchars($product['data']['description']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="#">Status:</label>
                                    <br>
                                    <input type="checkbox" name="status" <?= $product['data']['status'] == 1 ? 'checked' : ''; ?>
                                        style="width:30px;height:30px;">
                                </div>

                                <div class="mb-3 text-end">
                                    <button type="submit" name="updateProduct" class="btn btn-primary">Update Product</button>
                                </div>

                                <?php
                        } else {
                            echo "<h5>No such data found!</h5>";
                        }

                    } else {
                        echo "<h5>Something Went Wrong!</h5>";
                    }
                    ?>


                </form>



            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php'); ?>