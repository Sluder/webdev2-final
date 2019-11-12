<?php

/**
 * Gets a specific user
 *
 * @param $db: DB connection
 * @param $username: Username of user to get
 */
function getUser($db, $username)
{
    $statement = $db->prepare("SELECT * FROM users where username = :username");
    $statement->bindValue(':username', $username);

    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    return $result;
}

/**
 * Gets a specific user
 *
 * @param $db: DB connection
 * @param $product_uuid: UUID of product to get
 */
function getProduct($db, $product_uuid)
{
    $statement = $db->prepare("SELECT * FROM products where uuid = :product_uuid");
    $statement->bindValue(':product_uuid', $product_uuid);

    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    return $result;
}

/**
 * Gets all products available to buy
 *
 * @param $db: DB connection
 */
function getProducts($db)
{
    $statement = $db->prepare("SELECT * FROM products where stock > 0 AND is_deleted = 0");

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}

/**
 * Add a new product
 *
 * @param $db : DB connection
 * @param $request : Array of date to update product with
 * @param $file: File to upload from $_FILES
 */
function addProduct($db, $request, $file)
{
    $uuid = rand(0, 99999);

    $file_success = _saveImg($uuid, $file);

    if (!$file_success) {
        return false;
    }

    $statement = $db->prepare("INSERT INTO products (uuid, tv_name, manufacturer, price, screen_size, tv_width, tv_height, tv_depth, stock, is_deleted) VALUES (:uuid, :product_name, :manufacturer, :price, :screen_size, :width, :height, :depth, :stock, 0);");
    $statement->bindValue(':uuid',  $uuid);
    $statement->bindValue(':product_name',  $request['product_name']);
    $statement->bindValue(':manufacturer',  $request['product_manufacturer']);
    $statement->bindValue(':price',  $request['product_price']);
    $statement->bindValue(':screen_size',  $request['screen_size']);
    $statement->bindValue(':width',  $request['product_width']);
    $statement->bindValue(':height',  $request['product_height']);
    $statement->bindValue(':depth',  $request['product_depth']);
    $statement->bindValue(':stock',  $request['product_stock']);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return $is_success && $file_success;
}

/**
 * Update existing product
 *
 * @param $db: DB connection
 * @param $request: Array of date to update product with
 */
function updateProduct($db, $request)
{
    $statement = $db->prepare("UPDATE products set tv_name = :product_name, manufacturer = :manufacturer, price = :price, screen_size = :screen_size, tv_width = :width, tv_height = :height, tv_depth = :depth, stock = :stock WHERE uuid = :uuid;");
    $statement->bindValue(':uuid',  $request['product_uuid']);
    $statement->bindValue(':product_name',  $request['product_name']);
    $statement->bindValue(':manufacturer',  $request['product_manufacturer']);
    $statement->bindValue(':price',  $request['product_price']);
    $statement->bindValue(':screen_size',  $request['screen_size']);
    $statement->bindValue(':width',  $request['product_width']);
    $statement->bindValue(':height',  $request['product_height']);
    $statement->bindValue(':depth',  $request['product_depth']);
    $statement->bindValue(':stock',  $request['product_stock']);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return $is_success;
}

/**
 * @param $db: DB connection
 * @param $product_uuid: UUID of product to soft-delete
 */
function deleteProduct($db, $product_uuid)
{
    $statement = $db->prepare("UPDATE products set is_deleted = 1 WHERE uuid = :uuid;");
    $statement->bindValue(':uuid',  $product_uuid);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return $is_success;
}

/**
 * Saves images for products
 *
 * @param $uuid: Generated product UUID
 * @param $file: File to upload from $_FILES
 */
function _saveImg($uuid, $file)
{
    $target_file = "img/{$uuid}.png";

    return move_uploaded_file($file['tmp_name'], $target_file);
}

/**
 * Gets all products in users cart
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 */
function getCart($db, $username)
{
    $statement = $db->prepare("SELECT * FROM cart INNER JOIN products ON cart.product_id = products.uuid WHERE cart.username = :username");
    $statement->bindValue(':username', $username);

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}

/**
 * Adds a product to the cart. Updates cart if already exists
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 * @param $product_uuid: UUID of product adding to cart
 */
function addToCart($db, $username, $product_uuid)
{
    $statement = $db->prepare("INSERT INTO cart (username, product_id, quantity) VALUES(:username, :product_id, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    $statement->bindValue(':username',  $username);
    $statement->bindValue(':product_id', $product_uuid);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return $is_success;
}

/**
 * Updates the quantity of a cart product
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 * @param $product_ids: UUID of product adding to cart
 * @param $quantities: Quantity to update cart product
 */
function updateCart($db, $username, $product_ids, $quantities)
{
    foreach ($product_ids as $key => $uuid) {
        $statement = $db->prepare("UPDATE cart SET quantity = :quantity WHERE username = :username AND product_id = :product_id");
        $statement->bindValue(':quantity', $quantities[$key]);
        $statement->bindValue(':username',  $username);
        $statement->bindValue(':product_id', $uuid);

        if (!$statement->execute()) {
            $statement->closeCursor();
            return false;
        }
        $statement->closeCursor();
    }

    return true;
}

/**
 * Removes a product from the cart
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 * @param $product_uuid: UUID of product removing from cart
 */
function removeFromCart($db, $username, $product_uuid)
{
    $statement = $db->prepare("DELETE FROM cart WHERE username = :username AND product_id = :product_uuid");
    $statement->bindValue(':username', $username);
    $statement->bindValue(':product_uuid', $product_uuid);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return $is_success;
}

/**
 * Places a new order
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 * @param $products: List of products to place order for
 */
function placeOrder($db, $username, $products)
{
    $order_id = rand(0, 99999);

    $statement = $db->prepare("INSERT INTO orders (order_id, username, date_time) VALUES(:order_id, :username, :date_time)");
    $statement->bindValue(':order_id', $order_id);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':date_time', date("Y-m-d H:i:s"));

    $statement->execute();

    foreach ($products as $product) {
        $statement = $db->prepare("INSERT INTO order_products (order_id, product_id, quantity) VALUES(:order_id, :product_id, :quantity)");
        $statement->bindValue('order_id', $order_id);
        $statement->bindValue(':product_id', $product['uuid']);
        $statement->bindValue(':quantity', $product['quantity']);

        $statement->execute();
    }
}