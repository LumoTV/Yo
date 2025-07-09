<?php
require 'config.php';

$message = '';
$isSuccess = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    try {
        $stmt->execute([$username, $password]);
        $message = "✅ Inscription réussie. <a href='login.php'>Connecte-toi</a>";
        $isSuccess = true;
    } catch (Exception $e) {
        $message = "❌ Nom d'utilisateur déjà pris.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Yo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Créer un compte</h1>

    <?php if ($message): ?>
        <div class="<?= $isSuccess ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input name="username" placeholder="Nom d'utilisateur" required><br>
        <input name="password" type="password" placeholder="Mot de passe" required><br>
        <button type="submit">S'inscrire</button>
    </form>

    <p><a href="index.php">Retour à l'accueil</a></p>
</div>
</body>
</html>
