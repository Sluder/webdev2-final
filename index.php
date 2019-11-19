<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Shop';
        $description = 'Shop ACME\'s latest products';

        include('includes/header.php');

        $products = getProducts($db);

        if (isset($_POST['product_id'])) {
            if (isset($_SESSION['current_user'])) {
                $response = addToCart($db, $_SESSION['current_user'], $_POST['product_id']);

            } else {
                $response = [
                    'success' => false,
                    'message' => 'You need to be logged in to add products to your cart'
                ];
            }
        }

        $most_ordered = getMostOrderedProduct($db)['product_id'];
    ?>

    <body>
        <?php
            $page = 'index';
            include('includes/navigation.php');
        ?>

        <main>
            <h3><i class="fa fa-tags fa-sm"></i> Shop</h3>

            <!-- Handle messages -->
            <?php if (isset($response)) { ?>
                <p class="message <?= $response['success'] ? 'success' : 'fail' ?>"><?= $response['message'] ?></p>
            <?php } ?>

            <!-- Product list -->
            <?php foreach ($products as $product) { ?>
                <figure>
                    <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['tv_name'] ?>">
                    <figcaption>
                        <?php if (isset($most_ordered) && $product['uuid'] === $most_ordered) { ?>
                            <p class="most-popular"><i class="fas fa-fire"></i> Most Popular</p>
                        <?php } ?>

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