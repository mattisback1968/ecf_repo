<?php
session_start();

require_once __DIR__ . '/../config/db_sql.php';

$pdo = DB_SQL::get();

$message = "";
$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mdp = $_POST['password'];
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{10,}$/';
    
    if (empty($email) || empty($mdp)) {
        $erreur = "Email et mot de passe sont obligatoires.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT utilisateur_id FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO utilisateur (email, password, adresse, telephone) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([
                $email,
                $mdp_hash,
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
    
    <?php if ($message): ?>
        <p style="color:red"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

<form method="POST" action="inscription.php">

    <label>Email : <input type="email" name="email" required></label><br>
    <label>Mot de passe : <input type="password" name="password" required></label><br>
    <label>Adresse : <input type="text" name="adresse"></label><br>
    <label>Téléphone : <input type="tel" name="telephone"></label><br>
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>