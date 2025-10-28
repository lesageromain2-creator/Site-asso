<?php
require 'db.php';
include 'header.php';

// Vérifier si l'utilisateur est connecté
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Gestion de l'ajout d'un nouvel article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));

    // Gestion de l'image
    $image_path = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    q("INSERT INTO articles (title, slug, content, author_id, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())", 
      'sssis', [$title, $slug, $content, $user_id, $image_path]);

    header('Location: dashboard.php');
    exit;
}

// Récupérer les articles de l'utilisateur
$articles = q("SELECT * FROM articles WHERE author_id = ? ORDER BY created_at DESC", 'i', [$user_id]);

?>

<section class="card">
    <h2>Bienvenue sur votre espace</h2>
    <p>Bonjour <?=htmlspecialchars($_SESSION['username'] ?? 'Utilisateur')?> ! Ici, vous pouvez gérer vos articles.</p>
</section>

<section class="card">
    <h2>Ajouter un nouvel article</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Titre de l'article</label>
            <input type="text" id="title" name="title" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="content">Contenu</label>
            <textarea id="content" name="content" rows="6" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image (facultatif)</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <button type="submit">Publier l'article</button>
    </form>
</section>

<section class="card">
    <h2>Mes articles</h2>
    <?php
    if ($articles && $articles->num_rows) {
        echo '<div class="article-list">';
        while ($row = $articles->fetch_assoc()) {
            echo '<article class="card">';
            if (!empty($row['image']) && file_exists($row['image'])) {
                echo '<img src="'.htmlspecialchars($row['image']).'" alt="'.htmlspecialchars($row['title']).'" style="width:100%; height:150px; object-fit:cover;">';
            }
            echo '<h3>'.htmlspecialchars($row['title']).'</h3>';
            echo '<p>Créé le '.htmlspecialchars(date('d/m/Y', strtotime($row['created_at']))).'</p>';
            echo '<p><a href="article.php?slug='.urlencode($row['slug']).'">Voir</a> | <a href="edit_article.php?id='.$row['id'].'">Modifier</a> | <a href="delete_article.php?id='.$row['id'].'" onclick="return confirm(\'Voulez-vous vraiment supprimer cet article ?\')">Supprimer</a></p>';
            echo '</article>';
        }
        echo '</div>';
    } else {
        echo '<p>Vous n\'avez pas encore publié d\'article.</p>';
    }
    ?>
</section>

<?php include 'footer.php'; ?>