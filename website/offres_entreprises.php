<!DOCTYPE html>

<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){

            $sql = 'SELECT name_company, activity_sector_company, nb_intern_cesi_company, email_company, city_localisation, postal_code_localisation, COUNT(internships.ID_company) AS "number_of_internships", note FROM companies NATURAL JOIN companies_located NATURAL JOIN localisations LEFT JOIN internships ON companies.ID_company=internships.ID_company INNER JOIN evaluate ON companies.ID_company=evaluate.ID_company INNER JOIN users ON evaluate.ID_user=users.ID_user NATURAL JOIN roles WHERE visibility_company="O" AND name_role="Pilote"';
            $params = [];
            $selected = [];
            if (isset($_GET["localisation"]) && isset($_GET["activity_sector"]) && isset($_GET["confiance"]) && isset($_GET["nb_stage"])){
                if ($_GET["localisation"] != ""){
                    $sql = $sql . " AND city_localisation=:localisation";
                    $params['localisation'] = $_GET["localisation"];
                    $selected['localisation'] = $_GET["localisation"];
                }
                if ($_GET["activity_sector"] != ""){
                    echo $_GET["activity_sector"];
                    $sql = $sql . " AND activity_sector_company LIKE :activity_sector";
                    $params['activity_sector'] = '%'.$_GET["activity_sector"].'%';
                    $selected['activity_sector'] = $_GET["activity_sector"];
                }
                if ($_GET["confiance"] != ""){
                    $sql = $sql . " AND note=:note";
                    $params['note'] = $_GET["confiance"];
                    $selected['note'] = $_GET["confiance"];
                }

                $sql = $sql . " GROUP BY internships.ID_company";

                if ($_GET["nb_stage"] != ""){
                    $sql = $sql . " HAVING COUNT(internships.ID_company)=:nb_stage";
                    $params['nb_stage'] = $_GET["nb_stage"];
                    $selected['nb_stage'] = $_GET["nb_stage"];
                }
            } else {
                $sql = $sql . " GROUP BY internships.ID_company";
            }
            $sql = $sql . ";";

            $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission=:perm AND username=:user;');
            $query_perm->execute(['user' => $_COOKIE["username"], 'perm' => "SFx2"]);
            if ($query_perm->rowCount() == 1) {
                $query_companies = $bdd->prepare($sql);
                $query_companies->execute($params);
                $results_companies = $query_companies->fetchALL(PDO::FETCH_OBJ);

                $query_localisations = $bdd->prepare('SELECT city_localisation FROM companies NATURAL JOIN companies_located NATURAL JOIN localisations WHERE visibility_company="O" GROUP BY city_localisation ORDER BY city_localisation ASC;');
                $query_localisations->execute();
                $results_localisations = $query_localisations->fetchALL(PDO::FETCH_OBJ);

                $query_activity_sector = $bdd->prepare('SELECT activity_sector_company FROM companies WHERE visibility_company="O" GROUP BY activity_sector_company;');
                $query_activity_sector->execute();
                $results_activity_sector = $query_activity_sector->fetchALL(PDO::FETCH_OBJ);
                $liste_activity_sector = [];
                foreach ($results_activity_sector as $result_activity_sector) {
                    foreach (explode(", ", $result_activity_sector->activity_sector_company) as $result) {
                        if (!in_array($result, $liste_activity_sector)) {
                            array_push($liste_activity_sector, $result);
                        }
                    }
                }
                
                $query_notes = $bdd->prepare('SELECT note FROM companies NATURAL JOIN evaluate INNER JOIN users ON evaluate.ID_user=users.ID_user NATURAL JOIN roles WHERE name_role="Pilote" GROUP BY note ORDER BY note ASC;');
                $query_notes->execute();
                $results_notes = $query_notes->fetchALL(PDO::FETCH_OBJ);

                $query_nb_stages = $bdd->prepare('SELECT * FROM (SELECT COUNT(internships.ID_company) AS "number_of_internships" FROM companies LEFT JOIN internships ON companies.ID_company=internships.ID_company GROUP BY internships.ID_company) AS T GROUP BY number_of_internships ORDER BY number_of_internships ASC;');
                $query_nb_stages->execute();
                $results_nb_stages = $query_nb_stages->fetchALL(PDO::FETCH_OBJ);
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Entreprises - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/offres_entreprises.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Liste des entreprises</div>
                    <div class="bubble">
                        <div class="title_bubble">Filtres de recherche :</div>
                        <div class="text_content" style="display:block;">
                            <form class="filter_form" action="/offres_entreprises.php" method ="GET">
                                <div class="table-container">
                                    <div class="flex-table">
                                        <div class="flex-row name">Localisation:</div>
                                        <div class="flex-row value">
                                            <select name="localisation">
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

                                        <div class="flex-row name">Secteur d'activité:</div>
                                        <div class="flex-row value">
                                            <select name="activity_sector">
                                                <option value="" selected>-- Choisir un secteur d'activité --</option>
                                                <?php
                                                foreach($liste_activity_sector as $activity_sector) {
                                                    if (isset($selected['activity_sector'])) {
                                                        if ($selected['activity_sector'] == $activity_sector) {echo '<option value="'.$activity_sector.'" selected>'.$activity_sector.'</option>';}
                                                        else {echo '<option value="'.$activity_sector.'">'.$activity_sector.'</option>';}
                                                    } else {echo '<option value="'.$activity_sector.'">'.$activity_sector.'</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="flex-row name">Confiance:</div>
                                        <div class="flex-row value">
                                            <select name="confiance">
                                                <option value="" selected>-- Choisir une confiance --</option>results_durations
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
                                        <div class="flex-row name">Nombre de stages:</div>
                                        <div class="flex-row value">
                                            <select name="nb_stage">
                                                <option value="" selected>-- Choisir un nombre --</option>
                                                <?php
                                                foreach($results_nb_stages as $nb_stages) {
                                                    if (isset($selected['nb_stage'])) {
                                                        if ($selected['nb_stage'] == $nb_stages->number_of_internships) {echo '<option value="'.$nb_stages->number_of_internships.'" selected>'.$nb_stages->number_of_internships.'</option>';}
                                                        else {echo '<option value="'.$nb_stages->number_of_internships.'">'.$nb_stages->number_of_internships.'</option>';}
                                                    } else {echo '<option value="'.$nb_stages->number_of_internships.'">'.$nb_stages->number_of_internships.'</option>';}
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit">Rechercher</button>
                                <button type="button" onclick="location.href = '/offres_entreprises.php';">Réinitialiser</button>
                            </form>
                        </div>   
                    </div>     

                    <?php
                    foreach ($results_companies as $result) {
                    ?>
                        <div class="bubble">
                            <div class="title_bubble"><?= $result->name_company; ?>
                            </div>
                            
                            <div class="text_content">
                                <div class="divLeft">
                                    <div class="name_company">
                                        <?= $result->activity_sector_company; ?>
                                    </div>
                                    <?= $result->city_localisation; ?> <?= $result->postal_code_localisation; ?>
                                    <div class="description">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita maiores nostrum eum asperiores hic repellat perferendis soluta necessitatibus possimus sit! Eaque iste tenetur harum repellendus. Quam culpa dolores modi autem.
                                    </div>
                                </div>
                                <div class="divRight">  
                                    <table>
                                        <div class="table_title">Points importants:</div>
                                        
                                        <tr>
                                            <td><i class="fas fa-id-card-alt"></i></td>
                                            <td>
                                                <?php
                                                    echo $result->nb_intern_cesi_company;
                                                    if($result->nb_intern_cesi_company > 1) {echo " étudiants CESI";}
                                                    else {echo " étudiant CESI";}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-envelope-open-text"></i></td>
                                            <td><?= $result->email_company; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fab fa-buffer"></i></td>
                                            <td>
                                                <?php
                                                    echo $result->number_of_internships;
                                                    if($result->number_of_internships > 1) {echo " offres de stage";}
                                                    else {echo " offre de stage";}
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-handshake"></i></td>
                                            <td>Confiance du pilote : <?= $result->note; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="assets/js/offres_entreprises.js"></script>
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