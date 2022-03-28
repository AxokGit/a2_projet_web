<?php 

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            if (isset($_POST["ID_company"]) && isset($_POST["action"])){
                $ID_company = $_POST["ID_company"];
                $action = $_POST["action"];

                if ($action == "delete"){
                    try {
                        $query_delete_company = $bdd->prepare('SET @ID_company=:ID_company;DELETE FROM companies_located WHERE ID_company=@ID_company AND 0=(SELECT EXISTS (SELECT * FROM internships WHERE ID_company=@ID_company));DELETE FROM evaluate WHERE ID_company=@ID_company AND 0=(SELECT EXISTS (SELECT * FROM internships WHERE ID_company=@ID_company));DELETE FROM manage_company WHERE ID_company=@ID_company AND 0=(SELECT EXISTS (SELECT * FROM internships WHERE ID_company=@ID_company));DELETE FROM companies WHERE ID_company=@ID_company AND 0=(SELECT EXISTS (SELECT * FROM internships WHERE ID_company=@ID_company));');
		                $query_delete_company->execute(['ID_company' => $ID_company]);
                        echo "true";
                    } catch (Exception $e) {
                        echo "false";
                    }
                } else if ($action == "add"){
                    echo "bonour";
                    if (isset($_POST["name"]) && isset($_POST["activity_sector"]) && isset($_POST["nb_intern_cesi"]) && isset($_POST["note"]) && isset($_POST["email"])) {
                        echo "oui";
                    }
                    /*try {
                        $query_add_company = $bdd->prepare('INSERT INTO companies VALUES (NULL, :name, :activity_sector, :nb_intern_CESI, :visibility, :email);');
		                $query_add_company->execute();
                        echo "true";
                    } catch (Exception $e) {
                        echo "false";
                    }*/
                }
            }
        }
    }
}