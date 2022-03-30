<!DOCTYPE html>

<?php 
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            $sql = 'SELECT internships.ID_internship, name_internship, duration_internship, remuneration_internship, offer_date_internship, place_number_internship, competences_internship, promotions.name_promotion FROM internships LEFT JOIN internship_for_promo ON internships.ID_internship = internship_for_promo.ID_internship LEFT JOIN promotions ON internship_for_promo.ID_promotion = promotions.ID_promotion;';

            $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission=:perm AND username=:user;');
            $query_perm->execute(['user' => $_COOKIE["username"], 'perm' => "SFx2"]);
            if ($query_perm->rowCount() == 1) {
                $query_interships = $bdd->prepare($sql);
                $query_interships->execute();
                $results_interships = $query_interships->fetchALL(PDO::FETCH_OBJ);

                $query_localisations = $bdd->prepare('SELECT ID_localisation, city_localisation FROM localisations GROUP BY city_localisation ORDER BY city_localisation ASC;');
                $query_localisations->execute();
                $results_localisations = $query_localisations->fetchALL(PDO::FETCH_OBJ);

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
                <span class="close">&times;</span>
                <div class="title_modal"></div>
                <form class="form_add_edit" method="POST" action="/controller/Manage_internships.php">
                    <input type="hidden" name="action" value="add">
                    <div class="table-container">
                        <div class="info_message"></div>
                        <div class="flex-table">
                            <div class="flex-row name">Nom :</div>
                            <div class="flex-row value"><input class="input" type="text" name="name" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Durée :</div>
                            <div class="flex-row value"><input class="input" type="text" name="activity_sector" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Rémunération :</div>
                            <div class="flex-row value"><input class="input" type="text" name="nb_intern_cesi" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Date :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Nb de palces :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Compétences :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Promotion(s) :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                    </div>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        </div>

        <div class="container">
            <?php require "controller/Nav_bar.php"?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Gestion des stages</div>
                    <div class="info_message">Le stage a été supprimé !</div>
                    <div class="logo_plus"><i class="fas fa-plus-square logo_add"></i> <i class="fas fa-chart-bar logo_stat"></i></div>
                    <div class="table">
                        <table class="table">
                            <tr>
                                <td>ID</td>
                                <td>Nom</td>
                                <td>Durée</td>
                                <td>Rémuniration</td>
                                <td>Date</td>
                                <td>Nb places</td>
                                <td>Compétences</td>
                                <td>Promotion(s)</td>
                                <td>Actions</td>
                            </tr>
                            <?php foreach ($results_interships as $result) { ?>
                            <tr>
                                <td><?= $result->ID_intership ?></td>
                                <td><?= $result->name_intership ?></td>
                                <td><?= $result->duration_intership ?></td>
                                <td><?= $result->remuneration_intership ?></td>
                                <td><?= $result->offer_date_intership ?></td>
                                <td><?= $result->place_number_intership ?></td>
                                <td><?= $result->competences_intership ?></td>
                                <td><?= $result->name_promotion_intership ?></td>
                                <td>
                                    <div class="actions">
                                        <i ID_internship="<?= $result->ID_internship ?>" name="<?= $result->name_intership ?>" duration="<?= $result->duration_intership ?>" remuneration="<?= $result->remuneration_intership ?>" offer_date="<?= $result->offer_date_intership?>" place_number="<?= $result->place_number_intership ?>" competences="<?= $result->competences_intership ?>" promotions="<?= $result->promotions_intership ?>" class="fas fa-pen logo_edit"></i>
                                        <i ID_internship="<?= $result->ID_internship ?>" class="fas fa-trash-alt logo_delete"></i>
                                    </div>
                                </td>
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
}
?>