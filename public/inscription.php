<?php
session_start();

require_once __DIR__ . '/../config/db_sql.php';

$pdo = DB_SQL::get();

$message = "";
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $mdp = $_POST['password'] ?? '';
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');

    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{10,}$/';

    if (empty($email) || empty($mdp)) {

        $erreur = "Email et mot de passe sont obligatoires.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT utilisateur_id FROM utilisateur WHERE email = ?"
        );
        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $erreur = "Cet email est déjà utilisé.";

        } elseif (!preg_match($regex, $mdp)) {

            $erreur = "Le mot de passe doit comporter au moins 10 caractères, dont une minuscule, une majuscule, un chiffre et un caractère spécial.";

        } else {

            // toutes les validations sont OK, hachage

            $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO utilisateur (email, password, adresse, telephone) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([
                $email,
                $mdpHash,
                $adresse ?: null,
                $telephone ?: null
            ]);

            header("Location: connexion.php?inscription=ok");
            exit;
        }
    }
}
?>
    
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>

<body>

    <h1>Inscription</h1>
    
    <?php if (!empty($erreur)): ?>
    
        <p style="color:red">
        <?= htmlspecialchars($erreur) ?>
    </p>

    <?php endif; ?>

<form method="POST" action="inscription.php">

    <label for="email">Email :</label>
    <input
        type="email"
        id="email"
        name="email"
        required
        placeholder="ex. utilisateur@fournisseur.com"
    >
    <span>Requis</span>
    
    <label for="password">Mot de passe :</label>
    
    <input
        type="password"
        id="password"
        name="password"
        required
>
    <span>Requis</span>
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>