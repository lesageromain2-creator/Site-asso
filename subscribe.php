<?php
session_start();
include('db.php'); // connexion à la base de données
include 'header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Vérification basique de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Adresse email invalide.');
    }

    // Préparer et exécuter l'insertion dans la base
    $stmt = $mysqli->prepare("INSERT INTO subscribers (email) VALUES (?)");
    if (!$stmt) {
        die("Erreur préparation SQL : " . $mysqli->error);
    }
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "✅ Merci ! Votre email a été ajouté à notre liste.";
        // Ici tu peux aussi rediriger vers une autre page :
        // header('Location: merci.php'); exit;
    } else {
        if ($mysqli->errno === 1062) { // email déjà existant
            echo "⚠️ Cette adresse est déjà inscrite.";
        } else {
            echo "Erreur lors de l'inscription : " . $mysqli->error;
        }
    }

    $stmt->close();
} else {
    // Si la page est visitée directement sans POST
    header('Location: index.php');
    exit;
}
?>

<?php include 'footer.php'; ?>