<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'], $_POST['content'])) {
    $article_id = intval($_POST['article_id']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'] ?? null;

    if ($content !== '') {
        q(
            "INSERT INTO comments (article_id, user_id, content) VALUES (?, ?, ?)",
            'iis',
            [$article_id, $user_id, $content]
        );
    }

    // Retour à la page de l’article
    header('Location: article.php?slug=' . urlencode($_POST['slug']));
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>