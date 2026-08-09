<?php

session_start();

require_once __DIR__ . '/../config/db_sql.php';

$pdo = DB_SQL::get();

$message = "";

// Le formulaire a été envoyé ?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $motDePasse = $_POST['password'] ?? '';

    // Vérification simple
    if ($email === '' || $motDePasse === '') {
        $message = "Veuillez remplir tous les champs.";
    } else {

        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {

            // Pour l'instant mot de passe en clair
            if ($motDePasse === $utilisateur['password']) {

                $_SESSION['utilisateur_id'] = $utilisateur['id'];
                $_SESSION['nom'] = $utilisateur['nom'];

                header("Location: accueil.php");
                exit;

            } else {
                $message = "Mot de passe incorrect.";
            }

        } else {
            $message = "Utilisateur inconnu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>

<body>

<h1>Connexion</h1>

<?php if ($message): ?>
    <p style="color:red"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="post">

    <label>Email</label><br>
    <input
        type="email"
        name="email"
        required
    ><br><br>

    <label>Mot de passe</label><br>
    <input
        type="password"
        name="password"
        required
    ><br><br>

    <button type="submit">
        Se connecter
    </button>

</form>

</body>
</html>

