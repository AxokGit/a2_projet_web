<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {                            /*Seulement si la method est en POST*/
                if (isset($_POST["actual_pass"]) && isset($_POST["new_pass"]) && isset($_POST["confirm_pass"])){                /*Vérification de l'existance des paramètres*/
                    $actual_pass = $_POST["actual_pass"];                           /*Récupération des paramètres*/
                    $new_pass = $_POST["new_pass"];
                    $confirm_pass = $_POST["confirm_pass"];

                    $query = $bdd->prepare('SELECT password_user FROM users WHERE username=:user;');
                    $query->execute(['user' => $_COOKIE["username"]]);                         /*Remplissage de la requete avec les données*/
                    $results = $query->fetchALL(PDO::FETCH_OBJ);                /*Retour un résultat sous forme d'objet*/

                    if ($query->rowCount() == 1){
                        if ($results[0]->password_user == $actual_pass){
                            if ($new_pass == $confirm_pass){
                                $query_update_pass = $bdd->prepare('UPDATE users SET password_user = :pass WHERE username=:user;');
                                $query_update_pass->execute(['user' => $_COOKIE["username"], 'pass' => $new_pass]);
                                echo "true";
                            } else {
                                echo "new_not_match";
                            }
                        }
                        else { echo "actual_not_match";}                                   /*false si mot de passe non correct*/
                    } else { echo "false";}
                    $bdd = null;                                                /*Fin de connexion*/
                }
            }
        } else {
            echo "<script>location.href='/';</script>";
        }
    }
}

?>