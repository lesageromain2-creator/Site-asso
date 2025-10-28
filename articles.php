<?php
require 'db.php';
include 'header.php';
?>

<section class="card">
    <h2>Catalogue des articles</h2>

    <?php
    $res = q("SELECT a.id, a.title, a.slug, a.created_at,a.image, u.username 
              FROM articles a 
              LEFT JOIN users u ON u.id=a.author_id 
              ORDER BY a.created_at DESC");

    if ($res && $res->num_rows) {
        echo '<div class="article-list" style="display:flex; flex-wrap:wrap; gap:20px;">';
        
        while ($row = $res->fetch_assoc()) {
            echo '<article class="card" style="width:300px;">';
            
            // Affichage de l'image cliquable
            if (!empty($row['image']) && file_exists($row['image'])) {
                echo '<a href="article.php?slug=' . urlencode($row['slug']) . '">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width:100%; height:200px; object-fit:cover;">';
                echo '</a>';
            }

            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>Par ' . htmlspecialchars($row['username'] ?? 'Anonyme') . ' — ' . htmlspecialchars(date('d/m/Y', strtotime($row['created_at']))) . '</p>';
            echo '<p><a href="article.php?slug=' . urlencode($row['slug']) . '">Lire l\'article →</a></p>';
            echo '</article>';
        }

        echo '</div>';
    } else {
        echo '<p>Aucun article pour l\'instant.</p>';
    }
    ?>
</section>

<?php include 'footer.php'; ?>

