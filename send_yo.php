<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) die();
$me = $_SESSION['user_id'];
$friend_id = $_GET['id'];
$stmt = $pdo->prepare("INSERT INTO yos (sender_id, receiver_id) VALUES (?, ?)");
$stmt->execute([$me, $friend_id]);
header('Location: home.php');
?>