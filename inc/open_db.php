<?php

$servername = 'localhost';
$username = 'sluder';
$password = 'K8zs6';
$dbname = 'sluder';

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

} catch (PDOException $e) {
    echo $e->getMessage();

    exit();
}
