<!DOCTYPE html>

<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){

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
                    if ($results_perm[0]->name_role == "Etudiant") {
                        $sql = 'SELECT ID_candidature, progression_candidature, cv_file_path_candidature, lm_file_path_candidature, validation_form_file_path_candidature, internship_agreement_file_path_candidature, name_internship, name_company, email_company, ID_user, username, city_localisation, postal_code_localisation FROM candidatures NATURAL JOIN users INNER JOIN internships ON candidatures.ID_internship=internships.ID_internship INNER JOIN localisations ON internships.ID_localisation=localisations.ID_localisation NATURAL JOIN companies WHERE username=:user ORDER BY offer_date_internship ASC;';
                        $query_candidatures = $bdd->prepare($sql);
                        $query_candidatures->execute(['user' => $_COOKIE["username"]]);
                        $results_candidatures = $query_candidatures->fetchALL(PDO::FETCH_OBJ);
                    } else if ($results_perm[0]->name_role == "Pilote" || $results_perm[0]->name_role == "D??l??gu??(e)" || $results_perm[0]->name_role == "Administrateur") {
                        $sql = 'SELECT ID_candidature, ID_user, username, firstname_user, lastname_user, progression_candidature, cv_file_path_candidature, lm_file_path_candidature, validation_form_file_path_candidature, internship_agreement_file_path_candidature, name_internship, name_company, email_company, city_localisation, postal_code_localisation FROM users NATURAL JOIN user_belong_promo NATURAL JOIN promotions NATURAL JOIN roles NATURAL JOIN candidatures INNER JOIN internships ON candidatures.ID_internship=internships.ID_internship NATURAL JOIN companies INNER JOIN localisations ON internships.ID_localisation=localisations.ID_localisation WHERE name_role="Etudiant" AND ID_promotion IN (SELECT ID_promotion FROM users NATURAL JOIN user_belong_promo NATURAL JOIN promotions WHERE username=:user) ORDER BY lastname_user ASC;';
                        $query_candidatures = $bdd->prepare($sql);
                        $query_candidatures->execute(['user' => $_COOKIE["username"]]);
                        $results_candidatures = $query_candidatures->fetchALL(PDO::FETCH_OBJ);
                    }
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Candidatures - CTS</title>
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/candidatures.css">
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
                                        if ($result->progression_candidature == 0) {echo "Vous avez indiquez que l'entreprise a r??pondu n??gativemet ?? votre demande.<br>Ne perdez pas espoir :)";} 
                                        else if ($result->progression_candidature == 1) {?>
                                            <div class="title_in_bubble_status">Status <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La candidature est actuellement en attente de r??ponse de l'entreprise.</div>
                                            <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                            A partir de l'adresse email de l'entreprise, nous vous invitons ?? la contacter afin de leur adresser votre CV et lettre de motivation.
                                            <br>
                                            
                                            <?php
                                            $query_perm_step2 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx31" AND username=:user;');
                                            $query_perm_step2->execute(['user' => $_COOKIE["username"]]);
                                            if ($query_perm_step2->rowCount() >= 1) {
                                                echo '<br>Est-ce que la r??ponse de l\'entreprise est positive ?<br>';
                                                ?>
                                                <form action="/controller/Manage_candidatures.php" method="post">
                                                    <input type="hidden" name="response" value="y">
                                                    <input type="hidden" name="ID_candidature" value="<?=$result->ID_candidature?>">
                                                    <input type="hidden" name="progression_candidature" value="<?=$result->progression_candidature?>">
                                                    <button class="button" type="submit">Oui</button>
                                                </form>
                                                <form action="/controller/Manage_candidatures.php" method="post">
                                                    <input type="hidden" name="response" value="n">
                                                    <input type="hidden" name="ID_candidature" value="<?=$result->ID_candidature?>">
                                                    <input type="hidden" name="progression_candidature" value="<?=$result->progression_candidature?>">
                                                    <button class="button" type="submit">Non</button>
                                                </form>
                                            <?php
                                            } else {
                                                echo '<br>Vous n\'avez pas la permission d\'entrer la r??ponse de l\'entreprise... Veuillez contacter votre pilote ou d??l??gu??(e).';
                                            }
                                            ?>
                                        <?php
                                        } else if ($result->progression_candidature == 2) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                                <div class="text_stat">La candidature est actuellement en attendre d'une fiche de validation de sujet de stage compl??t??e et sign??e par l'entreprise.</div>
                                                <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                                A pr??sent, vous pouvez envoyer la fiche de validation ?? l'entreprise pour ensuite la d??poser compl??t??e ci-dessous.
                                                <br>
                                                
                                                <?php
                                                $query_perm_step3 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx32" AND username=:user;');
                                                $query_perm_step3->execute(['user' => $_COOKIE["username"]]);
                                                if ($query_perm_step3->rowCount() >= 1) { ?>
                                                    <br>
                                                    <form action="controller/Manage_candidatures.php" method="post" enctype="multipart/form-data">
                                                        <div class="flex-row-modal value"><input id="fvss" type="file" name="fvss" required></div>
                                                        <input type="hidden" name="ID_candidature" value="<?= $result->ID_candidature ?>" required>
                                                        <input type="hidden" name="progression_candidature" value="<?= $result->progression_candidature ?>" required>
                                                        <br>
                                                        <button class="button" type="submit">Envoyer</button>
                                                    </form>
                                                <?php } else {
                                                    echo '<br>Vous n\'avez pas la permission d\'entrer la r??ponse de l\'entreprise... Veuillez contacter votre pilote ou d??l??gu??(e).';
                                                }
                                        } else if ($result->progression_candidature == 3) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La candidature est actuellement en attendre d'une fiche de validation de sujet de stage sign??e par le pilote.</div>
                                            <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                            Le pilote a ??t?? averti de votre d??p??t. Il signera votre fiche de validation
                                            <br>
                                        <?php
                                        } else if ($result->progression_candidature == 4) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La convention de stage est en cours de r??daction.</div>
                                        <?php
                                        } else if ($result->progression_candidature == 5) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La convention a ??t?? envoy?? ?? l'entreprise. Sa r??ponse est en attente</div>
                                        <?php
                                        } else if ($result->progression_candidature == 6) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">Le stage est confirm?? !</div>
                                        <?php
                                        } ?>
                                        
                                    </div>
                                </div>
                                <div class="divRight">  
                                    <table>
                                        <div class="table_title">Fichiers en lien avec la candidature:</div>
                                        <tr>
                                            <td>Curriculum Vitae :</td>
                                            <td>
                                                <?php
                                                    if($result->cv_file_path_candidature != "") {echo 'D??pos?? <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lettre de motivation :</td>
                                            <td>
                                                <?php
                                                    if($result->lm_file_path_candidature != "") {echo 'D??pos?? <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fiche de validation :</td>
                                            <td>
                                                <?php
                                                    if($result->validation_form_file_path_candidature != "") {
                                                        if ($result->progression_candidature <= 3) {
                                                            echo 'D??pos??e non sign??e <i style="color:green;" class="fas fa-check"></i>';
                                                        } else {
                                                            echo 'D??pos??e et sign??e <i style="color:green;" class="fas fa-check"></i>';
                                                        }
                                                    }
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Convention de stage :</td>
                                            <td>
                                                <?php
                                                    if($result->internship_agreement_file_path_candidature != "") {echo 'D??pos?? <i style="color:green;" class="fas fa-check"></i>';}
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
                    } else if ($results_perm[0]->name_role == "Pilote" || $results_perm[0]->name_role == "D??l??gu??(e)" || $results_perm[0]->name_role == "Administrateur") { //Ici le contenu que le tuteur doit voir
                        if ($query_candidatures->rowCount() >= 1) {
                            foreach ($results_candidatures as $result) {
                    ?>
                        <div class="bubble">
                            <div class="title_bubble"><?= $result->name_internship; ?></div>
                            <div class="text_content">
                                <div class="divLeft">
                                    <div class="title_in_bubble">
                                        <?= $result->lastname_user ?> <?= $result->firstname_user ?>
                                    </div>
                                    <?= $result->city_localisation; ?> <?= $result->postal_code_localisation; ?> - <?= $result->email_company; ?>
                                    <div class="description">
                                        <?php
                                        if ($result->progression_candidature == 0) {echo "Vous avez indiquez que l'entreprise a r??pondu n??gativemet ?? votre demande.<br>Ne perdez pas espoir :)";} 
                                        else if ($result->progression_candidature == 1) {?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La candidature est actuellement en attente de r??ponse de l'entreprise.</div>
                                            <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                            L'??tudiant a envoy?? son CV et sa lettre de motivation et est dans l'attente d'une r??ponse de l'entreprise, il indiquera la r??ponse de cette derni??re d??s que possible.

                                        <?php
                                        } else if ($result->progression_candidature == 2) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                                <div class="text_stat">La candidature est actuellement en attendre d'une fiche de validation de sujet de stage compl??t??e et sign??e par l'entreprise.</div>
                                                <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                                L'??tudiant a envoy?? la fiche de validation de sujet de stage ?? l'entreprise. Vous serez aerti par mail lorsqu'il la d??posera.
                                                <br>
                                   
                                        <?php
                                        } else if ($result->progression_candidature == 3) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La candidature est actuellement en attendre d'une fiche de validation de sujet de stage sign??e par le pilote.</div>
                                            <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                            Vous pouvez d??s ?? pr??sent signer la fiche de validation de sujet de stage que l'??tudiant a d??pos??.
                                            <br>
                                            <?php
                                                $query_perm_step3 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx33" AND username=:user;');
                                                $query_perm_step3->execute(['user' => $_COOKIE["username"]]);
                                                if ($query_perm_step3->rowCount() >= 1) { ?>
                                                    <br>
                                                    <form action="controller/Manage_candidatures.php" method="post" enctype="multipart/form-data">
                                                        <div class="flex-row-modal value"><input id="fvss_signee" type="file" name="fvss_signee" required></div>
                                                        <input type="hidden" name="ID_candidature" value="<?= $result->ID_candidature ?>" required>
                                                        <input type="hidden" name="ID_user" value="<?= $result->ID_user ?>" required>
                                                        <input type="hidden" name="progression_candidature" value="<?= $result->progression_candidature ?>" required>
                                                        <br>
                                                        <button class="button" type="submit">Envoyer</button>
                                                    </form>
                                                <?php } else {
                                                    echo '<br>Vous n\'avez pas la permission de d??poser la fiche de validation de sujet de stage sign??e... Veuillez contacter votre pilote ou d??l??gu??(e).';
                                                } ?>

                                        <?php
                                        } else if ($result->progression_candidature == 4) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La candidature est actuellement en attendre d'une convention de stage.</div>
                                            <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                            Vous pouvez maintenant d??poser la convention de stage de l'??tudiant.
                                            <br>
                                            <?php
                                                $query_perm_step3 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx34" AND username=:user;');
                                                $query_perm_step3->execute(['user' => $_COOKIE["username"]]);
                                                if ($query_perm_step3->rowCount() >= 1) { ?>
                                                    <br>
                                                    <form action="controller/Manage_candidatures.php" method="post" enctype="multipart/form-data">
                                                        <div class="flex-row-modal value"><input id="convention" type="file" name="convention" required></div>
                                                        <input type="hidden" name="ID_candidature" value="<?= $result->ID_candidature ?>" required>
                                                        <input type="hidden" name="ID_user" value="<?= $result->ID_user ?>" required>
                                                        <input type="hidden" name="progression_candidature" value="<?= $result->progression_candidature ?>" required>
                                                        <br>
                                                        <button class="button" type="submit">Envoyer</button>
                                                    </form>
                                                <?php } else {
                                                    echo '<br>Vous n\'avez pas la permission d\'envoyer la convention de stage... Veuillez contacter votre pilote ou d??l??gu??(e).';
                                                } ?>
                                        <?php
                                        } else if ($result->progression_candidature == 5) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">La convention de stage a ??t?? envoy?? ?? l'entreprise.</div>
                                            <div class="title_in_bubble_status">Prochaine ??tape:</div>
                                            
                                            <?php
                                                $query_perm_step6 = $bdd->prepare('SELECT code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx35" AND username=:user;');
                                                $query_perm_step6->execute(['user' => $_COOKIE["username"]]);
                                                if ($query_perm_step6->rowCount() >= 1) {
                                                    echo 'Validez lorsque vous aurez re??u la convention de stage';
                                            ?>
                                                    <form action="/controller/Manage_candidatures.php" method="post">
                                                        <input type="hidden" name="response" value="y">
                                                        <input type="hidden" name="ID_candidature" value="<?=$result->ID_candidature?>">
                                                        <input type="hidden" name="progression_candidature" value="<?=$result->progression_candidature?>">
                                                        <button class="button" type="submit">Valider</button>
                                                    </form>
                                            <?php } else {
                                                echo '<br>Vous n\'avez pas la permission de confirmer la convention de stage... Veuillez contacter votre pilote ou d??l??gu??(e).';
                                            } ?>
                                        <?php
                                        } else if ($result->progression_candidature == 6) { ?>
                                            <div class="title_in_bubble_status">Statut <?=$result->progression_candidature ?></div>
                                            <div class="text_stat">Le stage est confirm?? !</div>
                                        <?php
                                        } ?>
                                    </div>
                                </div>
                                <div class="divRight">  
                                    <table>
                                        <div class="table_title">Fichiers en lien avec la candidature:</div>
                                        <tr>
                                            <td>Curriculum Vitae :</td>
                                            <td>
                                                <?php
                                                    if($result->cv_file_path_candidature != "") {echo 'D??pos?? <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Lettre de motivation :</td>
                                            <td>
                                                <?php
                                                    if($result->lm_file_path_candidature != "") {echo 'D??pos??e <i style="color:green;" class="fas fa-check"></i>';}
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fiche de validation :</td>
                                            <td>
                                                <?php
                                                    if($result->validation_form_file_path_candidature != "") {
                                                        if ($result->progression_candidature <= 3) {
                                                            echo 'D??pos??e non sign??e <i style="color:green;" class="fas fa-check"></i>';
                                                        } else {
                                                            echo 'D??pos??e et sign??e <i style="color:green;" class="fas fa-check"></i>';
                                                        }
                                                    }
                                                    else {echo 'Non disponible <i style="color:red;" class="fas fa-times"></i>';}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Convention de stage :</td>
                                            <td>
                                                <?php
                                                    if($result->internship_agreement_file_path_candidature != "") {echo 'D??pos?? <i style="color:green;" class="fas fa-check"></i>';}
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
                            Aucun de vos ??tudiants n'a postul?? ?? un stage &nbsp;<i class="fas fa-wind"></i>
                        </div>
                    </div>
                    <?php
                        }
                    } ?>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="http://ctsstatic.fr/assets/js/candidatures.js"></script>
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