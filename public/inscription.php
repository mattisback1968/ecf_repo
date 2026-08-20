<?php
session_start();

require_once __DIR__ . '/../config/db_sql.php';
require_once __DIR__ . '/../functions/messages.php';

$pdo = DB_SQL::get();

$message = "";
$erreur = '';

# INSERER fonction Filter_var pour format adresse email (filter_var($mail, FILTER_VALIDATE_EMAIL))
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $mdp = $_POST['password'] ?? '';
    $mdpConfirm = $_POST['PasswordConfirm'] ?? '';
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');

    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{10,}$/';

    if (empty($email) || empty($mdp)) {

    $message = "Email et mot de passe sont obligatoires.";
    afficheMessage($message);

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $message = "Format email non valide";
    afficheMessage($message);

} else {

    $stmt = $pdo->prepare(
        "SELECT utilisateur_id FROM utilisateur WHERE email = ?"
    );
    $stmt->execute([$email]);

    if ($stmt->fetch()) {

        $message = "Cet email est déjà utilisé.";
        afficheMessage($message);

    } elseif (!preg_match($regex, $mdp)) {

        $message = "Le mot de passe doit comporter au moins 10 caractères, dont une minuscule, une majuscule, un chiffre et un caractère spécial.";
        afficheMessage($message);

    } elseif ($mdp !== $mdpConfirm) {
        $message = "Les mots de passe ne correspondent pas.";
        afficheMessage($message);
    }
    
    else {

        // Toutes les validations sont OK
        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO utilisateur (email, password, adresse, telephone)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $email,
            $mdpHash,
            $adresse ?: null,
            $telephone ?: null
        ]);
    }
}
            
            header("Location: connexion.php?inscription=ok");
            exit;
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
        <?= htmlspecialchars($message) ?>
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
    <br><br>
    
    <label for="password">Mot de passe :</label>
    
    <input
        type="password"
        id="password"
        name="password"
        required
>
    <span>Requis</span>
    <br><br>

    <label for="password_confirm">Confirmer le mot de passe :</label>

    <input
        type="password"
        id="password_confirm"
        name="PasswordConfirm"
        required
>
    <span>Requis</span>
    <br><br>
    
    <button type="submit">S'inscrire</button>

</form>
</body>
</html>