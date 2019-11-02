<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACME | Shop</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

        <?php
            require_once('inc/open_db.php');
            include('inc/functions.php');

            $products = getProducts($db);

            if (isset($_POST['product_id'])) {
                addToCart($db, 'sluder', $_POST['product_id']);
            }
        ?>
    </head>
    <body>
        <header>
            <p>ACME</p>
            <a href="index.php">Home</a>
            <a href="shop.php" class="active">Shop</a>
            <a href="cart.php">Cart</a>

            <a href="">Login</a>
        </header>

        <main>
            <h2><i class="fa fa-tags"></i> Shop</h2>

            <?php if (isset($_POST['product_id'])) { ?>
                <p class="message">Successfully added <i><?= $_POST['product_name'] ?></i> to cart</p>
            <?php } ?>

            <?php foreach ($products as $product) { ?>
                <figure>
                    <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['name'] ?>">
                    <h4><?= $product['name'] ?></h4>

                    <figcaption>
                        <p>$<?= number_format($product['price'], 2) ?></p>

                        <form action="shop.php" method="post">
                            <input type="hidden" name="product_id" value="<?= $product['uuid'] ?>">
                            <input type="hidden" name="product_name" value="<?= $product['name'] ?>">

                            <button>Add to cart</button>
                        </form>
                    </figcaption>
                </figure>
            <?php } ?>
        </main>

        <footer>
            Zachary Sluder – CS3800 Assignment #3 - Fall 2019
        </footer>
    </body>
</html>     