
<?php

try {

    $pdo = new PDO('mysql:host=localhost;dbname=melody_db', 'root', '');

    foreach ($pdo->query('SELECT username FROM users', PDO::FETCH_ASSOC) as $user) {

        echo $user['username'].'<br>';

    }

} catch (PDOException $e) {

    echo 'Impossible de récupérer la liste des utilisateurs';

}