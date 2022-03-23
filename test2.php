<?php
session_start();

if (isset($_SESSION["username"])){
    include "controller/ConnexionBDD.php";
    if (!$error) {
        $query_ID_user = $bdd->prepare('SELECT ID_user FROM users WHERE username=:user;');
        $query_ID_user->execute(['user' => $_SESSION["username"]]);
        $ID_user = $query_ID_user->fetchALL(PDO::FETCH_OBJ)[0]->ID_user;

        $location_cv = "./documents/users/".$ID_user."/".$_FILES['cv']['name'];
        $location_lm = "./documents/users/".$ID_user."/".$_FILES['lm']['name'];

        if (!is_dir('./documents/')){
            mkdir("./documents/", 0700);
        }
        if (!is_dir('./documents/users')){
            mkdir("./documents/users", 0700);
        }
        if (!is_dir("./documents/users/".$ID_user)){
            mkdir("./documents/users/".$ID_user, 0700);
        }


        try {
            move_uploaded_file($_FILES['cv']['tmp_name'], $location_cv);
            move_uploaded_file($_FILES['lm']['tmp_name'], $location_lm);
            echo "tout est ok";
        }
        catch (Exception $e) {
            echo "zut une erreur";
        }
    }
}
?>