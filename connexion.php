<?php

include 'header-init.php';

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

// générer le JWT

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

$payload = json_encode([
    'id' => $utilisateurBdd['id'],
    'admin' => $utilisateurBdd['admin'],
    'email' => $utilisateurBdd['email'],
]);

function base64UrlEncode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}


// Encoder en Base64 URL-safe
$base64UrlHeader = base64UrlEncode($header);
$base64UrlPayload = base64UrlEncode($payload);

// Créer la signature
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'votre_cle_secrete', true);
$base64UrlSignature = base64UrlEncode($signature);

// Assembler le token
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;


echo '{"jwt" : "' . $jwt . '"}';
