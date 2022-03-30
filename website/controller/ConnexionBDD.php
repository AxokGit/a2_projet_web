<?php
$dsn = 'mysql:dbname=a2_projet_web;host=localhost:3306';               /*Chaine de connexion avec IP et BDD */
$username_bdd = "root";                                                 /*Nom d'utilisateur pour MySQL */
$password_bdd = "";                                     /*Mot de passe pour MySQL*/
$error = false;                                                         /*Erreur de connexion à false avant connexion*/

try {                                                                   /*Tente une connexion...*/
    $bdd = new PDO($dsn, $username_bdd, $password_bdd);                 /*Creation objet PDO et init de la connexion*/
    $bdd->exec("set names utf8mb4");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      /*Définition de toutes erreurs en tant qu'Exception*/
} catch(PDOException $e) {                                              /*Si erreur attrapée*/
    $error = $e->getMessage();                                          /*Stock le msg de l'erreur dans error*/
}
?>