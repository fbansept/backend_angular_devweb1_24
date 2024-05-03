<?php

function supprimerImageProduit($idProduit, $connexion)
{

    //on vérifie si il y a une ancienne image a supprimer
    $requete = $connexion->prepare("SELECT image FROM produit WHERE id = :id");

    $requete->execute(["id" => $idProduit]);

    $produit = $requete->fetch();
    $nomImageAsupprimer = $produit['image'];

    //si il y a une ancienne image
    if ($nomImageAsupprimer != null && $nomImageAsupprimer != '') {
        //on supprime l'image du dossier uploads
        unlink('uploads/' . $nomImageAsupprimer);
    }

    //on enleve le nom de l'image du produit
    $requete = $connexion->prepare("UPDATE produit SET
                                    image = ''
                                    WHERE id = :id");

    $requete->execute(["id" => $idProduit]);
}

function upload()
{

    $date = date("Y-m-d-H-i-s");

    $nouveauNomDeFichier = $date . '-' . $_FILES['image']['name'];

    move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $nouveauNomDeFichier);

    return $nouveauNomDeFichier;
}
