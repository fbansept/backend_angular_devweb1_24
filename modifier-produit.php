<?php

include 'header-init.php';

if (!isset($_GET['id'])) {
    echo '{"message" : "il n\'y a pas d\'identiant dans l\'URL"}';
    http_response_code(400);
    exit;
}

$idProduit = $_GET["id"];

// Prend les données brutes de la requête
$json = file_get_contents('php://input');

// Le convertit en objet PHP
$produit = json_decode($json);

$requete = $connexion->prepare("UPDATE produit SET 
                                    nom = :nom, 
                                    description = :description, 
                                    prix = :prix
                                WHERE id = :id");

$requete->execute([
    "nom" => $produit->nom,
    "description" => $produit->description,
    "prix" =>  $produit->prix,
    "id" => $idProduit
]);

echo '{"message" : "Le produit a bien été modifié"}';
