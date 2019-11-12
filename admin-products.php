<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Products';
        $description = 'Add new products to your shop';

        include('includes/header.php');

        if (isset($_POST['edit'])) {
            $product = getProduct($db, $_POST['edit']);

        } else if (isset($_POST['add'])) {
            $is_success = addProduct($db, $_POST, $_FILES['product_img']);

        } else if (isset($_POST['update'])) {
            $is_success = updateProduct($db, $_POST);

        } else if (isset($_POST['delete'])) {
            $is_success = deleteProduct($db, $_POST['delete']);
        }

        $products = getProducts($db);
    ?>

    <body>
        <?php
            $page = 'products';
            include('includes/navigation.php');
        ?>

        <main>
            <!-- Add/edit product -->
            <form action="admin-products.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_uuid" value="<?= isset($_POST['edit']) ? $product['uuid'] : '' ?>">

                <?php if (isset($_POST['edit'])) { ?>
                    <h3>
                        <i class="fas fa-edit fa-sm"></i> Edit Product
                        <button type="submit" name="update">Update</button>
                    </h3>
                <?php } else { ?>
                    <h3>
                        <i class="fas fa-plus fa-sm"></i> Add Product
                        <button type="submit" name="add">Save</button>
                    </h3>
                <?php } ?>

                <?php if (isset($_POST['add'])) { ?>
                    <?php if ($is_success) { ?>
                        <p class="message success">Successfully added new product</p>
                    <?php } else { ?>
                        <p class="message fail">Unable to add new product</p>
                    <?php } ?>
                <?php } else if (isset($_POST['update'])) { ?>
                    <?php if ($is_success) { ?>
                        <p class="message success">Successfully updated your product</p>
                    <?php } else { ?>
                        <p class="message fail">Unable to update your product</p>
                    <?php } ?>
                <?php } else if (isset($_POST['delete'])) { ?>
                    <?php if ($is_success) { ?>
                        <p class="message success">Successfully deleted your product</p>
                    <?php } else { ?>
                        <p class="message fail">Unable to delete your product</p>
                    <?php } ?>
                <?php } ?>

                <table class="form-table">
                    <tr>
                        <td>
                            <label for="product_img">Product Image</label><br>
                            <input type="file" name="product_img">
                        </td>
                        <td>
                            <label for="product_name">Name</label><br>
                            <input type="text" name="product_name" value="<?= isset($_POST['edit']) ? $product['tv_name'] : '' ?>">
                        </td>
                        <td>
                            <label for="product_manufacturer">Manufacturer</label><br>
                            <input type="text" name="product_manufacturer" value="<?= isset($_POST['edit']) ? $product['manufacturer'] : '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="screen_size">Screen Size</label><br>
                            <input type="number" min="1" name="screen_size" value="<?= isset($_POST['edit']) ? $product['screen_size'] : '36' ?>">
                        </td>
                        <td>
                            <label for="product_width">Width</label><br>
                            <input type="number" min="1" name="product_width" value="<?= isset($_POST['edit']) ? $product['tv_width'] : '20' ?>">
                        </td>
                        <td>
                            <label for="product_height">Height</label><br>
                            <input type="number" min="1" name="product_height" value="<?= isset($_POST['edit']) ? $product['tv_height'] : '20' ?>">
                        </td>
                        <td>
                            <label for="product_depth">Depth</label><br>
                            <input type="number" min="1" name="product_depth" value="<?= isset($_POST['edit']) ? $product['tv_depth'] : '5' ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="product_price">Price</label><br>
                            <input type="number" min="1" name="product_price" step="0.01" value="<?= isset($_POST['edit']) ? $product['price'] : '' ?>">
                        </td>
                        <td>
                            <label for="product_stock">Stock</label><br>
                            <input type="number" min="1" name="product_stock" value="<?= isset($_POST['edit']) ? $product['stock'] : '1' ?>">
                        </td>
                    </tr>
                </table>
            </form>

            <!-- Product list -->
            <h3><i class="fas fa-list fa-sm"></i> Products</h3>

            <form action="admin-products.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Manufacturer</th>
                            <th>Screen Size</th>
                            <th>Dimensions</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)) { ?>
                            <tr class="empty">
                                <td colspan="8">There are no existing products</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($products as $product) { ?>
                                <tr>
                                    <td>
                                        <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['tv_name'] ?>">
                                    </td>
                                    <td><?= $product['tv_name']?></td>
                                    <td><?= $product['manufacturer'] ?></td>
                                    <td><?= $product['screen_size'] ?>"</td>
                                    <td><?= $product['tv_width'] ?>" x <?= $product['tv_height'] ?>" x <?= $product['tv_depth'] ?></td>
                                    <td>$<?= number_format($product['price'], 2) ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td>
                                        <button class="fa-btn" type="submit" name="edit" value="<?= $product['uuid'] ?>">
                                            <i class="fas fa-edit fa-lg"></i>
                                        </button>
                                        <button class="fa-btn" type="submit" name="delete" value="<?= $product['uuid'] ?>">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </main>

        <?php
            include('includes/footer.php');
        ?>
    </body>
</html>