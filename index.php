<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ACME | Home</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/normalize.css"/>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>

        <?php
            require_once('inc/open_db.php');
            include('inc/functions.php');

            $products = getProducts($db);
        ?>
    </head>
    <body>
        <header>
            <a href="index.php" class="active">Home</a>
            <a href="shop.php">Shop</a>
            <a href="cart.php">Cart</a>
        </header>

        <main>
            <?php foreach ($products as $product) { ?>
                <figure>
                    <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['name'] ?>">
                    <h4><?= $product['name'] ?></h4>

                    <figcaption>
                        <p>$<?= number_format($product['price'], 2) ?></p>

<!--                        <form action="index.php" method="post">-->
<!--                            <input type="hidden" name="isbn" value="--><?//= $isbn ?><!--">-->
<!---->
<!--                            <button>Add to cart</button>-->
<!--                        </form>-->
                    </figcaption>
                </figure>
            <?php } ?>
        </main>

        <footer>
            Zachary Sluder – CS3800 Assignment #3 - Fall 2019
        </footer>
    </body>
</html>     