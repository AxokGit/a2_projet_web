<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {                            /*Seulement si la method est en POST*/
    if (isset($_POST["user"]) && isset($_POST["pass"])){                /*Vérification de l'existance des paramètres*/
        $user = $_POST["user"];                                         /*Récupération des paramètres*/
        $pass = $_POST["pass"];
        require "ConnexionBDD.php";                                     /*Inclusion de la partie connexion*/

        if (!$error) {                                                  /*Si la connexion a été établie sans erreur*/
            $query = $bdd->prepare('SELECT password_user FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user;');
            $query->execute(['user' => $user]);                         /*Remplissage de la requete avec les données*/
            $results = $query->fetchALL(PDO::FETCH_OBJ);                /*Retour un résultat sous forme d'objet*/

            if ($query->rowCount() == 1){
                if ($results[0]->password_user == $pass){
                    echo "true";                                        /*Retourne true si le mot de passe correspond*/
                    $_SESSION["username"] = $user;
                    /*Requête permettant la mise à jour des champs : connection_count et last_connection de l'utilisateur*/
                    $query = $bdd->prepare('UPDATE users SET connection_count = connection_count + 1, last_connection = CURDATE() WHERE username = :user;');
                    $query->execute(['user' => $user]);                 /*Execution de la requête*/
                }
                else { echo "false";}                                   /*false si mot de passe non correct*/
            } else { echo "false";}
            $bdd = null;                                                /*Fin de connexion*/
        } else {echo "false";}                                          /*Dans le cas d'une erreur de connexion à la BDD, retour false (erreur d'authentification)*/
    }
}
?>