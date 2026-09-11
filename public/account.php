
<?php
/*
session_start();

require_once __DIR__ . '/../config/db_sql.php';
require_once __DIR__ . '/../functions/messages.php';

$pdo = DB_SQL::get();

$message = "";
$erreur = '';

# INSERER fonction Filter_var pour format adresse email (filter_var($mail, FILTER_VALIDATE_EMAIL))
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    //$mdp = $_POST['password'] ?? '';
    //$mdpConfirm = $_POST['PasswordConfirm'] ?? '';
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $city = trim($_POST['ville']?? '');

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
            "UPDATE utilisateur (email, password, nom, prenom, telephone, adresse, ville, pays)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            WHERE utilisateur_id = "
        );

    $stmt->execute([
    $email,
    $mdpHash,
    $lastName,
    $firstName,
    $adresse,
    $telephone
    ]);

        header("Location: signin.php?signup=ok");
        exit;
    }
}
    }

?>
*/  
?> 

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>

    <link
    href="bootstrap/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body>

    <?php if (!empty($message)): ?>
    
        <p style="color:red">
        <?= htmlspecialchars($message) ?>
    </p>

    <?php endif; ?>

<div class="hero-scene text-center text-white">

    <div class="hero-scene-content">

    <h1 class="text-dark">Compte utilisateur</h1>   
    <h1>Compte utilisateur</h1>

    </div>

</div>

<div class="container">

    <form method="POST" action="account.php">

        <div class="mb-3">

            <label for="last_name" class="form-label">Nom</label>

            <input
                type="text"
                class="form-control"
                id="last_name"
                placeholder="Votre nom"
                name="last_name">

        </div>

        <div class="mb-3">

            <label for="first_name" class="form-label">Prénom</label>

            <input
                type="text"
                class="form-control"
                id="first_name"
                placeholder="Votre prénom"
                name="first_name">

        </div>

        <div class="mb-3">

          <label for="email" class="form-label">Email</label>

          <input
            type="email"
            class="form-control"
            id="email"
            placeholder="test@mail.fr"
            name="email"
            readonly> 

        </div>

        <div class="mb-3">

            <label for="Adresse" class="form-label">Adresse</label>

            <input
                type="text"
                class="form-control"
                id="address"
                placeholder="Votre adresse postale"
                name="address">

        </div>

        <div class="mb-3">

            <label for="Country" class="form-label">Pays</label>

            <input
                type="text"
                class="form-control"
                id="country"
                placeholder="Pays de résidence attaché à l'adresse"
                name="country">

        </div>


        <div class="mb-3">

            <label for="phone">Téléphone portable</label>

            <input
                type="tel"
                class="form-control"
                id="phone"
                name="phone"
                placeholder = "06 99 98 65 12"
                required>

          </div>

        <div class="text-center">

            <button
                type="submit" class="btn btn-primary">Modifier le compte</button>
            <button
                type="button" class="btn btn-danger">Supprimer mon compte</button>

        </div>

    </form>

    <div class="text-center pt-3">

        <a href="/edit_password.php">Cliquez ici pour modifier votre mot de passe</a>

    </div>

    
    <div class="text-center pt-3">

        <a href="/home.php">Retour à l'accueil</a>

    </div>

</div>

</body>
</html>
