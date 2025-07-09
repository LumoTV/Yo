<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) exit;
$me = $_SESSION['user_id'];
$request_id = $_GET['id'];

// Get sender ID
$stmt = $pdo->prepare("SELECT sender_id FROM friend_requests WHERE id = ? AND receiver_id = ?");
$stmt->execute([$request_id, $me]);
$request = $stmt->fetch();

if ($request) {
    $sender = $request['sender_id'];

    // Marquer comme acceptée
    $pdo->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?")->execute([$request_id]);

    // Ajouter amitié bidirectionnelle
    $pdo->prepare("INSERT IGNORE INTO friends (user_id, friend_id) VALUES (?, ?), (?, ?)")
        ->execute([$me, $sender, $sender, $me]);
}

header('Location: home.php');
?>
