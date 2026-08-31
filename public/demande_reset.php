<?php
// 1. Inclusion de Composer
require_once __DIR__ . '/../vendor/autoload.php';

$message_retour = "";
$classe_message = "";

// 2. Connexion à la bdd MySQL via PDO
try {
    $db = new PDO('mysql:host=localhost;dbname=resto;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion BDD : ' . $e->getMessage());
}

// 3. Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $email_saisi = trim($_POST['email']);

    // Sécurité : Message générique identique pour éviter l'énumération d'emails
    $message_retour = "Si cet email correspond à un compte, un lien de réinitialisation vous a été envoyé.";
    $classe_message = "info";

    // 4. Vérifier si l'utilisateur existe dans votre table 'utilisateur'
    $query = $db->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = ?");
    $query->execute([$email_saisi]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $user_id = $user['utilisateur_id'];

        // 5. Génération du token brut (64 caractères hexadécimaux aléatoires)
        $token_brut = bin2hex(random_bytes(32)); 

        // 6. Calcul du hash SHA-256 (ce qui va être stocké en BDD)
        $token_hash = hash('sha256', $token_brut);

        // 7. Définition de l'expiration (+15 minutes)
        $date_expiration = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $date_expiration->modify('+15 minutes');
        $expires_at = $date_expiration->format('Y-m-d H:i:s');

        // 8. Nettoyage : on supprime les anciens tokens de cet utilisateur s'il y en avait
        $delete = $db->prepare("DELETE FROM password_resets WHERE user_id = ?");
        $delete->execute([$user_id]);

        // 9. Insertion du nouveau token haché
        $insert = $db->prepare("
            INSERT INTO password_resets (user_id, token_hash, expires_at) 
            VALUES (:user_id, :token_hash, :expires_at)
        ");
        $insert->execute([
            'user_id'    => $user_id,
            'token_hash' => $token_hash,
            'expires_at' => $expires_at
        ]);

        // 10. Simulation de l'envoi de l'email (Lien absolu contenant le TOKEN BRUT)
        $lien_reinitialisation = "http://ecf.local" . $token_brut;
        
        // A des fin de test, on triche un peu : on affiche le lien directement dans la page
        $message_retour .= "<br><br><strong>[Mode Test - Lien généré] :</strong><br><a href='$lien_reinitialisation'>$lien_reinitialisation</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { margin-top: 0; color: #333; }
        input[type="email"] { width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007BFF; color: white; border: none; border-radius: 4px; font-size: 1em; cursor: pointer; }
        button:hover { background: #0056b3; }
        .alert { padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9em; line-height: 1.4; }
        .info { background: #e3f2fd; color: #0d47a1; border-left: 4px solid #1e88e5; }
    </style>
</head>
<body>

<div class="box">
    <h2>Mot de passe oublié ?</h2>
    <p style="color: #666; font-size: 0.9em;">Saisissez votre adresse email pour recevoir un lien de réinitialisation.</p>

    <?php if (!empty($message_retour)): ?>
        <div class="alert <?= $classe_message ?>"><?= $message_retour ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="email">Adresse email :</label>
        <input type="email" id="email" name="email" required placeholder="exemple@domaine.com">
        <button type="submit">Envoyer le lien</button>
    </form>
</div>

</body>
</html>
