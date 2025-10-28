<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>Connexion</title>
</head>
<body>

<?php
require 'db.php';
include 'header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $errors[] = 'Veuillez remplir tous les champs.';
    } else {
        $res = q("SELECT * FROM users WHERE email = ? LIMIT 1", 's', [$email]);
        $user = $res ? $res->fetch_assoc() : null;

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Adresse email ou mot de passe incorrect.';
        }
    }
}
?>

<section class="card">
  <h2>Connexion</h2>

  <?php if ($errors): ?>
    <div class="card" style="background:#fee;border:1px solid #f99;padding:10px;">
      <ul>
        <?php foreach($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="">
    <div class="form-group">
      <label for="email">Adresse email</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>
    </div>

    <button type="submit">Se connecter</button>
  </form>

  <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
