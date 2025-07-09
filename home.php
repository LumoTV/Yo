<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$me = $_SESSION['user_id'];

// Amis
$friends = $pdo->prepare("
    SELECT u.id, u.username
    FROM users u
    JOIN friends f ON u.id = f.friend_id
    WHERE f.user_id = ?
");
$friends->execute([$me]);

// Notifications Yo
$yos = $pdo->prepare("
    SELECT y.id, u.username, y.sent_at
    FROM yos y
    JOIN users u ON y.sender_id = u.id
    WHERE y.receiver_id = ?
    ORDER BY y.sent_at DESC
");
$yos->execute([$me]);


// Demandes d'amis reçues
$requests = $pdo->prepare("
    SELECT f.id, u.username
    FROM friend_requests f
    JOIN users u ON u.id = f.sender_id
    WHERE f.receiver_id = ? AND f.status = 'pending'
");
$requests->execute([$me]);

// Demandes d'amis envoyées (en attente)
$sent_requests = $pdo->prepare("
    SELECT f.id, u.username
    FROM friend_requests f
    JOIN users u ON u.id = f.receiver_id
    WHERE f.sender_id = ? AND f.status = 'pending'
");
$sent_requests->execute([$me]);

// Suggestions d'utilisateurs aléatoires (non amis, non soi-même)
$suggestions = $pdo->prepare("
    SELECT id, username
    FROM users
    WHERE id != ? AND id NOT IN (
        SELECT friend_id FROM friends WHERE user_id = ?
        UNION
        SELECT user_id FROM friends WHERE friend_id = ?
    )
    ORDER BY RAND()
    LIMIT 5
");
$suggestions->execute([$me, $me, $me]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Yo - Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Accueil</h1>
    <a href="logout.php">Se déconnecter</a>

    <h2>Amis</h2>
    <ul>
        <?php foreach ($friends as $f): ?>
            <li><?= htmlspecialchars($f['username']) ?> 
                <a href="send_yo.php?id=<?= $f['id'] ?>">Yo!</a>
            </li>
        <?php endforeach; ?>
    </ul>

        <h2>Notifications Yo</h2>
        <ul>
            <?php foreach ($yos as $yo): ?>
                <li>
                    <?= htmlspecialchars($yo['username']) ?> t'a envoyé un Yo le <?= $yo['sent_at'] ?>
                    <a href="delete_yo.php?id=<?= $yo['id'] ?>" class="delete-yo" title="Supprimer cette notification" onclick="return confirm('Supprimer cette notification ?')">✖</a>
                </li>
            <?php endforeach; ?>
        </ul>


        <h2>Demandes d'amis reçues</h2>
        <ul>
            <?php foreach ($requests as $req): ?>
                <li>
                    <?= htmlspecialchars($req['username']) ?>
                    <a href="accept_friend.php?id=<?= $req['id'] ?>">Accepter</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <h2>Demandes envoyées</h2>
        <ul>
            <?php foreach ($sent_requests as $sent): ?>
                <li>
                    Demande envoyée à <?= htmlspecialchars($sent['username']) ?> (en attente)
                </li>
            <?php endforeach; ?>
        </ul>

    <h2>Fais-toi des amis</h2>
    <ul>
        <?php foreach ($suggestions as $sugg): ?>
            <li>
                <?= htmlspecialchars($sugg['username']) ?>
                <a href="send_friend_request.php?id=<?= $sugg['id'] ?>">Ajouter</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
