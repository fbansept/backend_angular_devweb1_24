<?php

include 'header-init.php';

if (!isset($_GET['id'])) {
    echo '{"message" : "il n\'y a pas d\'identiant dans l\'URL"}';
    http_response_code(400);
    exit;
}

$idProduit = $_GET["id"];

$requete = $connexion->prepare("SELECT * FROM produit WHERE id = ?");
$requete->execute([$idProduit]);

$produit = $requete->fetch();

if (!$produit) {
    //echo '{"message" : "produit inexistant"}';
    echo json_encode(["message" => "produit inexistant"]);
    http_response_code(404);
    exit;
}

echo json_encode($produit);
