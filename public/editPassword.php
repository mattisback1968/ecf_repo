><!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Changement de mot de passe</title>
    
    <!--<style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; }
        .carte-avis { background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #4CAF50; }
        .etoiles { color: #FFD700; font-size: 1.2em; font-weight: bold; }
        .meta { font-size: 0.85em; color: #777; margin-top: 10px; }
    
    </style>-->

    <link
    href="bootstrap/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body>

<div class="hero-scene text-center text-white">
    <div class="hero-scene-content">
        
    <h1 class="text-dark"> Changement de mot de passe</h1>
    </div>
</div>

<body>
    <<div class="hero-scene text-center text-white">

    <div class="hero-scene-content">

        <h1 class="test_dark">Changement de mot de passe</h1>

    </div>

</div>

<div class="container">
    <form>
        <div class="mb-3">
          <label for="Password" class="form-label">Mot de passe actuel</label>
          <input type="password" class="form-control" id="Password" name="Password" required>
        </div>
        
        <div class="mb-3">
          <label for="NewPassword" class="form-label">Nouveau mot de passe</label>
          <input type="password" class="form-control" id="NewPassword" name="NewPassword" required>
        </div>

        <div class="mb-3">
            <label for="PasswordConfirm" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="PasswordConfirm" name="PasswordConfirm" required>
          </div>

        <div class="text-center pt-3">
            <button type="submit" class="btn btn-primary">Changer mon mot de passe</button>
        </div>
    </form>
    <br>
    <div class="text-center pt-3">
        <a href="/account.html">Accueil</a>
    </div>
</div>
</body>
</html>