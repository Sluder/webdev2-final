<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Cart';
        $description = 'View your cart and checkout';

        include('includes/header.php');

        if (isset($_SESSION['current_user'])) {
            if (isset($_POST['update'])) {
                $is_success = updateCart($db, $_SESSION['current_user'], $_POST['product_ids'], $_POST['quantities']);

            } else if (isset($_POST['delete'])) {
                $is_success = removeFromCart($db, $_SESSION['current_user'], $_POST['delete']);
            }

            $cart_products = getCart($db, $_SESSION['current_user']);
        } else {
            $cart_products = [];
        }
    ?>

    <body>
        <?php
            $page = 'cart';
            include('includes/navigation.php');
        ?>

        <main>
            <!-- Made a large form so all quantities can be update at once -->
            <form action="cart.php" method="post">
                <h3>
                    <i class="fa fa-shopping-cart fa-sm"></i> Shopping Cart

                    <?php if (!empty($cart_products)) { ?>
                        <button type="submit" name="update">
                            Update Cart
                        </button>
                    <?php } ?>
                </h3>

                <!-- Handle messages -->
                <?php if (isset($_POST['update']) || isset($_POST['delete'])) { ?>
                    <?php if ($is_success) { ?>
                        <p class="message success">Successfully updated your cart</p>
                    <?php } else { ?>
                        <p class="message fail"><i>Cart was unable to be updated</p>
                    <?php } ?>
                <?php } ?>

                <!-- Cart items list -->
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cart_total = 0; ?>
                        <?php if (empty($cart_products)) { ?>
                            <tr class="empty">
                                <td colspan="6">You have no items in your cart</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($cart_products as $product) { ?>
                                <?php $cart_total += $product['quantity'] * $product['price']; ?>
                                <tr>
                                    <td>
                                        <button class="fa-btn" type="submit" name="delete" value="<?= $product['uuid'] ?>">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['tv_name'] ?>">
                                    </td>
                                    <td>
                                        <?= $product['screen_size'] ?>" <?= $product['tv_name'] ?> (<?= $product['tv_width'] ?>" x <?= $product['tv_height'] ?>" x <?= $product['tv_depth'] ?>")
                                    </td>
                                    <td>$<?= number_format($product['price'], 2) ?></td>
                                    <td>
                                        <input type="hidden" name="product_ids[]" value="<?= $product['uuid'] ?>">
                                        <input type="number" name="quantities[]" min="1" max="<?= $product['stock'] ?>" value="<?= $product['quantity'] ?>">
                                    </td>
                                    <td>$<?= number_format($product['quantity'] * $product['price'], 2) ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </form>

            <p class="cart-total"><b>Cart Total : $<?= number_format($cart_total, 2) ?></b></p>

            <!-- Shipping information -->
            <h3><i class="fa fa-truck fa-sm"></i> Shipping</h3>
        </main>

        <?php
            include('includes/footer.php');
        ?>
    </body>
</html>