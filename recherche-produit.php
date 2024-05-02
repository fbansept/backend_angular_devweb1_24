<?php

include 'header-init.php';

if (!isset($_GET['recherche'])) {
    echo '{"message" : "il n\'y a pas de parametre recherche"}';
    http_response_code(400);
    exit;
}

$recherche = $_GET['recherche'];

$requete = $connexion->prepare("SELECT * 
FROM produit 
WHERE nom LIKE :recherche 
OR description LIKE :recherche");

$requete->execute(["recherche" => "%$recherche%"]);

$listeProduit = $requete->fetchAll();

echo json_encode($listeProduit);
