<?php

require_once __DIR__ . '/../config/db_sql.php';

require_once __DIR__ . '/../vendor/autoload.php';

//var_dump(class_exists('DB_SQL'));
//var_dump(get_declared_classes());
//exit;

session_start();

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

<br><br>

 <div class="text-center pt-3">

    <a href="demande_reset.php">Vous n'avez même pas été foutu de retenir votre mot de passe ? Cliquez ici ! !</a>

</div>

</body>
</html>

