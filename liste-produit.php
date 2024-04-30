<?php

include 'header-init.php';

$requete = $connexion->query("SELECT * FROM produit");

$listeProduit = $requete->fetchAll();

echo json_encode($listeProduit);
