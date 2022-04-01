<!DOCTYPE html>

<?php 
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            $sql = 'SELECT companies.ID_company, name_company, activity_sector_company, nb_intern_cesi_company, email_company, visibility_company, companies_located.ID_localisation, note FROM companies LEFT JOIN companies_located ON companies.ID_company=companies_located.ID_company LEFT JOIN localisations ON companies_located.ID_localisation=localisations.ID_localisation INNER JOIN evaluate ON companies.ID_company = evaluate.ID_company;';

            $query_perm_nav = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE username = :user;');
            $query_perm_nav->execute(['user' => $_COOKIE["username"]]);
            $results_nav = $query_perm_nav->fetchALL(PDO::FETCH_OBJ);
            if ($query_perm_nav->rowCount() >= 1) {
                $gestion_Enterprises_create = false;
                $gestion_Enterprises_edit = false;
                $gestion_Enterprises_delete = false;
                $gestion_Enterprises_stat = false;
                
                foreach($results_nav as $result){
                    if ($result->code_permission == "SFx3"){
                        $gestion_Enterprises_create = true;
                    }
                    if ($result->code_permission == "SFx4"){
                        $gestion_Enterprises_edit = true;
                    }
                    if ($result->code_permission == "SFx6"){
                        $gestion_Enterprises_delete = true;
                    }
                    if ($result->code_permission == "SFx7"){
                        $gestion_Enterprises_stat = true;
                    }
                }
            }
            if ($gestion_Enterprises_create || $gestion_Enterprises_edit || $gestion_Enterprises_delete || $gestion_Enterprises_stat) {
                $query_companies = $bdd->prepare($sql);
                $query_companies->execute();
                $results_companies = $query_companies->fetchALL(PDO::FETCH_OBJ);

                $query_localisations = $bdd->prepare('SELECT ID_localisation, city_localisation FROM localisations GROUP BY city_localisation ORDER BY city_localisation ASC;');
                $query_localisations->execute();
                $results_localisations = $query_localisations->fetchALL(PDO::FETCH_OBJ);

                $query_companies_top_internship = $bdd->prepare('SELECT companies.ID_company, name_company, COUNT(internships.ID_company) AS "number_of_internships" FROM companies LEFT JOIN internships ON companies.ID_company=internships.ID_company GROUP BY internships.ID_company ORDER BY number_of_internships DESC;');
                $query_companies_top_internship->execute();
                $results_companies_top_internship = $query_companies_top_internship->fetchALL(PDO::FETCH_OBJ);

                $query_companies_top_cesi_intern = $bdd->prepare('SELECT companies.ID_company, name_company, nb_intern_cesi_company FROM companies ORDER BY nb_intern_cesi_company DESC;');
                $query_companies_top_cesi_intern->execute();
                $results_companies_top_cesi_intern = $query_companies_top_cesi_intern->fetchALL(PDO::FETCH_OBJ);

                $query_companies_top_note = $bdd->prepare('SELECT companies.ID_company, name_company, note FROM companies INNER JOIN evaluate ON companies.ID_company=evaluate.ID_company INNER JOIN users ON evaluate.ID_user=users.ID_user NATURAL JOIN roles WHERE name_role="Pilote" ORDER BY note ASC;');
                $query_companies_top_note->execute();
                $results_companies_top_note = $query_companies_top_note->fetchALL(PDO::FETCH_OBJ);
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Entreprises - Gestion - CTS</title>
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/gestion_entreprises.css">
    </head>
    <body>
        <div id="modal_add_edit" class="modal">
            <div class="modal-content">
                <span class="close" id="close_add_edit">&times;</span>
                <div class="title_modal"></div>
                <form class="form_add_edit" method="POST" action="/controller/Manage_companies.php">
                    <input type="hidden" name="action" value="add">
                    <div class="table-container">
                        <div class="info_message"></div>
                        <div class="flex-table">
                            <div class="flex-row name">Nom :</div>
                            <div class="flex-row value"><input class="input" type="text" name="name" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Secteur d'activité :</div>
                            <div class="flex-row value"><input class="input" type="text" name="activity_sector" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Nb stagiaires CESI :</div>
                            <div class="flex-row value"><input class="input" type="text" name="nb_intern_cesi" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Email :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Confiance :</div>
                            <div class="flex-row value">
                                <select class="input" name="note" id="select_note" required>
                                    <option value="A">--Choisir une note--</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
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
                            <div class="flex-row name">Visibilité :</div>
                            <div class="flex-row value">
                                <select class="input" name="visibility" id="select_visibility" required>
                                    <option value="A">--Choisir une visibilité--</option>
                                    <option value="O">Oui</option>
                                    <option value="N">Non</option>
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
                <div class="title_modal2">Top des entreprises ayant le plus de stages</div>
                <div class="modal_stat_table">
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Nom</td>
                            <td>Nb stages</td>
                        </tr>
                        <?php foreach ($results_companies_top_internship as $result) { ?>
                        <tr>
                            <td><?= $result->ID_company ?></td>
                            <td><?= $result->name_company ?></td>
                            <td><?= $result->number_of_internships ?></td>
                            
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="title_modal2">Top des entreprises ayant le plus d'étudiants CESI</div>
                <div class="modal_stat_table">
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Nom</td>
                            <td>Nb étudiants CESI</td>
                        </tr>
                        <?php foreach ($results_companies_top_cesi_intern as $result) { ?>
                        <tr>
                            <td><?= $result->ID_company ?></td>
                            <td><?= $result->name_company ?></td>
                            <td><?= $result->nb_intern_cesi_company ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="title_modal2">Top des entreprises ayant les meilleures notes</div>
                <div class="modal_stat_table">
                    <table class="table">
                        <tr>
                            <td>ID</td>
                            <td>Nom</td>
                            <td>Note</td>
                        </tr>
                        <?php foreach ($results_companies_top_note as $result) { ?>
                        <tr>
                            <td><?= $result->ID_company ?></td>
                            <td><?= $result->name_company ?></td>
                            <td><?= $result->note ?></td>
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
                    <div class="content_title">Gestion des entreprises</div>
                    <div class="info_message">L'entreprise a été supprimé !</div>
                    <div class="logo_plus">
                        <?php
                        if ($gestion_Enterprises_create) { echo '<i class="fas fa-plus-square logo_add"></i>'; }
                        if ($gestion_Enterprises_stat) { echo '<i class="fas fa-chart-bar logo_stat"></i>'; }
                        ?>
                    </div>
                    <div class="table">
                        <table class="table">
                            <tr>
                                <td>ID</td>
                                <td>Nom</td>
                                <td>Secteur d'activité</td>
                                <td>Nb stagiaires CESI</td>
                                <td>Email</td>
                                <td>Visibilité</td>
                                <?php if ($gestion_Enterprises_edit || $gestion_Enterprises_delete) { echo '<td>Actions</td>';} ?>
                            </tr>
                            <?php foreach ($results_companies as $result) { ?>
                            <tr>
                                <td><?= $result->ID_company ?></td>
                                <td><?= $result->name_company ?></td>
                                <td><?= $result->activity_sector_company ?></td>
                                <td><?= $result->nb_intern_cesi_company ?></td>
                                <td><?= $result->email_company ?></td>
                                <td><?= $result->visibility_company ?></td>
                                <?php if ($gestion_Enterprises_edit || $gestion_Enterprises_delete) { ?>
                                <td>
                                    <div class="actions">
                                        <?php
                                        if ($gestion_Enterprises_edit) { echo "<i ID_company='$result->ID_company' name='$result->name_company' activity_sector='$result->activity_sector_company' nb_intern='$result->nb_intern_cesi_company' email='$result->email_company' localisation='$result->ID_localisation' note='$result->note' visibility='$result->visibility_company' class='fas fa-pen logo_edit'></i>";}
                                        if ($gestion_Enterprises_delete) { echo "<i ID_company='$result->ID_company' class='fas fa-trash-alt logo_delete'></i>";}
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
        <script src="http://ctsstatic.fr/assets/js/gestion_entreprises.js"></script>
    </body>
</html>
<?php
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
