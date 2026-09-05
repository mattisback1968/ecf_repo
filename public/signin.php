
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
    $mdp = $_POST['password'] ?? '';

    // Vérification simple
    if ($email === '' || $mdp === '') {
        $message = "Veuillez remplir tous les champs.";
        afficheMessage($message);
    } else {

        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur) {

            if ($mdp === $utilisateur['password']) {

                $_SESSION['utilisateur_id'] = $utilisateur['id'];
                $_SESSION['nom'] = $utilisateur['nom'];

                header("Location: accueil.php");
                exit;

            } else {
                $message = "Mot de passe incorrect.";
                afficheMessage($message);
            }

        } else {
            $message = "Utilisateur inconnu.";
            afficheMessage($message);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite et Gourmand</title>
</head>

<div class="container">
    <form>
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

        <br>

        <div class="mb-3">

          <label for="PasswordInput" class="form-label">Mot de passe</label>

          <label for="password" class="form-label">Mot de passe</label>

          <input
            type="password"
            class="form-control"
            id="password" name="password"
            required>

        </div>

        <br>

        <div class="text-center">

            <button type="submit" class="btn btn-primary">Connexion</button>

        </div>

    </form>

    <br><br>

    <div class="text-center pt-3">

        <a href="/signup.php">Vous n’avez pas de compte ? Inscrivez-vous dans la joie et l'allégresse !</a>

    </div>