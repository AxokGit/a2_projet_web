<!DOCTYPE html>

<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){

            $sql = 'SELECT ID_candidature, progression_candidature, cv_file_path_candidature, lm_file_path_candidature, validation_form_file_path_candidature, internship_agreement_file_path_candidature, name_internship, name_company, email_company, ID_user, username, city_localisation, postal_code_localisation FROM candidatures NATURAL JOIN users INNER JOIN internships ON candidatures.ID_internship=internships.ID_internship INNER JOIN localisations ON internships.ID_localisation=localisations.ID_localisation NATURAL JOIN companies WHERE username=:user ORDER BY offer_date_internship ASC;';
            $query_perm = $bdd->prepare('SELECT username, code_permission, name_role FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE username=:user;');
            $query_perm->execute(['user' => $_COOKIE["username"]]);
            $results_perm = $query_perm->fetchALL(PDO::FETCH_OBJ);
            if ($query_perm->rowCount() >= 1) {
                $showCandidatures = false;

                foreach ($results_perm as $result) {
                    if ($result->code_permission == "SFx29" || $result->code_permission == "SFx30" || $result->code_permission == "SFx31" || $result->code_permission == "SFx32" || $result->code_permission == "SFx33" || $result->code_permission == "SFx34" || $result->code_permission == "SFx35"){
                        $showCandidatures = true;
                    }
                }

                if ($showCandidatures){
                    $query_candidatures = $bdd->prepare($sql);
                    $query_candidatures->execute(['user' => $_COOKIE["username"]]);
                    $results_candidatures = $query_candidatures->fetchALL(PDO::FETCH_OBJ);
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Candidatures - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/candidatures.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Candidatures</div>
                    <?php
                    if ($results_perm[0]->name_role == "Etudiant") {
                        if ($query_candidatures->rowCount() >= 1) {
                            foreach ($results_candidatures as $result) {
                    ?>
                        <div class="bubble">
                            <div class="title_bubble"><?= $result->name_internship; ?></div>
                            <div class="text_content">
                                <div class="divLeft">
                                    <div class="title_in_bubble">
                                        <?= $result->name_company; ?>
                                    </div>
                                    <?= $result->city_localisation; ?> <?= $result->postal_code_localisation; ?> - <?= $result->email_company; ?>
                                    <div class="description">
                                        <?php
                                        if ($result->progression_candidature == 0) {echo "Vous avez indiquez que l'entreprise a répondu négativemet à votre demande.<br>Ne perdez pas espoir :)";} 
                                        else if ($result->progression_candidature == 1) {?>
                                            <div class="title_in_bubble_status">Status</div>
                                            <div class="text_stat">La candidature est actuellement en attente de réponse de l'entreprise.</div>
                                            <div class="title_in_bubble_status">Prochaine étape:</div>
                                            A partir de l'adresse email de l'entreprise, nous vous invitons à la contacter afin de leur adresser votre CV et lettre de motivation.
                                            <br>
                                            
                                            <?php
                                            $query_perm_step2 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx31" AND username=:user;');
                                            $query_perm_step2->execute(['user' => $_COOKIE["username"]]);
                                            if ($query_perm_step2->rowCount() >= 1) {
                                                echo '<br>Est-ce que la réponse de la part de l\'entreprise est positive ?<br>';
                                                echo '<button class="button" onclick="location.href=\'/controller/Manage_candidatures.php/?response=y&ID_candidature='.$result->ID_candidature.'\';">Oui</button>';
                                                echo '<button class="button" onclick="location.href=\'/controller/Manage_candidatures.php/?response=n&ID_candidature='.$result->ID_candidature.'\';">Non</button>';
                                            } else {
                                                echo '<br>Vous n\'avez pas la permission d\'entrer la réponse de l\'entreprise... Veuillez contacter votre pilote ou délégué(e).';
                                            }
                                            ?>
                                        <?php
                                        } else if ($result->progression_candidature == 2) { ?>
                                            <div class="title_in_bubble_status">Status</div>
                                                <div class="text_stat">La candidature est actuellement en attendre d'une fiche de validation de sujet de stage complétée et signée par l'entreprise.</div>
                                                <div class="title_in_bubble_status">Prochaine étape:</div>
                                                A partir de l'adresse email de l'entreprise, nous vous invitons à la contacter afin de leur adresser votre CV et lettre de motivation.
                                                <br>
                                                
                                                <?php
                                                $query_perm_step2 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx31" AND username=:user;');
                                                $query_perm_step2->execute(['user' => $_COOKIE["username"]]);
                                                if ($query_perm_step2->rowCount() >= 1) {
                                                    echo '<br>Est-ce que la réponse de la part de l\'entreprise est positive ?<br>';
                                                    echo '<button class="button" onclick="location.href=\'/controller/Manage_candidatures.php/?response=y&ID_candidature='.$result->ID_candidature.'\';">Oui</button>';
                                                    echo '<button class="button" onclick="location.href=\'/controller/Manage_candidatures.php/?response=n&ID_candidature='.$result->ID_candidature.'\';">Non</button>';
                                                } else {
                                                    echo '<br>Vous n\'avez pas la permission d\'entrer la réponse de l\'entreprise... Veuillez contacter votre pilote ou délégué(e).';
                                                }
                                                ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="divRight">  
                                    <table>
                                        <div class="table_title">Fichiers en lien avec la candidature:</div>
                                        <tr>
                                            <td>Curriculum Vitae :</td>
                                            <td>
                                                <?php
                                                    if($result->cv_file_path_candidature != "") {echo 'Déposé <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lettre de motivation :</td>
                                            <td>
                                                <?php
                                                    if($result->lm_file_path_candidature != "") {echo 'Déposé <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fiche de validation :</td>
                                            <td>
                                                <?php
                                                    if($result->validation_form_file_path_candidature != "") {echo 'Déposé <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Convention de stage :</td>
                                            <td>
                                                <?php
                                                    if($result->internship_agreement_file_path_candidature != "") {echo 'Déposé <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php 
                            } 
                        } else {
                    ?>
                    <div class="bubble">
                        <div class="title_bubble">Tout est vide...</div>
                        <div class="text_content">
                            Vous n'avez aucune candidature en cours &nbsp;<i class="fas fa-wind"></i>
                        </div>
                    </div>
                    <?php
                        }
                    } else { //Ici le contenu que le tuteur doit voir
                        // partie très compliquée :/
                    } ?>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="assets/js/candidatures.js"></script>
    </body>
</html>
<?php
                } else {
                    header('HTTP/1.0 403 Forbidden');
                    require "controller/403.php";
                }
            } else {
                header('HTTP/1.0 403 Forbidden');
                require "controller/403.php";
            }
        } else {
            echo "<script>location.href='/';</script>";
        }
    } else {
        echo "<script>location.href='/';</script>";
    }
} else {
    echo "<script>location.href='/';</script>";
}
?>