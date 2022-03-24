<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../assets/vendors/phpmailer/src/Exception.php';
require_once '../assets/vendors/phpmailer/src/PHPMailer.php';
require_once '../assets/vendors/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){

            if (isset($_GET["response"]) && isset($_GET["ID_candidature"])) {
                $response = $_GET["response"];
                $ID_candidature = $_GET["ID_candidature"];

                $query_perm_step2 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx31" AND username=:user;');
                $query_perm_step2->execute(['user' => $_COOKIE["username"]]);
                if ($query_perm_step2->rowCount() >= 1) {
                    $query_check_user_candidature = $bdd->prepare('SELECT * FROM users NATURAL JOIN candidatures WHERE username=:user AND ID_candidature=:ID_candidature;');
                    $query_check_user_candidature->execute(['user' => $_COOKIE["username"], 'ID_candidature' => $ID_candidature]);
                    if ($query_perm_step2->rowCount() >= 1) {
                        if ($response == "y") {
                            $query_update_candidature = $bdd->prepare('UPDATE candidatures SET progression_candidature = "2" WHERE ID_candidature=:ID_candidature;');
                            $query_update_candidature->execute(['ID_candidature' => $ID_candidature]);
                        } else if ($response == "n") {
                            $query_update_candidature = $bdd->prepare('UPDATE candidatures SET progression_candidature = "0" WHERE ID_candidature=:ID_candidature;');
                            $query_update_candidature->execute(['ID_candidature' => $ID_candidature]);
                        }
                        echo "<script>location.href='/candidatures.php';</script>";
                    } else {
                        echo "<script>location.href='/candidatures.php';</script>";
                    }
                } else {
                    header('HTTP/1.0 403 Forbidden');
                    require "controller/403.php";
                }
            }
        }
    }
}
?>