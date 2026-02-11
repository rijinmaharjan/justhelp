<?php include('includes/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    Add Products
                    <a href="products.php" class="btn btn-danger float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">

                <?= alertMessage(); ?>

                <form action="code.php" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="#">Product Image:</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="#">Product Name:</label>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="#">Price:</label>
                        <input type="number" name="price" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label><strong>Select Size:</strong></label><br><br>

                        <input type="checkbox" name="size[]" value="S"> Small <br>
                        <input type="checkbox" name="size[]" value="M"> Medium <br>
                        <input type="checkbox" name="size[]" value="L"> Large <br>
                        <input type="checkbox" name="size[]" value="XL"> Extra Large <br>

                    </div>
                    <div class="mb-3">
                        <label for="#">Description:</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="#">Status:</label>
                        <br>
                        <input type="checkbox" name="status" style="width:30px;height:30px;">
                    </div>

                    <div class="mb-3 text-end">
                        <button type="submit" name="saveProduct" class="btn btn-primary">Save Product</button>
                    </div>


                </form>



            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php'); ?>