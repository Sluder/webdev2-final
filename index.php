<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Shop';
        $description = 'Shop ACME\'s latest products';

        include('includes/header.php');

        $products = getProducts($db);

        if (isset($_POST['product_id'])) {
            if (isset($_SESSION['current_user'])) {
                $is_success = addToCart($db, $_SESSION['current_user'], $_POST['product_id']);
            }
        }
    ?>

    <body>
        <?php
            $page = 'index';
            include('includes/navigation.php');
        ?>

        <main>
            <h3><i class="fa fa-tags fa-sm"></i> Shop</h3>

            <!-- Handle messages -->
            <?php if (isset($_POST['product_id'])) { ?>
                <?php if (!isset($_SESSION['current_user'])) { ?>
                    <p class="message fail">You must be logged in to add items to your cart</p>

                <?php } else if ($is_success) { ?>
                    <p class="message success">Successfully added <i><?= $_POST['product_name'] ?></i> to cart</p>

                <?php } else { ?>
                    <p class="message fail"><i><?= $_POST['product_name'] ?></i> was unable to be added to cart</p>
                <?php } ?>
            <?php } ?>

            <!-- Product list -->
            <?php foreach ($products as $product) { ?>
                <figure>
                    <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['tv_name'] ?>">
                    <figcaption>
                        <p><b><?= $product['screen_size'] ?>" <?= $product['tv_name'] ?></b></p>
                        <p><?= $product['tv_width'] ?>" x <?= $product['tv_height'] ?>" x <?= $product['tv_depth'] ?>"</p>
                        <p><b>$<?= number_format($product['price'], 2) ?></b></p>

                        <form action="index.php" method="post">
                            <input type="hidden" name="product_id" value="<?= $product['uuid'] ?>">
                            <input type="hidden" name="product_name" value="<?= $product['tv_name'] ?>">

                            <button>Add to cart</button>
                        </form>
                    </figcaption>
                </figure>
            <?php } ?>
        </main>

        <?php
            include('includes/footer.php');
        ?>
    </body>
</html>     