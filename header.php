<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Collection Aur'art - Association d'Art</title>
</head>
<body>
<header class="site-header">
    <div class="container">
        <h1 class="logo"><a href="index.php">Collection Aur'art</a></h1>
        <nav class="main-nav">
            <a href="index.php">Accueil</a>
            <a href="articles.php">Catalogue</a>
            <a href="about.php">À propos</a>
            <?php if(!empty($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Mon espace</a>
                <a href="logout.php">Se déconnecter</a>
            <?php else: ?>
                <a href="login.php">Se connecter</a>
                <a href="register.php">S'inscrire</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">