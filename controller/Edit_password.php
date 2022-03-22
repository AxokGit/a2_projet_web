<?php
session_start();

if (isset($_SESSION["username"])){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {                            /*Seulement si la method est en POST*/
        if (isset($_POST["actual_pass"]) && isset($_POST["new_pass"]) && isset($_POST["confirm_pass"])){                /*Vérification de l'existance des paramètres*/
            $actual_pass = $_POST["actual_pass"];                           /*Récupération des paramètres*/
            $new_pass = $_POST["new_pass"];
            $confirm_pass = $_POST["confirm_pass"];

            require "ConnexionBDD.php";                                     /*Inclusion de la partie connexion*/
            
            if (!$error) {                                                  /*Si la connexion a été établie sans erreur*/
                $query = $bdd->prepare('SELECT password_user FROM users WHERE username=:user;');
                $query->execute(['user' => $_SESSION["username"]]);                         /*Remplissage de la requete avec les données*/
                $results = $query->fetchALL(PDO::FETCH_OBJ);                /*Retour un résultat sous forme d'objet*/

                if ($query->rowCount() == 1){
                    if ($results[0]->password_user == $actual_pass){
                        if ($new_pass == $confirm_pass){
                            $query_update_pass = $bdd->prepare('UPDATE users SET password_user = :pass WHERE username=:user;');
                            $query_update_pass->execute(['user' => $_SESSION["username"], 'pass' => $new_pass]);
                            echo "true";
                        } else {
                            echo "new_not_match";
                        }
                    }
                    else { echo "actual_not_match";}                                   /*false si mot de passe non correct*/
                } else { echo "false";}
                $bdd = null;                                                /*Fin de connexion*/
            } else {echo "false";}                                          /*Dans le cas d'une erreur de connexion à la BDD, retour false (erreur d'authentification)*/
        }
    }
} else {
    echo "<script>location.href='/';</script>";
}

?>