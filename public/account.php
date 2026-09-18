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

// Enregistrer dans la bdd les informations mises à jour ou complétées de l'utilisateur
    // Récupération du profil pour préremplir le formulaire
$stmt = $pdo->prepare(
    "SELECT nom, prenom, email, telephone, adresse, ville, pays
     FROM utilisateur
     WHERE utilisateur_id = ?"
);
$stmt->execute([$utilisateurId]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Mise à jour uniquement après soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastName = trim($_POST['last_name'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $address = trim($_POST['adresse'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');

    $stmt = $pdo->prepare(
        "UPDATE utilisateur
         SET nom = ?, prenom = ?, adresse = ?, ville = ?, pays = ?, telephone = ?
         WHERE utilisateur_id = ?"
    );

    $stmt->execute([
        $lastName,
        $firstName,
        $address,
        $city,
        $country,
        $phone,
        $utilisateurId
    ]);

    header("Location: account.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Page d'Accueil</title>
    <!-- Liaison avec le fichier CSS externe Bootstrap-->
    <link
    href="bootstrap/css/bootstrap.min.css"
    rel="stylesheet">
    </link>
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
                name="adresse"
                value="<?= htmlspecialchars($utilisateur['adresse'] ?? '') ?>">

        </div>

        <div class="mb-3">

            <label for="city">CP et ville</label>
            <input
                type="text"
                class="form-control"
                id="city"
                name="city"
                placeholder="75001 Paris"
                value="<?= htmlspecialchars($utilisateur['ville'] ?? '') ?>">

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
                type="submit" action=class="btn btn-primary">Modifier le compte</button>
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
