<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Cart';
        $description = 'View your cart and checkout';

        include('includes/header.php');

        if (isset($_SESSION['current_user'])) {
            if (isset($_POST['update'])) {
                $response = updateCart($db, $_SESSION['current_user'], $_POST['product_ids'], $_POST['quantities']);

            } else if (isset($_POST['delete'])) {
                $response = removeFromCart($db, $_SESSION['current_user'], $_POST['delete']);

            } else if (isset($_POST['apply-promo'])) {
                $response = applyPromo($db, $_POST['promo-code']);

            } else if (isset($_POST['complete-order'])) {
                $response = placeOrder($db, $_SESSION['current_user'], $_POST['promo-code']);
            }

            $cart_products = getCart($db, $_SESSION['current_user']);

            $user_information = getUserInformation($db, $_SESSION['current_user']);
        }
    ?>

    <body>
        <?php
            $page = 'cart';
            include('includes/navigation.php');

            // Redirect to user if this page is not allowed to be accessed
            if (!isset($_SESSION['current_user'])) {
                header("Location: index.php");
            }
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
                <?php if (isset($response)) { ?>
                    <p class="message <?= $response['success'] ? 'success' : 'fail' ?>"><?= $response['message'] ?></p>
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
                            <!-- List all products in logged-in users cart -->
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

            <p class="total"><b>Cart Total : $<?= number_format($cart_total, 2) ?></b></p>

            <!-- Show details of the cart -->
            <?php if (!empty($cart_products)) { ?>
                <h3><i class="fas fa-cash-register fa-sm"></i> Complete Order</h3>

                <table class="shipping-table half-table">
                    <tr>
                        <td><b>Subtotal</b></td>
                        <td>$<?= number_format($cart_total, 2) ?></td>
                    </tr>
                    <tr>
                        <td><b>Shipping</b></td>
                        <td>Free One-day</td>
                    </tr>
                    <tr>
                        <td><b>Shipping Address</b></td>
                        <td><?= $user_information['shipping_street'] ?>, <?= $user_information['shipping_city'] ?>, <?= $user_information['shipping_zipcode'] ?></td>
                    </tr>
                    <tr>
                        <td><b>Promo Code</b></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="text" name="promo-code" value="<?= isset($_POST['promo-code']) ? $_POST['promo-code'] : '' ?>">
                                <button type="submit" name="apply-promo">Apply</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Order Total</b></td>
                        <?php $has_promo = (isset($response['percent_off']) && $response['percent_off'] !== 0); ?>
                        <td>
                            $<?= number_format($cart_total - ($has_promo ? ($response['percent_off'] / 100) : 0) * $cart_total, 2) ?>
                            <?= $has_promo ? "({$response['percent_off']}% off)" : '' ?>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Checkout</b></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="promo-code" value="<?= isset($_POST['promo-code']) ? $_POST['promo-code'] : '' ?>">
                                <button type="submit" name="complete-order">Submit Order</button>
                            </form>
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </main>

        <?php
            include('includes/footer.php');
        ?>
    </body>
</html>