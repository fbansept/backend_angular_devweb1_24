<?php

include 'header-init.php';
include 'produit-helper.php';
include 'jwt-helper.php';

if (!isset($_GET['id'])) {
    echo '{"message" : "il n\'y a pas d\'identiant dans l\'URL"}';
    http_response_code(400);
    exit;
}

$idProduit = $_GET["id"];

// Prend les données brutes de la requête
//$json = file_get_contents('php://input');

$json = $_POST['produit'];

// Le convertit en objet PHP
$produit = json_decode($json);


//On recupère le produit dans la bdd
$requete = $connexion->prepare("SELECT * FROM produit WHERE id = ?");
$requete->execute([$idProduit]);
$produitBdd = $requete->fetch();

//si il n'y a pas de produit on retourne une erreur 404
if (!$produitBdd) {
    http_response_code(404);
    echo '{"message" : "Ce produit produit n\'existe pas"}';
    exit();
}

$utilisateurConnecte = extractJwtBody();

//si l'utilisateur n'est pas administrateur en plus de ne pas etre le créateur du produit 
if (!$utilisateurConnecte->admin && $utilisateurConnecte->id != $produitBdd['id_utilisateur']) {
    http_response_code(403);
    echo '{"message" : "Vous n\'êtes ni administrateur, ni créateur du produit"}';
    exit();
}


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


//--- si l'utilsiateur veut supprimer l'ancienne image ---

if (isset($_POST["supprimer_image"])) {

    supprimerImageProduit($idProduit, $connexion);
}

$nouveauNomDeFichier = '';

if (isset($_FILES['image'])) {

    supprimerImageProduit($idProduit, $connexion);

    //on upload la nouvelle image

    $nouveauNomDeFichier = upload();

    $requete = $connexion->prepare("UPDATE produit SET 
                                    image = :image
                                    WHERE id = :id");

    $requete->execute([
        "image" =>  $nouveauNomDeFichier,
        "id" => $idProduit
    ]);
}


echo '{"message" : "Le produit a bien été modifié"}';
