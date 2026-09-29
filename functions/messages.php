<?php

// 1. Pour les messages de succès / information (Vert)
function afficheMessage($message)
{
    echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Succès !</strong> ' . htmlspecialchars($message) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
}

// 2. Pour les messages d'erreur / avertissement (Rouge)
function afficheErreur($message)
{
    echo '
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Erreur :</strong> ' . htmlspecialchars($message) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>';
}
