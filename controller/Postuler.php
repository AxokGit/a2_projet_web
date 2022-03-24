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
            
            


            if (is_uploaded_file($_FILES['cv']['tmp_name']) && is_uploaded_file($_FILES['lm']['tmp_name']) && isset($_POST["ID_internship"])) {
                $query_user = $bdd->prepare('SELECT ID_user, firstname_user, lastname_user, email_user FROM users WHERE username=:user;');
                $query_user->execute(['user' => $_COOKIE["username"]]);
                $results_user = $query_user->fetchALL(PDO::FETCH_OBJ);

                $query_pilots_of_user = $bdd->prepare('SELECT * FROM users NATURAL JOIN user_belong_promo NATURAL JOIN promotions INNER JOIN roles ON roles.ID_role=users.ID_role WHERE (name_role="Pilote" OR name_role="Délégué(e)") AND ID_promotion=(SELECT ID_promotion FROM users NATURAL JOIN user_belong_promo NATURAL JOIN promotions WHERE username:user);');
                $query_pilots_of_user->execute(['user' => $_COOKIE["username"]]);
                $results_pilots_of_user = $query_pilots_of_user->fetchALL(PDO::FETCH_OBJ);

                $location_cv = "/documents/users/".$results_user[0]->ID_user."/"."candidatures/".$_POST["ID_internship"]."/".$_FILES['cv']['name'];
                $location_lm = "/documents/users/".$results_user[0]->ID_user."/"."candidatures/".$_POST["ID_internship"]."/".$_FILES['lm']['name'];

                try {
                    if (!is_dir('../documents/')){
                        mkdir("../documents/", 0700);
                    }
                    if (!is_dir('../documents/users')){
                        mkdir("../documents/users", 0700);
                    }
                    if (!is_dir("../documents/users/".$results_user[0]->ID_user."/")){
                        mkdir("../documents/users/".$results_user[0]->ID_user."/", 0700);
                    }
                    if (!is_dir("../documents/users/".$results_user[0]->ID_user."/"."candidatures/")){
                        mkdir("../documents/users/".$results_user[0]->ID_user."/"."candidatures/", 0700);
                    }
                    if (!is_dir("../documents/users/".$results_user[0]->ID_user."/"."candidatures/".$_POST["ID_internship"])){
                        mkdir("../documents/users/".$results_user[0]->ID_user."/"."candidatures/".$_POST["ID_internship"], 0700);
                    }

                    move_uploaded_file($_FILES['cv']['tmp_name'], "..".$location_cv);
                    move_uploaded_file($_FILES['lm']['tmp_name'], "..".$location_lm);

                    $query_ID_user = $bdd->prepare('INSERT INTO candidatures VALUES (NULL, "1", :location_cv, :location_lm, NULL, NULL, :ID_user, :ID_internship);');
                    $query_ID_user->execute(['location_cv' => $location_cv, 'location_lm' => $location_lm, 'ID_user' => $results_user[0]->ID_user, 'ID_internship' => $_POST["ID_internship"]]);
                    
                    try {
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                    
                        $mail->Username = 'teamspeakcompte@gmail.com';
                        $mail->Password = 'wptjusfmrxurmgcf';

                        $mail->setFrom('teamspeakcompte@gmail.com', 'Ceci Ton Stage');
                        $mail->addAddress('louisdumont4@gmail.com', 'Louis');
                        
                        $mail->IsHTML(true);
                        $mail->Subject = "CTS - ";
                        $mail->Body = 'HTML message body. <b>Gmail</b> SMTP email body.';
                        $mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';
                    
                        $mail->send();
                        echo "Email message sent.";
                    } catch (Exception $e) {
                        echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "<script>location.href='/offres_stages.php';</script>";
                }
                catch (Exception $e) {
                    echo "zut une erreur";
                }
            } else {echo "POST file incomplet";}
        }
    }
}
?>