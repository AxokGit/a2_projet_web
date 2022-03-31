<?php 

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            if (isset($_POST["ID_internship"]) && isset($_POST["action"])){
                $ID_internship = $_POST["ID_internship"];

                if ($_POST["action"] == "delete"){
                    try {
                        $query_delete_internship = $bdd->prepare('SET @ID_internship=:ID_internship;
                        DELETE FROM internship_for_promo WHERE ID_internship=@ID_internship AND 0=(SELECT EXISTS (SELECT * FROM candidatures WHERE ID_internship=@ID_internship));
                        DELETE FROM wishlist WHERE ID_internship=@ID_internship AND 0=(SELECT EXISTS (SELECT * FROM candidatures WHERE ID_internship=@ID_internship));
                        DELETE FROM internships WHERE ID_internship=@ID_internship AND 0=(SELECT EXISTS (SELECT * FROM candidatures WHERE ID_internship=@ID_internship));');
  		                $query_delete_internship->execute(['ID_internship' => $ID_internship]);
                        echo "true";
                    } catch (Exception $e) {
                        echo "false";
                    }
                }
            } 
            if (isset($_POST["action"]) && isset($_POST["name_internship"]) && isset($_POST["description_internship"]) && isset($_POST["duration_internship"]) && isset($_POST["remuneration_internship"]) && isset($_POST["offer_date_internship"]) && isset($_POST["place_number_internship"]) && isset($_POST["competences_internship"]) && isset($_POST["name_promotion"]) && isset($_POST["localisation"]) && isset($_POST["company"])) {
                if ($_POST["action"] == "add"){
                    try {
                        $query_add_internship = $bdd->prepare('INSERT INTO internships VALUES (NULL, :name_internship, :description_internship, :duration_internship, :remuneration_internship, :offer_date_internship, :place_number_internship, :competences_internship, :ID_company, :ID_localisation);');
                        $query_add_internship->execute(['name_internship' => $_POST["name_internship"], 'description_internship' => $_POST["description_internship"], 'duration_internship' => $_POST["duration_internship"], 'remuneration_internship' => $_POST["remuneration_internship"], 'offer_date_internship' => $_POST["offer_date_internship"], 'place_number_internship' => $_POST["place_number_internship"], 'competences_internship' => $_POST["competences_internship"], 'localisation' => $_POST["localisation"], 'company' => $_POST["company"]]);
                        $query_add_internship = $bdd->prepare('SELECT LAST_INSERT_ID() as "ID_internship";');
                        $query_add_internship->execute();
                        $ID_add_internship = $query_add_internship->fetchALL(PDO::FETCH_OBJ)[0]->ID_internship;
                        $query_add_internship = $bdd->prepare('INSERT INTO internship_for_promo VALUES (:ID_internship, (SELECT ID_promotion FROM promotions WHERE name_promotion = :name_promotion);');
                        $query_add_internship->execute(['ID_internship' => $ID_add_internship, 'name_promotion' => $_POST['name_promotion']]);
                        
                        echo "<script>location.href='/gestion_stages.php';</script>";
                    } catch (Exception $e) {
                        echo "false";
                    }
                }
            }
            if (isset($_POST["action"]) && isset($_POST["name_internship"]) && isset($_POST["description_internship"]) && isset($_POST["duration_internship"]) && isset($_POST["remuneration_internship"]) && isset($_POST["offer_date_internship"]) && isset($_POST["place_number_internship"]) && isset($_POST["competences_internship"]) && isset($_POST["name_promotion"]) && isset($_POST["localisation"]) && isset($_POST["company"])) {
                if ($_POST["action"] == "edit"){
                    try {
                        $ID_internship = $_POST["ID_internship"];
                        $query_edit_internship = $bdd->prepare('UPDATE internship_for_promo SET ID_internship = :ID_internship WHERE ID_promotion = :name_promotion;');
                        $query_edit_internship->execute(['name_promotion' => $_POST['name_promotion'], 'ID_internship' => $ID_internship]);
                        
                        $query_edit_internship = $bdd->prepare('UPDATE internships SET name_internship = :name_internship, description_internship = :description_internship, duration_internship = :duration_internship, remuneration_internship = :remuneration_internship, offer_date_internship = :offer_date_internship, place_number_internship = :place_number_internship, competences_internship = :competences_internship, name_promotion = :name_promotion, localisation = :localisation, company = :company WHERE ID_internship=:ID_internship;');
                        $query_edit_internship->execute(['name_internship' => $_POST["name_internship"], 'description_internship' => $_POST["description_internship"], 'duration_internship' => $_POST["duration_internship"], 'remuneration_internship' => $_POST["remuneration_internship"], 'offer_date_internship' => $_POST["offer_date_internship"], 'place_number_internship' => $_POST["place_number_internship"], 'competences_internship' => $_POST["competences_internship"], 'name_promotion' => $_POST["name_promotion"], 'localisation' => $_POST["localisation"], 'company' => $_POST["company"]]);
                        
                        echo "<script>location.href='/gestion_stages.php';</script>";
                    } catch (Exception $e) {
                        echo $e;
                    }
                } else {
                    echo "error";
                }
            }
        }
    }
}