<?php

include 'header-init.php';

// Prend les données brutes de la requête
$json = file_get_contents('php://input');

// Le convertit en objet PHP
$produit = json_decode($json);

$requete = $connexion->prepare("INSERT INTO produit(nom,description,prix) VALUES (:nom, :description, :prix)");

$requete->bindValue("nom", $produit->nom);
$requete->bindValue("description", $produit->description);
$requete->bindValue("prix", $produit->prix);

$requete->execute();

echo '{"message" : "Le produit a bien été ajouté"}';
