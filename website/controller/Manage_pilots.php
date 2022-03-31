<?php 

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            if (isset($_POST["ID_user"]) && isset($_POST["action"])){
                $ID_user = $_POST["ID_user"];

                if ($_POST["action"] == "delete"){
                    try {
                        $query_delete_pilots = $bdd->prepare('SET @ID_user=:ID_user WHERE name_role="pilot";
                        DELETE FROM manage_company WHERE ID_user=@ID_user AND 0=(SELECT EXISTS ( SELECT * FROM candidatures WHERE ID_user=@ID_user));
                        DELETE FROM evaluate WHERE ID_user=@ID_user AND 0=(SELECT EXISTS ( SELECT * FROM candidatures WHERE ID_user=@ID_user));
                        DELETE FROM wishlist WHERE ID_user=@ID_user AND 0=(SELECT EXISTS ( SELECT * FROM candidatures WHERE ID_user=@ID_user));
                        DELETE FROM user_belong_promo WHERE ID_user=@ID_user AND 0=(SELECT EXISTS ( SELECT * FROM candidatures WHERE ID_user=@ID_user));
                        DELETE FROM users WHERE ID_user=@ID_user AND 0=(SELECT EXISTS ( SELECT * FROM candidatures WHERE ID_user=@ID_user));');
		                $query_delete_pilots->execute(['ID_user' => $ID_user]);
                        echo "true";
                    } catch (Exception $e) {
                        echo "false";
                    }
                }
            }
        }
    }
}