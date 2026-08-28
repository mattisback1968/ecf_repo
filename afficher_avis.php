<?php
// 1. Chargement de l'autoloader et gestion des alias pour VS Code
// Le "../" permet de sortir du dossier "public" pour trouver "vendor" à la racine du projet
require_once __DIR__ . '/../vendor/autoload.php';

try {
    // 2. Connexion à MongoDB
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $mongo->ecf_nosql->avis_et_logs;

    // 3. Récupération de TOUS les documents (équivalent du .find() de mongosh)
    // On trie par date décroissante pour avoir les plus récents en premier
    $les_avis = $collection->find([], [
        'sort' => ['cree_le' => -1]
    ]);

} catch (Exception $e) {
    die("Erreur de connexion MongoDB : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis et Logs de l'application</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; }
        .carte-avis { background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid #4CAF50; }
        .etoiles { color: #FFD700; font-size: 1.2em; font-weight: bold; }
        .meta { font-size: 0.85em; color: #777; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>💬 Avis et Logs (MongoDB)</h2>

    <?php 
    // 4. Boucle pour afficher chaque document
    foreach ($les_avis as $avis) { 
        
        // Extraction et conversion propre de la date MongoDB en objet DateTime PHP
        // C'est l'inverse de ce qu'on a fait à l'insertion !
        $date_brute = $avis['cree_le']->toDateTime();
        $date_brute->setTimezone(new DateTimeZone('Europe/Paris')); // On applique le fuseau de Paris
        $date_formatee = $date_brute->format('d/m/Y à H:i:s');
        
        // Répéter l'icône étoile selon la note (ex: 5 étoiles = ⭐⭐⭐⭐⭐)
        $etoiles_visuelles = str_repeat('⭐', $avis['etoiles']);
    ?>
        
        <div class="carte-avis">
            <div class="etoiles"><?= $etoiles_visuelles ?> (<?= $avis['etoiles'] ?>/5)</div>
            <p><strong>Message :</strong> "<?= htmlspecialchars($avis['message']) ?>"</p>
            
            <div class="meta">
                ID SQL Utilisateur : <code><?= $avis['user_id_sql'] ?></code> | 
                Type : <code><?= htmlspecialchars($avis['type_log']) ?></code> <br>
                Posté le : <?= $date_formatee ?>
            </div>
        </div>

    <?php } ?>

</div>

</body>
</html>
