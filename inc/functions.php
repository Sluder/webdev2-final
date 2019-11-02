<?php

/**
 * Gets all products available to buy
 *
 * @param $db: DB connection
 */
function getProducts($db)
{
    $query = "SELECT * FROM products where stock > 0";
    $statement = $db->prepare($query);

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
    $query = "INSERT INTO cart (username, product_id, quantity) VALUES(:username, :product_id, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':username',  $username);
    $statement->bindValue(':product_id', $product_uuid);

    $statement->execute();
    $statement->closeCursor();
}

/**
 * Updates the quantity of a cart product
 *
 * @param $db: DB connection
 * @param $username: Username of logged-in user
 * @param $product_uuid: UUID of product adding to cart
 * @param $quantity: Quantity to update cart product
 */
function updateQuantity($db, $username, $product_uuid, $quantity)
{
    $query = "UPDATE cart SET quantity = :quantity WHERE username = :username AND product_id = :product_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':quantity', $quantity);
    $statement->bindValue(':username',  $username);
    $statement->bindValue(':product_id', $product_uuid);

    $statement->execute();
    $statement->closeCursor();
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
    $query = "DELETE FROM cart WHERE username = :username AND product_id = :product_uuid";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':product_uuid', $product_uuid);

    $statement->execute();
    $statement->closeCursor();
}

/**
 * Gets all products in users cart
 *
 * @param $db: DB connection
 */
function getCart($db)
{
    $query = "SELECT * FROM cart INNER JOIN products ON cart.product_id = products.uuid WHERE cart.username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', 'sluder');

    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    return $results;
}