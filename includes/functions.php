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
 * Gets user information for a user
 *
 * @param $db: DB connection
 * @param $username: Username of user to get information for
 */
function getUserInformation($db, $username)
{
    $statement = $db->prepare("SELECT * FROM user_information where username = :username");
    $statement->bindValue(':username', $username);

    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    return $result;
}

/**
 * Updates user information for a user
 *
 * @param $db: DB connection
 * @param $username: Username of user to get information for
 * @param $request: Array of date to update product with
 */
function updateUserInformation($db, $username, $request)
{
    $statement = $db->prepare("INSERT INTO user_information (username, name, email, phone, shipping_street, shipping_city, shipping_zipcode) VALUES(:username, :name, :email,  :phone, :shipping_street, :shipping_city, :shipping_zipcode) ON DUPLICATE KEY UPDATE name = :name, email = :email,  phone = :phone, shipping_street = :shipping_street, shipping_city = :shipping_city, shipping_zipcode = :shipping_zipcode");
    $statement->bindValue(':username',  $username);
    $statement->bindValue(':name',  $request['name']);
    $statement->bindValue(':email',  $request['email']);
    $statement->bindValue(':phone',  $request['phone']);
    $statement->bindValue(':shipping_street',  $request['street']);
    $statement->bindValue(':shipping_city',  $request['city']);
    $statement->bindValue(':shipping_zipcode',  $request['zipcode']);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully updated your information" : "Unable to update your information"
    ];
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
 * @param bool $include_empty: Whether to include products with no stock
 */
function getProducts($db, $include_empty = false)
{
    if ($include_empty) {
        $statement = $db->prepare("SELECT * FROM products where is_deleted = 0");
    } else {
        $statement = $db->prepare("SELECT * FROM products where stock > 0 AND is_deleted = 0");
    }

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}

/**
 * Add a new product
 *
 * @param $db: DB connection
 * @param $request: Array of date to update product with
 * @param $file: File to upload from $_FILES
 */
function createProduct($db, $request, $file)
{
    $uuid = rand(0, 99999);

    $file_success = _saveImg($uuid, $file);

    if (!$file_success) {
        return [
            'success' => false,
            'message' => 'Unable to upload product image'
        ];
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

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully added \"{$request['product_name']}\"" : "Unable to add \"{$request['product_name']}\""
    ];
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

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully updated \"{$request['product_name']}\"" : "Unable to update \"{$request['product_name']}\""
    ];
}

/**
 * Soft-delete a product
 *
 * @param $db: DB connection
 * @param $product_uuid: UUID of product to soft-delete
 */
function deleteProduct($db, $product_uuid)
{
    $statement = $db->prepare("UPDATE products set is_deleted = 1 WHERE uuid = :uuid;");
    $statement->bindValue(':uuid',  $product_uuid);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully deleted your product" : "Unable to delete your product"
    ];
}

/**
 * Gets the most ordered product
 *
 * @param $db: DB connection
 */
function getMostOrderedProduct($db)
{
    $statement = $db->prepare("SELECT product_id FROM order_products GROUP BY product_id ORDER BY COUNT(*) DESC LIMIT 1");

    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    return $result;
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

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully added to your cart" : "Unable to add product to your cart"
    ];
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

            return [
                'success' => false,
                'message' => "Unable to update your cart"
            ];
        }
        $statement->closeCursor();
    }

    return [
        'success' => true,
        'message' => "Successfully updated your cart"
    ];
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

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully removed product from your cart" : "Unable to remove product from your cart"
    ];
}

/**
 * Places a new order
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 * @param $promo_code: Promo code to apply to the cart
 */
function placeOrder($db, $username, $promo_code)
{
    // Ensure user has shipping information
    if (empty(getUserInformation($db, $username))) {
        return [
            'success' => false,
            'message' => 'Please fill out your shipping information in your settings before ordering'
        ];
    }

    // Create order
    $order_id = rand(0, 99999);

    $statement = $db->prepare("INSERT INTO orders (order_id, username, promo_code, date_time) VALUES (:order_id, :username, :promo_code, :date_time)");
    $statement->bindValue(':order_id', $order_id);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':promo_code', $promo_code);
    $statement->bindValue(':date_time', date("Y-m-d H:i:s"));

    if (!$statement->execute()) {
        return [
            'success' => false,
            'message' => "There was an issue creating your order"
        ];
    }

    $cart_products = getCart($db, $username);

    // Add all products from user cart to this order
    foreach ($cart_products as $product) {
        $statement = $db->prepare("INSERT INTO order_products (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
        $statement->bindValue('order_id', $order_id);
        $statement->bindValue(':product_id', $product['uuid']);
        $statement->bindValue(':quantity', $product['quantity']);

        $new_stock = $product['stock'] - $product['quantity'];

        // Check if query ran successfully & there is enough stock for this order
        if (!$statement->execute() || $new_stock < 0) {
            deleteOrder($db, $order_id);

            return [
                'success' => false,
                'message' => "'{$product['tv_name']}' only has {$product['stock']} in stock for ordering"
            ];
        // Checks if an admin has deleted this product while in this cart
        } else if ($product['is_deleted']) {
            return [
                'success' => false,
                'message' => "'{$product['tv_name']}' has been removed for ordering"
            ];
        } else {
            $statement = $db->prepare("UPDATE products SET stock = :stock WHERE uuid = :product_id");
            $statement->bindValue(':stock', $new_stock);
            $statement->bindValue(':product_id', $product['uuid']);

            $statement->execute();
        }

        $statement->closeCursor();
    }

    // Remove all products from the cart if order was successful
    foreach ($cart_products as $product) {
        removeFromCart($db, $username, $product['uuid']);
    }

    $statement->closeCursor();

    return [
        'success' => true,
        'message' => "Successfully placed your order"
    ];
}

