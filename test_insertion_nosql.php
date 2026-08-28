<?php
require_once __DIR__ . '/vendor/autoload.php';

try {
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $mongo->ecf_nosql->avis_et_logs;

    // 1. On crée la date avec le bon fuseau horaire
$date_paris = new DateTime('now', new DateTimeZone('Europe/Paris'));

// 2. On insère dans MongoDB en lui passant simplement l'objet $date_paris
$resultat = $collection->insertOne([
    'user_id_sql' => 1, 
    'message'     => 'Nouvelle entrée de test propre et validée !',
    'etoiles'     => 4,
    'type_log'    => 'creation_test_2',
    // On passe directement la date PHP au constructeur
    'cree_le'     => new \MongoDB\BSON\UTCDateTime($date_paris) 
    ]);



    echo "Document inséré avec l'ID NoSQL : " . $resultat->getInsertedId();

} catch (Exception $e) {
    echo "Erreur MongoDB : " . $e->getMessage();
}
