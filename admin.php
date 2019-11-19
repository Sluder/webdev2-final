<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Products';
        $description = 'Add new products to your shop';

        include('includes/header.php');

        if (isset($_POST['edit-product'])) {
            $product = getProduct($db, $_POST['edit-product']);

        } else if (isset($_POST['add-product'])) {
            $response = createProduct($db, $_POST, $_FILES['product_img']);

        } else if (isset($_POST['update-product'])) {
            $response = updateProduct($db, $_POST);

        } else if (isset($_POST['delete-product'])) {
            $response = deleteProduct($db, $_POST['delete-product']);

        } else if (isset($_POST['add-promo'])) {
            $response = createPromo($db, $_POST);

        } else if (isset($_POST['delete-promo'])) {
            $response = deletePromo($db, $_POST['delete-promo']);
        }

        $products = getProducts($db, true);
        $promos = getPromos($db);
    ?>

    <body>
        <?php
            $page = 'admin';
            include('includes/navigation.php');

            // Redirect to user if this page is not allowed to be accessed
            if (!isset($_SESSION['current_user']) || (isset($_SESSION['current_user']) && !$user['is_admin'])) {
                header("Location: index.php");
            }
        ?>

        <main>
            <!-- Add/edit product -->
            <form action="admin.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_uuid" value="<?= isset($_POST['edit-product']) ? $product['uuid'] : '' ?>">

                <?php if (isset($_POST['edit-product'])) { ?>
                    <h3>
                        <i class="fas fa-edit fa-sm"></i> Edit Product
                        <button type="submit" name="update-product">Update</button>
                    </h3>
                <?php } else { ?>
                    <h3>
                        <i class="fas fa-plus fa-sm"></i> Add Product
                        <button type="submit" name="add-product">Create</button>
                    </h3>
                <?php } ?>

                <?php if (isset($response)) { ?>
                    <p class="message <?= $response['success'] ? 'success' : 'fail' ?>"><?= $response['message'] ?></p>
                <?php } ?>

                <table class="clear-table">
                    <tr>
                        <td>
                            <label for="product_img">Product Image</label><br>
                            <input type="file" name="product_img">
                        </td>
                        <td>
                            <label for="product_name">Name</label><br>
                            <input type="text" name="product_name" value="<?= isset($_POST['edit-product']) ? $product['tv_name'] : '' ?>">
                        </td>
                        <td>
                            <label for="product_manufacturer">Manufacturer</label><br>
                            <input type="text" name="product_manufacturer" value="<?= isset($_POST['edit-product']) ? $product['manufacturer'] : '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="screen_size">Screen Size</label><br>
                            <input type="number" min="1" name="screen_size" value="<?= isset($_POST['edit-product']) ? $product['screen_size'] : '36' ?>">
                        </td>
                        <td>
                            <label for="product_width">Width</label><br>
                            <input type="number" min="1" name="product_width" value="<?= isset($_POST['edit-product']) ? $product['tv_width'] : '20' ?>">
                        </td>
                        <td>
                            <label for="product_height">Height</label><br>
                            <input type="number" min="1" name="product_height" value="<?= isset($_POST['edit-product']) ? $product['tv_height'] : '20' ?>">
                        </td>
                        <td>
                            <label for="product_depth">Depth</label><br>
                            <input type="number" min="1" name="product_depth" value="<?= isset($_POST['edit-product']) ? $product['tv_depth'] : '5' ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="product_price">Price</label><br>
                            <input type="number" min="1" name="product_price" step="0.01" value="<?= isset($_POST['edit-product']) ? number_format($product['price'], 2) : '' ?>">
                        </td>
                        <td>
                            <label for="product_stock">Stock</label><br>
                            <input type="number" min="1" name="product_stock" value="<?= isset($_POST['edit-product']) ? $product['stock'] : '1' ?>">
                        </td>
                    </tr>
                </table>
            </form>

            <!-- Product list -->
            <h3><i class="fas fa-list fa-sm"></i> Products</h3>

            <form action="admin.php" method="post">
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
                                        <button class="fa-btn" type="submit" name="edit-product" value="<?= $product['uuid'] ?>">
                                            <i class="fas fa-edit fa-lg"></i>
                                        </button>
                                        <button class="fa-btn" type="submit" name="delete-product" value="<?= $product['uuid'] ?>">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </form>

            <!-- Promo codes list -->
            <form action="admin.php" method="post">
                <h3>
                    <i class="fas fa-percentage fa-sm"></i> Promo Codes
                    <button type="submit" name="add-promo">Add</button>
                </h3>

                <table class="clear-table">
                    <tr>
                        <td>
                            <label for="code">Code</label>
                            <input type="text" name="code">
                        </td>
                        <td>
                            <label for="percent-off">Percent Off</label>
                            <input type="number" name="percent-off" min="5" max="100" value="5">
                        </td>
                    </tr>
                </table>
            </form>

            <table class="half-table">
                <tr>
                    <th>Code</th>
                    <th>Percent Off</th>
                    <th></th>
                </tr>
                    <?php if (empty($promos)) { ?>
                        <tr class="empty">
                            <td colspan="3">There were no valid promos found</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($promos as $promo) { ?>
                            <tr>
                                <td><?= $promo['code'] ?></td>
                                <td><?= $promo['percent_off'] ?>%</td>
                                <td>
                                    <form action="admin.php" method="post">
                                        <button class="fa-btn" type="submit" name="delete-promo" value="<?= $promo['code'] ?>">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
            </table>
        </main>

        <?php
            include('includes/footer.php');
        ?>
    </body>
</html>