<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$me = $_SESSION['user_id'];
$yo_id = $_GET['id'] ?? null;

if ($yo_id) {
    // S'assurer que la notification Yo appartient bien à l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT id FROM yos WHERE id = ? AND receiver_id = ?");
    $stmt->execute([$yo_id, $me]);
    if ($stmt->fetch()) {
        $del = $pdo->prepare("DELETE FROM yos WHERE id = ?");
        $del->execute([$yo_id]);
    }
}

header('Location: home.php');
exit;
