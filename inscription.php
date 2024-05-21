<?php

include 'header-init.php';

//tansformer le JSON en objet PHP contenant les informations de l'utilisateur
$json = file_get_contents('php://input');

// Le convertit en objet PHP
$utilisateur = json_decode($json);

//TODO : verifier que le mot de passe et l'email ont les formats attendus

//On ajoute l'utilisateur dans la base de données
$requete = $connexion->prepare("INSERT INTO utilisateur(email,password,admin) 
                                VALUES (:email, :password, 0)");

$requete->bindValue("email", $utilisateur->email);
$requete->bindValue("password", password_hash($utilisateur->password, PASSWORD_DEFAULT));

$requete->execute();

echo '{"message" : "Vous êtes inscrit"}';
