<?php
session_start();

if (isset($_SESSION["username"])){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {                            /*Seulement si la method est en POST*/
        if (isset($_POST["action"]) && isset($_POST["ID_internship"])){                                /*Vérification de l'existance des paramètres*/
            $action = $_POST["action"];                           /*Récupération des paramètres*/
            $ID_internship = $_POST["ID_internship"];                           /*Récupération des paramètres*/
            
            require "ConnexionBDD.php";                                     /*Inclusion de la partie connexion*/
            if (!$error) {                                                  /*Si la connexion a été établie sans erreur*/
                $query_check_wishlist = $bdd->prepare('SELECT ID_user FROM users NATURAL JOIN wishlist INNER JOIN internships ON wishlist.ID_internship=internships.ID_internship WHERE username=:user AND wishlist.ID_internship=:id_internship;');
                $query_check_wishlist->execute(['user' => $_SESSION["username"], 'id_internship' => $ID_internship]);                         /*Remplissage de la requete avec les données*/
                $results_check_wishlist = $query_check_wishlist->fetchALL(PDO::FETCH_OBJ);                /*Retour un résultat sous forme d'objet*/
                
                if ($action == "remove"){
                    if ($query_check_wishlist->rowCount() == 1){
                        $query_del_wishlist = $bdd->prepare('DELETE FROM wishlist WHERE ID_internship=:id_internship AND ID_user=:id_user;');
                        $query_del_wishlist->execute(['id_internship' => $ID_internship, 'id_user' => $results_check_wishlist[0]->ID_user, ]);
                        echo 'remove_ok';
                    } else { echo "remove_error";}
                } else if ($action == "add"){
                    if ($query_check_wishlist->rowCount() == 0){
                        
                        $query_get_id_user = $bdd->prepare('SELECT ID_user FROM users WHERE username=:user LIMIT 1;');
                        $query_get_id_user->execute(['user' => $_SESSION["username"]]);                         /*Remplissage de la requete avec les données*/
                        $results_get_id_user = $query_get_id_user->fetchALL(PDO::FETCH_OBJ);                /*Retour un résultat sous forme d'objet*/

                        if ($query_get_id_user->rowCount() == 1){
                            $query_del_wishlist = $bdd->prepare('INSERT INTO wishlist (ID_internship, ID_user) VALUES (:id_internship, :id_user);');
                            $query_del_wishlist->execute(['id_internship' => $ID_internship, 'id_user' => $results_get_id_user[0]->ID_user, ]);
                            echo 'add_ok';
                        } else {echo 'add_error';}

                        
                    } else { echo "false";}
                }
                
                $bdd = null;                                                /*Fin de connexion*/
            } else {echo "false";}                                          /*Dans le cas d'une erreur de connexion à la BDD, retour false (erreur d'authentification)*/
        }
    }
} else {
    echo "<script>location.href='/';</script>";
}

?>