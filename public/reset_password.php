<?php
require_once 'config.php';
// 1. Connexion à la BDD via PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=resto;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion BDD : ' . $e->getMessage());
}

$message_retour = "";
$classe_message = "";
$formulaire_visible = false;
$user_id_concerne = null;

// 2. VERIFICATION DU TOKEN DANS L'URL
if (!empty($_GET['token'])) {
    $token_brut = $_GET['token'];
    
    // On calcule le hash SHA-256 pour pouvoir le comparer avec la BDD
    $token_hash = hash('sha256', $token_brut);

    // On cherche si ce hash existe et s'il n'est pas expiré
    $query = $db->prepare("
        SELECT user_id, expires_at
        FROM password_resets
        WHERE token_hash = ?
    ");
    $query->execute([$token_hash]);
    $reset_request = $query->fetch(PDO::FETCH_ASSOC);

    if ($reset_request) {
        // Vérification de la date d'expiration
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $expires_at = new DateTime($reset_request['expires_at'], new DateTimeZone('Europe/Paris'));

        if ($now < $expires_at) {
            // Le token est valide et toujours actif !
            $formulaire_visible = true;
            $user_id_concerne = $reset_request['user_id'];
        } else {
            $message_retour = "Ce lien de réinitialisation a expiré (validité de 15 minutes).";
            $classe_message = "danger";
        }
    } else {
        $message_retour = "Lien de réinitialisation invalide ou déjà utilisé.";
        $classe_message = "danger";
    }
} else {
    $message_retour = "Aucun jeton de réinitialisation fourni.";
    $classe_message = "danger";
}

// 3. TRAITEMENT DE LA SOUMISSION DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $formulaire_visible && $user_id_concerne) {
    $new_password = $_POST['NewPassword'];
    $password_confirm = $_POST['PasswordConfirm'];

    // Vérification de la correspondance des mots de passe
    if ($new_password !== $password_confirm) {
        $message_retour = "Attention, les deux mots de passe ne correspondent pas.";
        $classe_message = "danger";
    } elseif (strlen($new_password) < 8) { // Petite sécurité bonus sur la longueur
        $message_retour = "Le nouveau mot de passe doit faire au moins 8 caractères.";
        $classe_message = "danger";
    } else {
        // Tout est bon, on hache le nouveau mot de passe avec l'algorithme standard de PHP
        $password_hache = password_hash($new_password, PASSWORD_BCRYPT);

        // Mise à jour dans la table 'utilisateur'
        $update = $db->prepare("UPDATE utilisateur SET mot_de_passe = ? WHERE utilisateur_id = ?");
        $update->execute([$password_hache, $user_id_concerne]);

        // Nettoyage : On supprime le token pour qu'il ne soit plus jamais réutilisé
        $delete = $db->prepare("DELETE FROM password_resets WHERE user_id = ?");
        $delete->execute([$user_id_concerne]);

        $message_retour = "Votre mot de passe a bien été modifié ! Vous pouvez à présent vous connecter.";
        $classe_message = "success";
        $formulaire_visible = false; // On cache le formulaire puisque l'action est réussie
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de mot de passe</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100 m-0">

<div class="card shadow-sm p-4" style="width: 100%; max-width: 450px; border-radius: 10px;">
    
    <h1 class="h4 text-dark mb-4 text-center fw-bold">Nouveau mot de passe</h1>

    <!-- Affichage des alertes Bootstrap (Succès ou Erreur) -->
    <?php if (!empty($message_retour)): ?>
        <div class="alert alert-<?= $classe_message ?> alert-dismissible fade show small" role="alert">
            <?= $message_retour ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <!-- Le formulaire ne s'affiche QUE si le token est valide et non expiré -->
    <?php if ($formulaire_visible): ?>
        <form method="POST" action="">
            
            <div class="mb-3">
              <label for="NewPassword" class="form-label small fw-bold">Nouveau mot de passe</label>
              <input type="password" class="form-control" id="NewPassword" name="NewPassword" required minlength="8">
            </div>

            <div class="mb-3">
                <label for="PasswordConfirm" class="form-label small fw-bold">Confirmer le nouveau mot de passe</label>
                <input type="password" class="form-control" id="PasswordConfirm" name="PasswordConfirm" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mt-2">Mettre à jour le mot de passe</button>
        </form>
    <?php endif; ?>
    
    <div class="text-center mt-3">
        <a href="/login.php" class="text-decoration-none small">← Retour à la connexion</a>
    </div>

</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
