<?php

include 'header-init.php';

include 'produit-helper.php';

//TODO (commencé le 06/05/24):

//récupérer le token dans la requete envoyé par angular

// verifier se validité, si il est invalide : refuser la requete





// Prend les données brutes de la requête
// $json = file_get_contents('php://input');

$json = $_POST['produit'];


$nouveauNomDeFichier = '';

if (isset($_FILES['image'])) {

    $nouveauNomDeFichier = upload();
}

// Le convertit en objet PHP
$produit = json_decode($json);

$requete = $connexion->prepare("INSERT INTO produit(nom,description,prix, image) 
                                VALUES (:nom, :description, :prix, :image)");

$requete->bindValue("nom", $produit->nom);
$requete->bindValue("description", $produit->description);
$requete->bindValue("prix", $produit->prix);
$requete->bindValue("image", $nouveauNomDeFichier);

$requete->execute();

echo '{"message" : "Le produit a bien été ajouté"}';
