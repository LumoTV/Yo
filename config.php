<?php
$host = 'add_your_host';
$db = 'add_your_db';
$user = 'add_your_user';
$pass = 'add_your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
session_start();
?>
