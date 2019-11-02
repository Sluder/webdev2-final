<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACME | Cart</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

        <?php
            require_once('inc/open_db.php');
            include('inc/functions.php');

            if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
                updateQuantity($db, 'sluder', $_POST['product_id'], $_POST['quantity']);

            } else if (isset($_POST['product_id'])) {
                removeFromCart($db, 'sluder', $_POST['product_id']);
            }

            $cart_products = getCart($db);
        ?>
    </head>
    <body>
        <header>
            <p>ACME</p>
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="cart.php" class="active">Cart</a>

            <a href="">Login</a>
        </header>

        <main>
            <h2><i class="fa fa-shopping-cart"></i> Shopping Cart</h2>

            <?php if (isset($_POST['product_id']) && isset($_POST['quantity'])) { ?>
                <p class="message">Successfully updated your cart</p>

            <?php } else if (isset($_POST['product_id'])) { ?>
                <p class="message">Successfully removed <i><?= $_POST['product_name'] ?></i> from cart</p>
            <?php } ?>

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
                                    <form action="cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product['uuid'] ?>">
                                        <input type="hidden" name="product_name" value="<?= $product['name'] ?>">

                                        <button class="fa-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['name'] ?>">
                                </td>
                                <td>
                                    <?= $product['manufacturer'] ?> - <?= $product['name'] ?> (<?= $product['tv_width'] ?> x <?= $product['tv_height'] ?> x <?= $product['tv_depth'] ?>)
                                </td>
                                <td>$<?= number_format($product['price'], 2) ?></td>
                                <td>
                                    <form action="cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product['uuid'] ?>">
                                        <input type="number" name="quantity" min="1" value="<?= $product['quantity'] ?>">

                                        <button class="fa-btn">
                                            <i class="fa fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>$<?= number_format($product['quantity'] * $product['price'], 2) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>

            <p class="cart-total"><b>Cart Total : $<?= number_format($cart_total, 2) ?></b></p>
        </main>

        <footer>
            Zachary Sluder – CS3800 Assignment #3 - Fall 2019
        </footer>
    </body>
</html>