<?php
// 1. Initialiser la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Vider toutes les variables de session
$_SESSION = array();

// 3. Détruire le cookie de session dans le navigateur
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Détruire définitivement la session sur le serveur
session_destroy();

// 5. Rediriger vers la page d'accueil (située un dossier plus haut)
header("Location: ../index.php");
exit;
