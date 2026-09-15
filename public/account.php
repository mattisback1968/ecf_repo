
<?php

session_start();

require_once __DIR__ . '/../config/db_sql.php';
require_once __DIR__ . '/../functions/messages.php';

$pdo = DB_SQL::get();

$message = "";

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: signin.php");
    exit;
}

$utilisateurId = $_SESSION['utilisateur_id'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare(
    "SELECT nom, prenom, email, telephone, adresse, pays
     FROM utilisateur
     WHERE utilisateur_id = ?"
);

$stmt->execute([$utilisateurId]);

$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    $message = "Utilisateur introuvable.";
    afficheMessage($message);
}
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
            name="last_name"
            value="<?= htmlspecialchars($utilisateur['nom'] ?? '') ?>">

        </div>

        <div class="mb-3">

            <label for="first_name" class="form-label">Prénom</label>

            <input
                type="text"
                class="form-control"
                id="first_name"
                placeholder="Votre prénom"
                name="first_name"
                value="<?= htmlspecialchars($utilisateur['prenom'] ?? '') ?>">

        </div>

        <div class="mb-3">

          <label for="email" class="form-label">Email</label>

          <input
            type="email"
            class="form-control"
            id="email"
            placeholder="test@mail.fr"
            name="email"
            readonly
            value="<?= htmlspecialchars($utilisateur['email'] ?? '') ?>"> 

        </div>

        <div class="mb-3">

            <label for="address" class="form-label">Adresse</label>

            <input
                type="text"
                class="form-control"
                id="address"
                placeholder="Votre adresse postale"
                name="addresse"
                value="<?= htmlspecialchars($utilisateur['adresse'] ?? '') ?>">

        </div>

        <div class="mb-3">

            <label for="country" class="form-label">Pays</label>

            <input
                type="text"
                class="form-control"
                id="country"
                placeholder="Pays de résidence attaché à l'adresse"
                name="country"
                value="<?= htmlspecialchars($utilisateur['pays'] ?? '') ?>">

        </div>


        <div class="mb-3">

            <label for="phone">Téléphone portable</label>

            <input
            type="tel"
            class="form-control"
            id="phone"
            name="phone"
            placeholder="06 99 98 65 12"
            value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>"
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
