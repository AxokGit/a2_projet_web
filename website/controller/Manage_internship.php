<?php 

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            if (isset($_POST["ID_internship"]) && isset($_POST["action"])){
                $ID_intership = $_POST["ID_internship"];

                if ($_POST["action"] == "delete"){
                    try {
                        $query_delete_internship = $bdd->prepare('SET @ID_internship=:ID_internship;
                        DELETE FROM internship_for_promo WHERE ID_internship=@ID_internship AND 0=(SELECT EXISTS (SELECT * FROM candidatures WHERE ID_internship=@ID_internship));
                        DELETE FROM wishlist WHERE ID_internship=@ID_internship AND 0=(SELECT EXISTS (SELECT * FROM candidatures WHERE ID_internship=@ID_internship));
                        DELETE FROM internships WHERE ID_internship=@ID_internship AND 0=(SELECT EXISTS (SELECT * FROM candidatures WHERE ID_internship=@ID_internship));');
  		                $query_delete_internship->execute(['ID_internship' => $ID_intership]);
                        echo "true";
                    } catch (Exception $e) {
                        echo "false";
                    }
                }
            } else if (isset($_POST["action"]) && isset($_POST["name"]) && isset($_POST["duration_internship"]) && isset($_POST["remuneration_internship"]) && isset($_POST["offer_date_internship"]) && isset($_POST["place_number_internship"]) && isset($_POST["name_promotion"])) {
                if ($_POST["action"] == "add"){
                    try {
                        $query_add_internship = $bdd->prepare('INSERT INTO internships VALUES (NULL, :name, :duration_internship, :remuneration_internship, :offer_date_internship, :place_number_internship);');
                        $query_add_internship->execute(['name' => $_POST["name"], 'duration_internship' => $_POST["duration_internship"], 'remuneration_internship' => $_POST["remuneration_internship"], 'offer_date_internship' => $_POST["offer_date_internship"], 'place_number_internship' => $_POST["place_number_internship"]]);
                        $query_add_internship = $bdd->prepare('SELECT LAST_INSERT_ID() as "ID_internship";');
                        $query_add_internship->execute();
                        $ID_add_internship = $query_add_internship->fetchALL(PDO::FETCH_OBJ)[0]->ID_internship;
                        $query_add_internship = $bdd->prepare('INSERT INTO internship_for_promo VALUES (:ID_promotion, :ID_internship);');
                        $query_add_internship->execute(['ID_promotion' => $_POST["name_promotion"], 'ID_internship' => $ID_add_internship]);
                        echo "<script>location.href='/gestion_stages.php';</script>";
                    } catch (Exception $e) {
                        echo "false";
                    }
                }
            }
        }
    }
}