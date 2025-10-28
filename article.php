<?php
require 'db.php';
include 'header.php';


// Récupération du slug depuis l'URL
$slug = $_GET['slug'] ?? '';

if (!$slug) {
    echo '<div class="card"><p>Article introuvable.</p></div>';
    include 'footer.php';
    exit;
}

// Requête sécurisée pour récupérer l'article avec l'auteur
$res = q(
    "SELECT a.*, u.username 
     FROM articles a 
     LEFT JOIN users u ON u.id = a.author_id 
     WHERE a.slug = ?",
    's',
    [$slug]
);

$article = $res ? $res->fetch_assoc() : null;

if (!$article) {
    echo '<div class="card"><p>Article introuvable.</p></div>';
    include 'footer.php';
    exit;
}
?>

<article class="card article-detail">
    <h2><?= htmlspecialchars($article['title']) ?></h2>
    <p class="article-meta">
        Par <?= htmlspecialchars($article['username'] ?? 'Anonyme') ?> — 
        <?= htmlspecialchars(date('d/m/Y', strtotime($article['created_at']))) ?>
    </p>

    <?php if (!empty($article['image']) && file_exists($article['image'])): ?>
        <img src="<?= htmlspecialchars($article['image']) ?>" 
             alt="<?= htmlspecialchars($article['title']) ?>" 
             class="article-image">
    <?php endif; ?>

    <div class="article-content">
        <?= nl2br(htmlspecialchars($article['content'])) ?>
    </div>
    
    <p><a href="articles.php" class="btn-back"> Retour au catalogue</a></p>
</article>

<?php include 'footer.php'; ?>

<!-- Section commentaires -->



<section class="card" style="margin-top: 30px;">
    <h3>Commentaires</h3>

    <?php
    $comments = q(
        "SELECT c.*, u.username 
         FROM comments c 
         LEFT JOIN users u ON c.user_id = u.id 
         WHERE c.article_id = ? 
         ORDER BY c.created_at DESC",
        'i',
        [$article['id']]
    );

    if ($comments && $comments->num_rows > 0) {
        while ($c = $comments->fetch_assoc()) {
            echo '<div style="border-bottom:1px solid #ddd; padding:10px 0;">';
            echo '<strong>' . htmlspecialchars($c['username'] ?? 'Anonyme') . '</strong> ';
            echo '<small>(' . htmlspecialchars(date('d/m/Y H:i', strtotime($c['created_at']))) . ')</small>';
            echo '<p>' . nl2br(htmlspecialchars($c['content'])) . '</p>';

            // Si le commentaire appartient à l'utilisateur connecté, on affiche le lien "Supprimer"
            if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] == $c['user_id']) {
                echo '<a href="delete_comment.php?id=' . $c['id'] . '&slug=' . urlencode($article['slug']) . '" 
                        onclick="return confirm(\'Voulez-vous vraiment supprimer ce commentaire ?\')"
                        style="color:red; font-size:0.9em;">🗑 Supprimer</a>';
            }

            echo '</div>';
        }
    } else {
        echo '<p>Aucun commentaire pour le moment.</p>';
    }
    ?>

    <!-- Formulaire d’ajout de commentaire -->
    <?php if (!empty($_SESSION['user_id'])): ?>
        <form method="post" action="add_comment.php" style="margin-top:20px;">
            <input type="hidden" name="article_id" value="<?= htmlspecialchars($article['id']) ?>">
            <input type="hidden" name="slug" value="<?= htmlspecialchars($article['slug']) ?>">
            <textarea name="content" rows="3" required placeholder="Écrivez un commentaire..." style="width:100%; padding:8px;"></textarea><br>
            <button type="submit" style="margin-top:5px;">Envoyer</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Connectez-vous</a> pour laisser un commentaire.</p>
    <?php endif; ?>
</section>
