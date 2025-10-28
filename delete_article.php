<?php
require 'db.php';

// Vérifier que l'utilisateur est connecté
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$article_id = $_GET['id'] ?? null;

if ($article_id) {
    // Récupérer l'article pour supprimer l'image
    $res = q("SELECT image FROM articles WHERE id = ? AND author_id = ?", 'ii', [$article_id, $user_id]);
    $article = $res ? $res->fetch_assoc() : null;

    if ($article) {
        // Supprimer l'image si elle existe
        if (!empty($article['image']) && file_exists($article['image'])) {
            unlink($article['image']);
        }
        // Supprimer l'article
        q("DELETE FROM articles WHERE id = ? AND author_id = ?", 'ii', [$article_id, $user_id]);
    }
}

header('Location: dashboard.php');
exit;
