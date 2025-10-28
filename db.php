<?php
// db.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'new_site_artv1';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('Échec connexion MySQL : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// helper universel pour SELECT / INSERT / UPDATE
function q($sql, $types = null, $params = []) {
    global $mysqli;

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo "Erreur de préparation : " . $mysqli->error;
        return false;
    }

    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }

    $ok = $stmt->execute();
    if (!$ok) {
        echo "Erreur d’exécution : " . $stmt->error;
        return false;
    }

    // si la requête est un SELECT → on retourne le résultat
    if (stripos($sql, 'SELECT') === 0) {
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    } else {
        // sinon, on retourne true ou false selon succès
        $stmt->close();
        return true;
    }
}

// on s’assure qu’une session est bien démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
