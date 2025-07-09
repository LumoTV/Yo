<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) die();
$me = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$_POST['username']]);
$friend = $stmt->fetch();
if ($friend) {
    $stmt = $pdo->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?, ?)");
    $stmt->execute([$me, $friend['id']]);
}
header('Location: home.php');
?>