/**
 * Gets all past orders for a user
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 */
function getOrderHistory($db, $username)
{
    $statement = $db->prepare("SELECT * FROM orders INNER JOIN order_products ON order_products.order_id = orders.order_id INNER JOIN products ON order_products.product_id = products.uuid WHERE orders.username = :username ORDER BY orders.order_id");
    $statement->bindValue(':username', $username);

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}

/**
 * Gets all products attached to an order
 *
 * @param $db: DB connection
 * @param $order_uuid: Unique ID for an order
 */
function getOrderProducts($db, $order_uuid)
{
    $statement = $db->prepare("SELECT * FROM order_products WHERE order_id = :order_id");
    $statement->bindValue(':order_id', $order_uuid);

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}

/**
 * Deletes an order (Only called if there was an issue creating the order)
 *
 * @param $db: DB connection
 * @param $order_uuid: Unique ID for an order
 */
function deleteOrder($db, $order_uuid)
{
    // Delete products from the order
    $statement = $db->prepare("DELETE FROM order_products WHERE order_id = :order_id");
    $statement->bindValue(':order_id', $order_uuid);

    $statement->execute();

    // Delete the order
    $statement = $db->prepare("DELETE FROM orders WHERE order_id = :order_id");
    $statement->bindValue(':order_id', $order_uuid);

    $statement->execute();
    $statement->closeCursor();
}

/**
 * Gets all valid promo codes
 *
 * @param $db: DB connection
 */
function getPromos($db)
{
    $statement = $db->prepare("SELECT * FROM promo_codes WHERE is_deleted = 0");

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}

/**
 * Gets one specific promo code
 *
 * @param $db: DB connection
 * @param $code: Actual code from a promo
 */
function getPromo($db, $code)
{
    $statement = $db->prepare("SELECT * FROM promo_codes WHERE code = :code");
    $statement->bindValue(':code', $code);

    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();

    return $results;
}

/**
 * Create a new promo code for customers to use
 *
 * @param $db: DB connection
 * @param $request: Array of date to update product with
 */
function createPromo($db, $request)
{
    $statement = $db->prepare("INSERT INTO promo_codes (code, percent_off, is_deleted) VALUES (:code, :percent_off, 0)");
    $statement->bindValue(':code', $request['code']);
    $statement->bindValue(':percent_off', $request['percent-off']);

    $is_success = $statement->execute();
    $statement->fetchAll();
    $statement->closeCursor();

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully created promo code '{$request['code']}' for {$request['percent-off']}% off" : "Unable to create promo code"
    ];
}

/**
 * Soft-delete a promo code
 *
 * @param $db: DB connection
 * @param $code: Actual code from a promo
 */
function deletePromo($db, $code)
{
    $statement = $db->prepare("UPDATE promo_codes set is_deleted = 1 WHERE code = :code;");
    $statement->bindValue(':code',  $code);

    $is_success = $statement->execute();
    $statement->closeCursor();

    return [
        'success' => $is_success,
        'message' => $is_success ? "Successfully deleted promo code '{$code}'" : "Unable to delete promo code"
    ];
}

/**
 * Checks if a provided promo code valid
 *
 * @param $db: DB connection
 * @param $code: Code supplied from user to apply
 */
function applyPromo($db, $code)
{
    $statement = $db->prepare("SELECT * FROM promo_codes WHERE code = :code AND is_deleted = 0");
    $statement->bindValue(':code', $code);

    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    return [
        'success' => is_array($result),
        'message' => is_array($result) ? "Successfully applied promo code to cart" : "You entered an invalid promo code",
        'percent_off' => is_array($result) ? intval($result['percent_off']) : 0
    ];
}