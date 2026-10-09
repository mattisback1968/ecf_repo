<?php
session_start();
require 'config.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mdp = $_POST['mdp'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mdp, $utilisateur['mdp'])) {
        $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
        $_SESSION['email'] = $utilisateur['email'];
        header("Location: dashboard.php");
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}