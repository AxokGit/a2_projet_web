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

            if (isset($_POST["ID_candidature"]) && isset($_POST["progression_candidature"])) {
                $ID_candidature = $_POST["ID_candidature"];
                $progression_candidature = $_POST["progression_candidature"];

                if ($progression_candidature == "1"){
                    if (isset($_POST["response"])) {
                        $response = $_POST["response"];

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
                            }
                            echo "<script>location.href='/candidatures.php';</script>";
                        } else {
                            header('HTTP/1.0 403 Forbidden');
                            require "controller/403.php";
                        }
                    }

                } else if ($progression_candidature == "2") {
                    $query_perm_step3 = $bdd->prepare('SELECT ID_user, code_permission, firstname_user, lastname_user FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx32" AND username=:user;');
                    $query_perm_step3->execute(['user' => $_COOKIE["username"]]);
                    $results_perm_step3 = $query_perm_step3->fetchALL(PDO::FETCH_OBJ);
                    if ($query_perm_step3->rowCount() >= 1) {
                        $query_ID_internship = $bdd->prepare('SELECT ID_internship FROM candidatures WHERE ID_candidature=:ID_candidature;');
                        $query_ID_internship->execute(['ID_candidature' => $ID_candidature]);
                        $results_ID_internship = $query_ID_internship->fetchALL(PDO::FETCH_OBJ);
                        if (is_uploaded_file($_FILES['fvss']['tmp_name'])){
                            $query_pilots_of_user = $bdd->prepare('SELECT ID_user, firstname_user, lastname_user, email_user FROM users NATURAL JOIN user_belong_promo NATURAL JOIN promotions INNER JOIN roles ON roles.ID_role=users.ID_role WHERE (name_role="Pilote" OR name_role="Délégué(e)") AND ID_promotion=(SELECT ID_promotion FROM users NATURAL JOIN user_belong_promo NATURAL JOIN promotions WHERE username=:user);');
                            $query_pilots_of_user->execute(['user' => $_COOKIE["username"]]);
                            $results_pilots_of_user = $query_pilots_of_user->fetchALL(PDO::FETCH_OBJ);

                            $location_fvss = "/documents/users/".$results_perm_step3[0]->ID_user."/"."candidatures/".$results_ID_internship[0]->ID_internship."/".$_FILES['fvss']['name'];
                            
                            try {
                                if (!is_dir("../documents/users/".$results_perm_step3[0]->ID_user."/"."candidatures/".$results_ID_internship[0]->ID_internship)){
                                    mkdir("../documents/users/".$results_perm_step3[0]->ID_user."/"."candidatures/".$results_ID_internship[0]->ID_internship, 0700);
                                }

                                move_uploaded_file($_FILES['fvss']['tmp_name'], "..".$location_fvss);

                                $query_update_candidature = $bdd->prepare('UPDATE candidatures SET progression_candidature="3", validation_form_file_path_candidature=:location_fvss WHERE ID_candidature=:ID_candidature;                                ');
                                $query_update_candidature->execute(['location_fvss' => $location_fvss, 'ID_candidature' => $ID_candidature]);
                                
                                try {
                                    $mail->isSMTP();
                                    $mail->Host = 'smtp.gmail.com';
                                    $mail->SMTPAuth = true;
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                    $mail->Port = 587;
                                
                                    $mail->Username = 'teamspeakcompte@gmail.com';
                                    $mail->Password = 'wptjusfmrxurmgcf';
    
                                    $mail->setFrom('teamspeakcompte@gmail.com', 'Ceci Ton Stage');
                                    foreach ($results_pilots_of_user as $result) {
                                        $mail->addAddress($result->email_user, $result->firstname_user." ".$result->lastname_user);
                                    }
                                    
                                    $mail->IsHTML(true);
                                    $mail->Subject = "CTS - Depot de candidature";
                                    $mail->Body = 'Bonjour, une fiche de validation a ete depose par <b>'.$results_perm_step3[0]->firstname_user.' '.$results_perm_step3[0]->lastname_user.'</b>. Vous pouvez des a present vous connecter et apposer votre signature.';
                                    //$mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';
                                
                                    $mail->send();
                                    echo "Email message sent.";
                                } catch (Exception $e) {
                                    echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
                                }

                                echo "<script>location.href='/candidatures.php';</script>";

                            } catch (Exception $e) {
                                echo "zut une erreur";
                            }
                        }
                    }
                }
            }
        }
    }
} else {
    echo "<script>location.href='/';</script>";
}
?>