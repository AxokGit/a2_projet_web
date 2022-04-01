<!DOCTYPE html>

<!DOCTYPE html>

<?php 
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
    require "controller/ConnexionBDD.php";
    if (!$error) {
        $query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
        $query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
        if ($query_check_cookie->rowCount() == 1){
            $sql = 'SELECT internships.ID_internship, name_internship, description_internship, duration_internship, remuneration_internship, offer_date_internship, place_number_internship, competences_internship, GROUP_CONCAT(promotions.name_promotion SEPARATOR ", ") AS "name_promotion", ID_company, name_company, ID_localisation, promotions.ID_promotion FROM internships NATURAL JOIN companies NATURAL JOIN localisations LEFT JOIN internship_for_promo ON internships.ID_internship = internship_for_promo.ID_internship LEFT JOIN promotions ON internship_for_promo.ID_promotion = promotions.ID_promotion GROUP BY internships.ID_internship;';

            $query_perm_nav = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE username = :user;');
            $query_perm_nav->execute(['user' => $_COOKIE["username"]]);
            $results_nav = $query_perm_nav->fetchALL(PDO::FETCH_OBJ);
            if ($query_perm_nav->rowCount() >= 1) {
                $gestion_Stages_create = false;
                $gestion_Stages_edit = false;
                $gestion_Stages_delete = false;
                $gestion_Stages_stat = false;
                
                foreach($results_nav as $result){
                    if ($result->code_permission == "SFx3"){
                        $gestion_Stages_create = true;
                    }
                    if ($result->code_permission == "SFx4"){
                        $gestion_Stages_edit = true;
                    }
                    if ($result->code_permission == "SFx6"){
                        $gestion_Stages_delete = true;
                    }
                    if ($result->code_permission == "SFx7"){
                        $gestion_Stages_stat = true;
                    }
                }
            }

            if ($gestion_Stages_create || $gestion_Stages_edit || $gestion_Stages_delete || $gestion_Stages_stat) {
                $query_internships = $bdd->prepare($sql);
                $query_internships->execute();
                $results_internships = $query_internships->fetchALL(PDO::FETCH_OBJ);

                $query_promotions = $bdd->prepare('SELECT ID_promotion, name_promotion FROM promotions GROUP BY name_promotion ORDER BY name_promotion ASC;');
                $query_promotions->execute();
                $results_promotions = $query_promotions->fetchALL(PDO::FETCH_OBJ);

                $query_localisations = $bdd->prepare('SELECT ID_localisation, city_localisation FROM localisations GROUP BY city_localisation ORDER BY city_localisation ASC;');
                $query_localisations->execute();
                $results_localisations = $query_localisations->fetchALL(PDO::FETCH_OBJ);

                $query_company = $bdd->prepare('SELECT ID_company, name_company FROM companies GROUP BY name_company ORDER BY name_company ASC;');
                $query_company->execute();
                $results_company = $query_company->fetchALL(PDO::FETCH_OBJ);

                $query_internships_top_remuneration = $bdd->prepare('SELECT * FROM internships ORDER BY remuneration_internship DESC LIMIT 5;');
                $query_internships_top_remuneration->execute();
                $results_internships_top_remuneration = $query_internships_top_remuneration->fetchALL(PDO::FETCH_OBJ);

                $query_internships_longest = $bdd->prepare('SELECT * FROM internships ORDER BY duration_internship DESC LIMIT 5;');
                $query_internships_longest->execute();
                $results_companies_longest = $query_internships_longest->fetchALL(PDO::FETCH_OBJ);

                $query_internships_shortest = $bdd->prepare('SELECT * FROM internships ORDER BY duration_internship ASC LIMIT 5;');
                $query_internships_shortest->execute();
                $results_internships_shortest = $query_internships_shortest->fetchALL(PDO::FETCH_OBJ);

?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Stages - Gestion - CTS</title>
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/gestion_stages.css">
    </head>
    <body>
        <div id="modal_add_edit" class="modal">
            <div class="modal-content">
                <span id="close_add_edit" class="close">&times;</span>
                <div class="title_modal">Ajout d'un stage</div>
                <form class="form_add_edit" method="POST" action="/controller/Manage_internships.php">
                    <input type="hidden" name="action" value="add">
                    <div class="table-container">
                        <div class="info_message"></div>
                        <div class="flex-table">
                            <div class="flex-row name">Nom :</div>
                            <div class="flex-row value"><input class="input" type="text" name="name_internship" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Description du stage :</div>
                            <div class="flex-row value"><input class="input" type="text" name="description_internship" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Durée du stage (j) :</div>
                            <div class="flex-row value"><input class="input" type="text" name="duration_internship" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Rémunération (€/h) :</div>
                            <div class="flex-row value"><input class="input" type="text" name="remuneration_internship" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Date de l'offre :</div>
                            <div class="flex-row value"><input class="input" type="date" name="offer_date_internship" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Nombre de place :</div>
                            <div class="flex-row value"><input class="input" type="text" name="place_number_internship" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Compétences :</div>
                            <div class="flex-row value"><input class="input" type="text" name="competences_internship" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Promotion :</div>
                            <div class="flex-row value">
                                <select class="input" name="promotion" id="select_name_promotion" required>
                                    <option value="" selected>--Choisir une promotion--</option>
                                    <?php foreach ($results_promotions as $result) { ?>
                                        <option value="<?= $result->ID_promotion ?>"><?= $result->name_promotion ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <div class="flex-table">
                        <div class="flex-row name">Localisation :</div>
                        <div class="flex-row value">
                            <select class="input" name="localisation" id="select_localisation" required>
                                <option value="">--Choisir une ville--</option>
                                <?php foreach ($results_localisations as $result) { ?>
                                    <option value="<?= $result->ID_localisation ?>"><?= $result->city_localisation ?></option>
                                <?php } ?>
                            </select>    
                        </div>
                    </div>
                    <div class="flex-table">
                        <div class="flex-row name">Entreprise :</div>
                        <div class="flex-row value">
                            <select class="input" name="company" id="select_company" required>
                                <option value="">--Choisir une entreprise--</option>
                                <?php foreach ($results_company as $result) { ?>
                                    <option value="<?= $result->ID_company ?>"><?= $result->name_company ?></option>
                                <?php } ?>
                            </select>    
                        </div>
                    </div>
                    </div>
                    <button id="button_add_edit" type="submit">Ajouter</button>
                </form>
            </div>
        </div>


        <div id="modal_stat" class="modal">
            <div class="modal-content">
                <span class="close" id="close_stat">&times;</span>
                <div class="title_modal2">Top 5 des stages ayant la meilleure rémuneration</div>
                <div class="modal_stat_table">
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Nom</td>
                            <td>Rémuneration (€/h)</td>
                        </tr>
                        <?php foreach ($results_internships_top_remuneration as $result) { ?>
                        <tr>
                            <td><?= $result->ID_internship ?></td>
                            <td><?= $result->name_internship ?></td>
                            <td><?= $result->remuneration_internship ?></td>
                            
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="title_modal2">Top 5 des stages les plus longs</div>
                <div class="modal_stat_table">
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Nom</td>
                            <td>Durée du stage (j)</td>
                        </tr>
                        <?php foreach ($results_companies_longest as $result) { ?>
                        <tr>
                            <td><?= $result->ID_internship ?></td>
                            <td><?= $result->name_internship ?></td>
                            <td><?= $result->duration_internship ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="title_modal2">Top 5 des stages les plus courts</div>
                <div class="modal_stat_table">
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Nom</td>
                            <td>Note</td>
                        </tr>
                        <?php foreach ($results_internships_shortest as $result) { ?>
                        <tr>
                            <td><?= $result->ID_internship ?></td>
                            <td><?= $result->name_internship ?></td>
                            <td><?= $result->duration_internship ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>



        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Gestion des stages</div>
                    <div class="info_message">Le stage a été supprimé !</div>
                    <div class="logo_plus">
                        <?php
                        if ($gestion_Stages_create) { echo '<i class="fas fa-plus-square logo_add"></i>'; }
                        if ($gestion_Stages_stat) { echo '<i class="fas fa-chart-bar logo_stat"></i>'; }
                        ?>
                    </div>
                    <div class="table">
                        <table class="table">
                            <tr>
                                <td>ID</td>
                                <td>Nom</td>
                                <td>Durée du stage (j)</td>
                                <td>Rémunération (€/h)</td>
                                <td>Date de l'offre</td>
                                <td>Nombre de place</td>
                                <td>Compétences</td>
                                <td>Promotions</td>
                                <?php if ($gestion_Stages_edit || $gestion_Stages_delete) { echo '<td>Actions</td>';} ?>
                            </tr>
                            <?php foreach ($results_internships as $result) { ?>
                            <tr>
                                <td><?= $result->ID_internship ?></td>
                                <td><?= $result->name_internship ?></td>
                                <td><?= $result->duration_internship ?></td>
                                <td><?= $result->remuneration_internship ?></td>
                                <td><?= $result->offer_date_internship ?></td>
                                <td><?= $result->place_number_internship ?></td>
                                <td><?= $result->competences_internship ?></td>
                                <td><?= $result->name_promotion ?></td>
                                <?php if ($gestion_Stages_edit || $gestion_Stages_delete) { ?>
                                <td>
                                    <div class="actions">
                                        <?php    
                                        if ($gestion_Stages_edit) { echo "<i ID_internship='$result->ID_internship' name='$result->name_internship' description='$result->description_internship' duration='$result->duration_internship' remuneration='$result->remuneration_internship' offer_date='$result->offer_date_internship' place_number='$result->place_number_internship' competences='$result->competences_internship' id_promotion='$result->ID_promotion' id_localisation='$result->ID_localisation' id_company='$result->ID_company' class='fas fa-pen logo_edit'></i>";}
                                        if ($gestion_Stages_delete) { echo "<i ID_internship='$result->ID_internship' class='fas fa-trash-alt logo_delete'></i>";}
                                        ?>
                                    </div>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="http://ctsstatic.fr/assets/js/gestion_stages.js"></script>
    </body>
</html>
<?php
        } else {
            echo "<script>location.href='/';</script>";
        }
    } else {
        echo "<script>location.href='/';</script>";
    }
} else {
    echo "<script>location.href='/';</script>";
}
}?>