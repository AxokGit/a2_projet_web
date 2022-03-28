<!DOCTYPE html>

<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){

            $sql = 'SELECT ID_internship ,name_internship, description_internship, duration_internship, remuneration_internship, offer_date_internship, place_number_internship, competences_internship, city_localisation, postal_code_localisation, GROUP_CONCAT(name_promotion SEPARATOR ", ") AS "name_promotion", name_company, email_company, email_company, note FROM internships NATURAL JOIN localisations NATURAL JOIN companies NATURAL JOIN internship_for_promo NATURAL JOIN promotions NATURAL JOIN evaluate INNER JOIN users ON evaluate.ID_user=users.ID_user NATURAL JOIN roles WHERE name_role="Pilote" AND visibility_company="O"';
            $params = [];
            $selected = [];
            if (isset($_GET["localisation"]) && isset($_GET["competences"]) && isset($_GET["confiance"]) && isset($_GET["dateoffre"]) && isset($_GET["duree"]) && isset($_GET["promotion"])){
                if ($_GET["localisation"] != ""){
                    $sql = $sql . " AND city_localisation=:localisation";
                    $params['localisation'] = $_GET["localisation"];
                    $selected['localisation'] = $_GET["localisation"];
                }
                if ($_GET["competences"] != ""){
                    $sql = $sql . " AND competences_internship LIKE :competences";
                    $params['competences'] = '%'.$_GET["competences"].'%';
                    $selected['competences'] = $_GET["competences"];
                }
                if ($_GET["confiance"] != ""){
                    $sql = $sql . " AND note=:note";
                    $params['note'] = $_GET["confiance"];
                    $selected['note'] = $_GET["confiance"];
                }
                if ($_GET["dateoffre"] != ""){
                    $sql = $sql . " AND offer_date_internship >= :dateoffre";
                    $params['dateoffre'] = $_GET["dateoffre"];
                    $selected['dateoffre'] = $_GET["dateoffre"];
                }
                if ($_GET["duree"] != ""){
                    $sql = $sql . " AND duration_internship = :duree";
                    $params['duree'] = $_GET["duree"];
                    $selected['duree'] = $_GET["duree"];
                }
                if ($_GET["promotion"] != ""){
                    $sql = $sql . " AND name_promotion = :promotion";
                    $params['promotion'] = $_GET["promotion"];
                    $selected['promotion'] = $_GET["promotion"];
                }
            }
            if (!isset($_GET["page"])){
                echo "<script>location.href='/offres_stages.php?page=1';</script>";

            }
            $sql = $sql . " GROUP BY ID_internship ORDER BY offer_date_internship ASC LIMIT 5 OFFSET " . strval((intval($_GET["page"])-1)*5) . ";";


            $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission=:perm AND username=:user;');
            $query_perm->execute(['user' => $_COOKIE["username"], 'perm' => "SFx8"]);
            if ($query_perm->rowCount() == 1) {
                $query_internships = $bdd->prepare($sql);
                $query_internships->execute($params);
                $results_internships = $query_internships->fetchALL(PDO::FETCH_OBJ);

                $query_localisations = $bdd->prepare('SELECT city_localisation FROM localisations NATURAL JOIN internships NATURAL JOIN companies WHERE visibility_company="O" GROUP BY city_localisation ORDER BY city_localisation ASC;');
                $query_localisations->execute();
                $results_localisations = $query_localisations->fetchALL(PDO::FETCH_OBJ);

                $query_competences = $bdd->prepare('SELECT competences_internship FROM internships NATURAL JOIN companies WHERE visibility_company="O";');
                $query_competences->execute();
                $results_competences = $query_competences->fetchALL(PDO::FETCH_OBJ);
                $liste_competences = [];
                foreach ($results_competences as $result_competences) {
                    foreach (explode(", ", $result_competences->competences_internship) as $result) {
                        if (!in_array($result, $liste_competences)) {
                            array_push($liste_competences, $result);
                        }
                    }
                }
                
                $query_notes = $bdd->prepare('SELECT note FROM internships NATURAL JOIN companies NATURAL JOIN evaluate INNER JOIN users ON evaluate.ID_user=users.ID_user NATURAL JOIN roles WHERE name_role="Pilote" AND visibility_company="O" GROUP BY note ORDER BY note ASC;');
                $query_notes->execute();
                $results_notes = $query_notes->fetchALL(PDO::FETCH_OBJ);

                $query_durations = $bdd->prepare('SELECT ROUND(duration_internship/30) AS duration_internship FROM internships NATURAL JOIN companies WHERE visibility_company="O" GROUP BY duration_internship ORDER BY duration_internship ASC;');
                $query_durations->execute();
                $results_durations = $query_durations->fetchALL(PDO::FETCH_OBJ);

                $query_promotions = $bdd->prepare('SELECT name_promotion FROM internships NATURAL JOIN internship_for_promo NATURAL JOIN promotions NATURAL JOIN companies WHERE visibility_company="O" GROUP BY name_promotion;');
                $query_promotions->execute();
                $results_promotions = $query_promotions->fetchALL(PDO::FETCH_OBJ);
                
                $query_wishlist = $bdd->prepare('SELECT ID_internship FROM internships NATURAL JOIN wishlist INNER JOIN users ON wishlist.ID_user=users.ID_user WHERE username=:user;');
                $query_wishlist->execute(['user' => $_COOKIE["username"]]);
                $results_wishlist = $query_wishlist->fetchALL(PDO::FETCH_OBJ);
                $wishlist = [];
                foreach ($results_wishlist as $result) {
                    array_push($wishlist, $result->ID_internship);
                }

                $query_candidatures = $bdd->prepare('SELECT candidatures.ID_internship FROM candidatures NATURAL JOIN users INNER JOIN internships ON candidatures.ID_internship=internships.ID_internship WHERE username=:user;');
                $query_candidatures->execute(['user' => $_COOKIE["username"]]);
                $results_candidatures = $query_candidatures->fetchALL(PDO::FETCH_OBJ);
                $candidatures = [];
                foreach ($results_candidatures as $result) {
                    array_push($candidatures, $result->ID_internship);
                }            
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Stages - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/offres_stages.css">
    </head>
    <body>
    <div id="modal_postuler" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title_modal"></div>
                <form class="form_postuler" action="controller/Postuler.php" method="post" enctype="multipart/form-data">
                    <div class="table-container">
                        <div class="info_message"></div>
                        <div class="flex-table">
                            <div class="flex-row-modal name">Curriculum Vitae</div>
                            <div class="flex-row-modal value"><input id="cv" type="file" name="cv" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row-modal name">Lettre de motivation</div>
                            <div class="flex-row-modal value"><input id="lm" type="file" name="lm" required></div>
                        </div>
                    </div>
                    <button type="submit">Envoyer</button>
                </form>
            </div>
        </div>
        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Offres de stages</div>
                    <div class="bubble">
                        <div class="title_bubble">Filtres de recherche :</div>
                        <div class="text_content" style="display:block;">
                            <form class="filter_form" action="/offres_stages.php" method ="GET">
                                <?php
                                if (isset($_GET["page"])) {echo '<input type="hidden" name="page" value="'.$_GET["page"].'" required>';}
                                else {echo '<input type="hidden" name="page" value="1" required>';}
                                ?>
                                
                                <div class="table-container">
                                    <div class="flex-table">
                                        <div class="flex-row name"><label for="localisation">Localisation:</label></div>
                                        <div class="flex-row value">
                                            <select id="localisation" name="localisation">
                                                <option value="" selected>-- Choisir une localisation --</option>
                                                <?php
                                                foreach($results_localisations as $localisation) {
                                                    if (isset($selected['localisation'])) {
                                                        if ($selected['localisation'] == $localisation->city_localisation) {echo '<option value="'.$localisation->city_localisation.'" selected>'.$localisation->city_localisation.'</option>';}
                                                        else {echo '<option value="'.$localisation->city_localisation.'">'.$localisation->city_localisation.'</option>';}
                                                    } else {echo '<option value="'.$localisation->city_localisation.'">'.$localisation->city_localisation.'</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="flex-row name"><label for="competences">Competences:</label></div>
                                        <div class="flex-row value">
                                            <select id="competences" name="competences">
                                                <option value="" selected>-- Choisir une compétence --</option>
                                                <?php
                                                foreach($liste_competences as $competence) {
                                                    if (isset($selected['competences'])) {
                                                        if ($selected['competences'] == $competence) {echo '<option value="'.$competence.'" selected>'.$competence.'</option>';}
                                                        else {echo '<option value="'.$competence.'">'.$competence.'</option>';}
                                                    } else {echo '<option value="'.$competence.'">'.$competence.'</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="flex-row name"><label for="confiance">Niveau de confiance:</label></div>
                                        <div class="flex-row value">
                                            <select id="confiance" name="confiance">
                                                <option value="" selected>-- Choisir une confiance --</option>
                                                <?php
                                                foreach($results_notes as $note) {
                                                    if (isset($selected['note'])) {
                                                        if ($selected['note'] == $note->note) {echo '<option value="'.$note->note.'" selected>'.$note->note.'</option>';}
                                                        else {echo '<option value="'.$note->note.'">'.$note->note.'</option>';}
                                                    } else {echo '<option value="'.$note->note.'">'.$note->note.'</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="flex-row name"><label for="dateoffre">Date de début:</label></div>
                                        <div class="flex-row value">
                                            <input id="dateoffre" type="date" name="dateoffre">
                                        </div>
                                        <div class="flex-row name"><label for="duree">Durée:</label></div>
                                        <div class="flex-row value">
                                            <select id="duree" name="duree">
                                                <option value="" selected>-- Choisir une durée --</option>
                                                <?php
                                                foreach($results_durations as $duration) {
                                                    if (isset($selected['duree'])) {
                                                        if ($selected['duree'] == $duration->duration_internship*30) {echo '<option value="'.($duration->duration_internship*30).'" selected>'.$duration->duration_internship.' mois</option>';}
                                                        else {echo '<option value="'.($duration->duration_internship*30).'">'.$duration->duration_internship.' mois</option>';}
                                                    } else {echo '<option value="'.($duration->duration_internship*30).'">'.$duration->duration_internship.' mois</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="flex-row name"><label for="promotion">Promotion:</label></div>
                                        <div class="flex-row value">
                                            <select id="promotion" name="promotion">
                                                <option value="" selected>-- Choisir une promotion --</option>
                                                <?php
                                                foreach($results_promotions as $promotion) {
                                                    if (isset($selected['promotion'])) {
                                                        if ($selected['promotion'] == $promotion->name_promotion) {echo '<option value="'.$promotion->name_promotion.'" selected>'.$promotion->name_promotion.'</option>';}
                                                        else {echo '<option value="'.$promotion->name_promotion.'">'.$promotion->name_promotion.'</option>';}
                                                    } else {echo '<option value="'.$promotion->name_promotion.'">'.$promotion->name_promotion.'</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit">Rechercher</button>
                                <button type="button" onclick="location.href = '/offres_stages.php';">Réinitialiser</button>
                            </form>
                        </div>   
                    </div>     

                    <?php
                    foreach ($results_internships as $result) {
                    ?>
                        <div class="bubble">
                            <div class="title_bubble"><?= $result->name_internship; ?>
                                <?php
                                if (in_array($result->ID_internship, $wishlist)) {
                                    echo '<div id="'.$result->ID_internship.'_1" class="heart1 heart-hidden"><i class="far fa-heart"></i></div>';
                                    echo '<div id="'.$result->ID_internship.'_2" class="heart2" style="color: red;"><i class="fas fa-heart"></i></div>';
                                } else {
                                    echo '<div id="'.$result->ID_internship.'_1" class="heart1"><i class="far fa-heart"></i></div>';
                                    echo '<div id="'.$result->ID_internship.'_2" class="heart2 heart-hidden" style="color: red;"><i class="fas fa-heart"></i></div>';
                                } ?>
                            </div>
                            
                            <div class="text_content">
                                <div class="divLeft">
                                    <div class="name_company">
                                        <?= $result->name_company; ?>
                                    </div>
                                    <?= $result->city_localisation; ?> <?= $result->postal_code_localisation; ?> - <?= $result->email_company; ?>
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
                                
                                <div class="button">
                                <?php
                                if (!in_array($result->ID_internship, $candidatures)) { ?>
                                    <button class="button_postuler" name_internship="<?=$result->name_internship;?>" id_internship="<?=$result->ID_internship?>">Postuler</button>
                                <?php } else { ?>
                                    <button class="button_postuler" disabled>Déjà postulé</button>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="control_bar">
                        <div class="left">
                            <i class="fas fa-angle-left"></i>
                        </div>
                        <?php
                            if(isset($_GET['page'])){echo $_GET['page'];}
                        ?>
                        <div class="right">
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="assets/js/offres_stages.js"></script>
    </body>
</html>
<?php
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