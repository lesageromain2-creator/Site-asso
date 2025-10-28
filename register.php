<?php
require 'db.php';
include 'header.php';
// chicken20xrt
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Vérifications de base
    if ($username === '' || $email === '' || $password === '') {
        $errors[] = 'Tous les champs sont requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Adresse email invalide.';
    } else {
        // Vérifie si l'utilisateur existe déjà (email OU username)
        $exists = q("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1", 'ss', [$email, $username]);
        if ($exists && $exists->num_rows > 0) {
            $errors[] = 'Email ou nom d’utilisateur déjà utilisé.';
        } else {
            // Crée le compte
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $res = q("INSERT INTO users (username, email, password) VALUES (?, ?, ?)", 'sss', [$username, $email, $hash]);

            if ($res !== false) {
                $success = "🎉 Bienvenue " . htmlspecialchars($username) . " ! Vous êtes bien inscrit.";
            } else {
                $errors[] = 'Erreur lors de la création du compte.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="card" style="max-width:700px; margin:20px auto;">
    <h2>Inscription</h2>

    <?php if ($errors): ?>
        <div class="card" style="background:#fee; border:1px solid #f99; padding:10px;">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="card" style="background:#e7ffe7; border:1px solid #6c6; padding:10px;">
            <p><?= $success ?></p>
            <p><a href="login.php">Se connecter →</a></p>
        </div>
    <?php else: ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">S'inscrire</button>
        </form>
    <?php endif; ?>

    <p style="margin-top:12px;">Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
</section>

<?php include 'footer.php'; ?>
</body>
</html>

