<?php

include 'header-init.php';
include 'produit-helper.php';
include 'jwt-helper.php';

//si l'utilisateur n'est pas connecté (ou n'a pas de JWT valide dans son entete de requete)
//il recevra une erreur 401 / 403
$utilisateurConnecte = extractJwtBody();

// //si l'utilisateur n'est pas administrateur 
// if(!$utilisateurConnecte->admin) {
//     http_response_code(403);
//     echo '{"message" : "Vous n\'êtes pas administrateur"}';
//     exit();
// }

$json = $_POST['produit'];


$nouveauNomDeFichier = '';

if (isset($_FILES['image'])) {

    $nouveauNomDeFichier = upload();
}

// Le convertit en objet PHP
$produit = json_decode($json);

$requete = $connexion->prepare("INSERT INTO produit(nom,description,prix, image, id_utilisateur) 
                                VALUES (:nom, :description, :prix, :image, :id_utilisateur)");

$requete->bindValue("nom", $produit->nom);
$requete->bindValue("description", $produit->description);
$requete->bindValue("prix", $produit->prix);
$requete->bindValue("image", $nouveauNomDeFichier);
$requete->bindValue("id_utilisateur", $utilisateurConnecte->id);


$requete->execute();

echo '{"message" : "Le produit a bien été ajouté"}';
