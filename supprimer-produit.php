<?php

include 'header-init.php';
include 'jwt-helper.php';

if (!isset($_GET['id'])) {
    echo '{"message" : "il n\'y a pas d\'identiant dans l\'URL"}';
    http_response_code(400);
    exit;
}

$idProduit = $_GET['id'];

//On recupère le produit dans la bdd
$requete = $connexion->prepare("SELECT * FROM produit WHERE id = ?");
$requete->execute([$idProduit]);
$produit = $requete->fetch();

//si il n'y a pas de produit on retourne une erreur 404
if(!$produit) {
    http_response_code(404);
    echo '{"message" : "Ce produit produit n\'existe pas"}';
    exit();
}

$utilisateurConnecte = extractJwtBody();

//si l'utilisateur n'est pas administrateur en plus de ne pas etre le créateur du produit 
if (!$utilisateurConnecte->admin && $utilisateurConnecte->id != $produit['id_utilisateur']) {
    http_response_code(403);
    echo '{"message" : "Vous n\'êtes ni administrateur, ni créateur du produit"}';
    exit();
}

$requete = $connexion->prepare("DELETE FROM produit WHERE id = ?");

$requete->execute([$idProduit]);

echo '{"message" : "le produit a bien été supprimé"}';
