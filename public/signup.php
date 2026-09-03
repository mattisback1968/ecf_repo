
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

    } else {

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

    <?php if (!empty($message)): ?>
    
        <p style="color:red">
        <?= htmlspecialchars($message) ?>
    </p>

    <?php endif; ?>

<div class="hero-scene text-center text-white">

    <div class="hero-scene-content">

        <h1>Inscription</h1>

    </div>

</div>

<div class="container">

    <form method="POST" action="signup.php">

        <div class="mb-3">

            <label for="NomInput" class="form-label">Nom</label>

            <input
                type="text"
                class="form-control"
                id="NomInput"
                placeholder="Votre nom"
                name="Nom"> 

        </div>
        <br>

        <div class="mb-3">

            <label for="PrenomInput" class="form-label">Prénom</label>

            <input type="text" class="form-control" id="PrenomInput" placeholder="Votre prénom" name="Prenom"> 

        </div>

        <div class="mb-3">

          <label for="email" class="form-label">Email</label>

          <input
            type="email"
            class="form-control"
            id="email"
            placeholder="test@mail.fr"
            name="email"
            required> 

        </div>

        <div class="mb-3">

          <label for="password" class="form-label">Mot de passe</label>

          <input
            type="password"
            class="form-control"
            id="password" name="password"
            required>

        </div>

        <div class="mb-3">

            <label for="password_confirm" class="form-label">Confirmez le mot de passe</label>

            <input
                type="password"
                class="form-control"
                id="password_confirm"
                name="PasswordConfirm"
                required>

          </div>

        <div class="text-center">

            <button
                type="submit" class="btn btn-primary">S'inscrire</button>

        </div>

    </form>

    <br><br>

    <div class="text-center pt-3">

        <a href="/signin.php">Vous avez déjà un compte ? Connectez-vous ici !</a>

    </div>

</div>

</body>
</html>
