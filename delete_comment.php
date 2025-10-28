<?php
require 'db.php';
session_start();

// Vérifie si l'utilisateur est connecté
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['slug'])) {
    header('Location: index.php');
    exit;
}

$comment_id = intval($_GET['id']);
$slug = $_GET['slug'];
$user_id = $_SESSION['user_id'];

// Vérifie que le commentaire appartient bien à l'utilisateur
$res = q("SELECT * FROM comments WHERE id = ? AND user_id = ?", 'ii', [$comment_id, $user_id]);

if ($res && $res->num_rows === 1) {
    q("DELETE FROM comments WHERE id = ?", 'i', [$comment_id]);
}

header('Location: article.php?slug=' . urlencode($slug));
exit;
?>
