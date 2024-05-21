<?php

include 'header-init.php';
include 'jwt-helper.php';

//tansformer le JSON en objet PHP contenant les informations de l'utilisateur
$json = file_get_contents('php://input');

// Le convertit en objet PHP
$utilisateur = json_decode($json);

// vérifier que l'utilisateur existe dans la base de donnée
$requete = $connexion->prepare("SELECT * 
                                FROM utilisateur 
                                WHERE email = :email
                                AND password = :password");

$requete->execute([
    "email" => $utilisateur->email,
    "password" => $utilisateur->password
]);

$utilisateurBdd = $requete->fetch();

if (!$utilisateurBdd) {
    http_response_code(403);
    echo '{"message" : "email ou mot de passe incorrect"}';
    exit();
}

$jwt = generateJwt($utilisateurBdd);

echo '{"jwt" : "' . $jwt . '"}';
