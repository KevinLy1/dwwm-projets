<?php 
// Définir le fuseau horaire
date_default_timezone_set("Europe/Paris");

// Ouvrir la session (personnaliser le nom du cookie)
session_name("BLOGSESSION"); // par défaut : PHPSESSID
session_start();

// Connexion BDD
$pdo = new PDO(
    "mysql:host=localhost;charset=utf8;dbname=blog", "root", "",
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // utiliser ERRMODE_SILENT lorsque l'on passe en prod
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // mode de fetch par défaut
    )
);

// Constantes
define("URLSITE", "/blog/");

// Inclusion des fonctions
require_once("functions.php");
?>