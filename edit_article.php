<?php
require 'db.php';
include 'header.php';

// Vérifier que l'utilisateur est connecté
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$article_id = $_GET['id'] ?? null;

if (!$article_id) {
    echo "<p>Article non trouvé.</p>";
    include 'footer.php';
    exit;
}

// Récupérer l'article
$res = q("SELECT * FROM articles WHERE id = ? AND author_id = ?", 'ii', [$article_id, $user_id]);
$article = $res ? $res->fetch_assoc() : null;
if (!$article) {
    echo "<p>Article non trouvé ou vous n'avez pas la permission.</p>";
    include 'footer.php';
    exit;
}

// Gestion du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
    
    // Gestion de l'image
    $image_path = $article['image']; // conserver l'ancienne si aucune nouvelle
    if (!empty($_FILES['image']['tmp_name'])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    q("UPDATE articles SET title = ?, slug = ?, content = ?, image = ? WHERE id = ? AND author_id = ?",
      'sssiii', [$title, $slug, $content, $image_path, $article_id, $user_id]);

    header('Location: dashboard.php');
    exit;
}
?>

<section class="card">
    <h2>Modifier l'article</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" value="<?=htmlspecialchars($article['title'])?>" required>
        </div>
        <div class="form-group">
            <label for="content">Contenu</label>
            <textarea id="content" name="content" rows="6" required><?=htmlspecialchars($article['content'])?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image (laisser vide pour conserver l'actuelle)</label>
            <input type="file" id="image" name="image" accept="image/*">
            <?php if (!empty($article['image']) && file_exists($article['image'])): ?>
                <p>Image actuelle : <img src="<?=htmlspecialchars($article['image'])?>" style="width:150px;height:auto;"></p>
            <?php endif; ?>
        </div>
        <button type="submit">Mettre à jour l'article</button>
    </form>
</section>

<?php include 'footer.php'; ?>