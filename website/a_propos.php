<?php

include_once("controller/smarty/libs/Smarty.class.php");

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
    require "controller/ConnexionBDD.php";
    if (!$error) {
        $query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles 
NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE 
code_permission="SFx1" AND username=:user AND password_user=:password_user;');
        $query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
        if ($query_check_cookie->rowCount() == 1) {
            $smarty = new Smarty();
            $smarty->template_dir = 'views';
            $smarty->compile_dir = 'views/temp';

            $smarty->assign("title", "A propos - CTS");
            $smarty->assign("description", "Cette page contient les informations 
relatives au site internet");
            $smarty->assign("page_title", "À propos de Cesi Ton Stage");

            $bubbles = [];
            $bubbles['Vision'] = "Créer des opportunités pour les étudiants CESI de la France entière et plus.";
            $bubbles['Mission'] = "La mission de CTS est simple : mettre en relation des entreprises et des 
étudiants du monde entier pour trouver la meilleure offre et le meilleur stagiaire  .";
            $bubbles['Qui sommes-nous ?'] = "Un groupe de projet composé de Justine ADLER, 
Louis DUMONT, Nathan KISS et Nicolas WIRA.";
            $smarty->assign("bubbles", $bubbles);

            $smarty->display("a_propos.tpl");
        }
    }
}