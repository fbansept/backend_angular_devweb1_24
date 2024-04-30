<?php

include 'header-init.php';

if (!isset($_GET['id'])) {
    echo '{"message" : "il n\'y a pas d\'identiant dans l\'URL"}';
    http_response_code(400);
    exit;
}

$idProduit = $_GET['id'];

$requete = $connexion->prepare("DELETE FROM produit WHERE id = ?");

$requete->execute([$idProduit]);

echo '{"message" : "le produit a bien été supprimé"}';
