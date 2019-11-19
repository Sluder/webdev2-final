<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Orders';
        $description = 'Look at your order history';

        include('includes/header.php');

        $order_history = getOrderHistory($db, $_SESSION['current_user']);

        // Cluster order products into orders
        $orders = [];
        foreach ($order_history as $order) {
            if (!array_key_exists($order['order_id'], $orders)) {
                $orders[$order['order_id']] = [];
            }

            array_push($orders[$order['order_id']], $order);
        }

        // Sort formatted orders by date
        usort($orders, function($a, $b) {
            return strtotime($a[0]['date_time']) - strtotime($b[0]['date_time']);
        });
    ?>

    <body>
        <?php
            $page = 'orders';
            include('includes/navigation.php');

            // Redirect to user if this page is not allowed to be accessed
            if (!isset($_SESSION['current_user'])) {
                header("Location: index.php");
            }
        ?>

        <main>
            <h3><i class="fas fa-history fa-sm"></i> Order History</h3>

            <!-- List all orders -->
            <?php if (empty($orders)) { ?>
                <table>
                    <tr class="empty">
                        <td>You have no past orders</td>
                    </tr>
                </table>
            <?php } else { ?>
                <?php foreach ($orders as $order_products) { ?>
                    <table class="order-table">
                        <tr>
                            <th colspan="5"><?= date("M d, Y g:i a", strtotime($order_products[0]['date_time'])) ?></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Product Name</th>
                            <th>Dimensions</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>

                        <!-- List all products attached to this order -->
                        <?php $order_total = 0; ?>
                        <?php foreach ($order_products as $product) { ?>
                            <tr>
                                <td>
                                    <img src="img/<?= $product['uuid'] ?>.png" alt="<?= $product['tv_name'] ?>">
                                </td>
                                <td><?= $product['screen_size'] ?>" <?= $product['tv_name'] ?></td>
                                <td><?= $product['tv_width'] ?>" x <?= $product['tv_height'] ?>" x <?= $product['tv_depth'] ?>"</td>
                                <td><?= $product['quantity']?></td>
                                <td>$<?= number_format($product['price'], 2) ?></td>
                            </tr>
                            <?php $order_total += $product['price']; ?>
                        <?php } ?>
                    </table>

                    <!-- Display promo if this order has one -->
                    <?php if ($order_products[0]['promo_code']) { ?>
                        <?php $promo = getPromo($db, $order_products[0]['promo_code']); ?>
                        <p class="total"><b>Total:</b> $<?= number_format($order_total - ($order_total * ($promo['percent_off'] / 100)), 2) ?> (<?= $promo['percent_off'] ?>% off)</p>

                    <?php } else {?>
                        <p class="total"><b>Total:</b> $<?= number_format($order_total, 2) ?></p>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </main>

        <?php
            include('includes/footer.php');
        ?>
    </body>
</html>