<?php
require 'config.php';
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Yo - Bienvenue</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur Yo</h1>
        <a href="register.php">Inscription</a> | <a href="login.php">Connexion</a>
    </div>
</body>
</html>