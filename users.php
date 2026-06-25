
<?php

try {

    $pdo = new PDO('mysql:host=localhost;dbname=resto', 'root', '');

    foreach ($pdo->query('SELECT email, prenom FROM utilisateur', PDO::FETCH_ASSOC) as $user) {

        echo $user['prenom'].' '.$user['email'].'<br>';

    }

} catch (PDOException $e) {

    echo 'Impossible de récupérer la liste des utilisateurs';

}