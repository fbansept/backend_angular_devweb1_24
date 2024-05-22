<?php

include 'header-init.php';

//tansformer le JSON en objet PHP contenant les informations de l'utilisateur
$json = file_get_contents('php://input');

// Le convertit en objet PHP
$utilisateur = json_decode($json);

//verifier l'email de l'utilisateur est unique
$requete = $connexion->prepare("SELECT * FROM utilisateur WHERE email = :email");
$requete->execute(["email" => $utilisateur->email]);
$utilisateurExistant = $requete->fetch();

if ($utilisateurExistant) {
    http_response_code(409); //note : 409 = CONFLICT
    echo '{"message" : "Cet email est déjà utilisé"}';
    exit();
}

//On ajoute l'utilisateur dans la base de données
$requete = $connexion->prepare("INSERT INTO utilisateur(email,password,admin) 
                                VALUES (:email, :password, 0)");

$requete->execute([
    "email" => $utilisateur->email,
    "password" => password_hash($utilisateur->password, PASSWORD_DEFAULT)
]);

echo '{"message" : "Vous êtes inscrit"}';
