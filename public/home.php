<?php

session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: signin.php");
    exit;
}

echo "Bienvenue sur l'espace utilisateur 🚀";
echo "<br>";
echo "Utilisateur ID : " . $_SESSION['utilisateur_id'];
echo "<br>";
echo "Utilisateur : " . $_SESSION['nom'] . " " . $_SESSION['prenom'];