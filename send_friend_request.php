<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) exit;
$me = $_SESSION['user_id'];
$target = $_GET['id'];

$stmt = $pdo->prepare("INSERT IGNORE INTO friend_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
$stmt->execute([$me, $target]);

header('Location: home.php');
?>
