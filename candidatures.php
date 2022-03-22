<!DOCTYPE html>

<?php
session_start();

if (isset($_SESSION["username"])){
    $sql = 'SELECT ID_candidature, progression_candidature, cv_file_path_candidature, lm_file_path_candidature, validation_form_file_path_candidature, internship_agreement_file_path_candidature, name_internship, name_company, ID_user, username, city_localisation, postal_code_localisation FROM candidatures NATURAL JOIN users INNER JOIN internships ON candidatures.ID_internship=internships.ID_internship INNER JOIN localisations ON internships.ID_localisation=localisations.ID_localisation NATURAL JOIN companies WHERE username=:user ORDER BY offer_date_internship ASC;';

    include "controller/ConnexionBDD.php";
    if (!$error) {
        $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE username=:user;');
        $query_perm->execute(['user' => $_SESSION["username"]]);
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
            $query_candidatures->execute(['user' => $_SESSION["username"]]);
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
                    if ($query_candidatures->rowCount() >= 1) {
                        foreach ($query_candidatures as $result) {
                    ?>
                        <div class="bubble">
                            <div class="title_bubble"><?= $result->name_internship; ?>
                                    <div id="<?= $result->ID_internship ?>_1" class="heart1 heart-hidden"><i class="far fa-heart"></i></div>
                                    <div id="<?= $result->ID_internship ?>_2" class="heart2" style="color: red;"><i class="fas fa-heart"></i></div>
                            </div>
                            <div class="text_content">
                                <div class="divLeft">
                                    <div class="name_company">
                                        <?= $result->name_company; ?>
                                    </div>
                                    <?= $result->city_localisation; ?> <?= $result->postal_code_localisation; ?>
                                    <div class="description">
                                        <?= $result->description_internship; ?>
                                    </div>
                                </div>
                                <div class="divRight">  
                                    <table>
                                        <div class="table_title">Points importants:</div>
                                        
                                        <tr>
                                            <td><i class="far fa-clock"></i></td>
                                            <td><?= $result->duration_internship; ?> jours</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-euro-sign"></i></td>
                                            <td><?= $result->remuneration_internship; ?> €/h</td>
                                        </tr>
                                        <tr>
                                            <td><i class="far fa-calendar-alt"></i></td>
                                            <td><?= $result->offer_date_internship; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-graduation-cap"></i></td>
                                            <td><?= $result->name_promotion; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-user-alt"></i></td>
                                            <td>
                                                <?php
                                                echo $result->place_number_internship;
                                                if($result->place_number_internship > 1) {echo " places disponibles";}
                                                else {echo " place disponible";}?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-chart-bar"></i></td>
                                            <td><?= $result->competences_internship; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-handshake"></i></td>
                                            <td>Confiance du pilote : <?= $result->note; ?></td>
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
                            Il n'y a aucun favori dans les parages &nbsp;<i class="fas fa-wind"></i>
                        </div>
                    </div>
                    <?php } ?>
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
    }
} else {
    echo "<script>location.href='/';</script>";
}
?>