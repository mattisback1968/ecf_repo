// Remplace 'test_db' et 'test_collection' par les noms de ton choix


// Ton document de test hybride (id SQL, message, étoiles, log)

<?php
$collection = $mongo->ecf_nosql->avis_et_logs;
$resultat = $collection->insertOne([
    'user_id_sql' => 1, // Référence à ton utilisateur MySQL
    'message'     => 'Appli ok, connexion établie !',
    'etoiles'     => 2,
    'type_log'    => 'creation_test',
    'cree_le'     => new MongoDB\BSON\UTCDateTime()
]);

echo "Document inséré avec l'ID NoSQL : " . $resultat->getInsertedId();