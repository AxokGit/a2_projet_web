<!DOCTYPE html>

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
                $results_internships = $query_interships->fetchALL(PDO::FETCH_OBJ);

                $query_promotions = $bdd->prepare('SELECT ID_promotion, name_promotion FROM promotions GROUP BY name_promotion ORDER BY name_promotion ASC;');
                $query_promotions->execute();
                $results_promotions = $query_promotions->fetchALL(PDO::FETCH_OBJ);

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
                    <div class="title_modal">Ajout d'un stage</div>
                    <form class="form_add_edit" method="POST" action="/controller/Manage_internship.php">
                        <input type="hidden" name="action" value="add">
                        <div class="table-container">
                            <div class="info_message"></div>
                            <div class="flex-table">
                                <div class="flex-row name">Nom :</div>
                                <div class="flex-row value"><input class="input" type="text" name="name_internship" required></div>
                            </div>
                            <div class="flex-table">
                                <div class="flex-row name">Durée du stage :</div>
                                <div class="flex-row value"><input class="input" type="text" name="duration_internship" required></div>
                            </div>
                            <div class="flex-table">
                                <div class="flex-row name">Rémunération :</div>
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
                                <div class="flex-row name">Promotion :</div>
                                <div class="flex-row value">
                                    <select class="input" name="name_promotion" id="select_name_promotion" required>
                                        <option value="" selected>--Choisir une promotion--</option>
                                        <?php foreach ($results_promotions as $result) { ?>
                                            <option value="<?= $result->ID_promotion ?>"><?= $result->name_promotion ?></option>
                                        <?php } ?>
                                    </select>    
                                </div>
                            </div>
                        </div>
                        <button type="submit">Ajouter</button>
                    </form>
                </div>
            </div>

        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
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
                                <td>Durée du stage</td>
                                <td>Rémunération</td>
                                <td>Date de l'offre</td>
                                <td>Nombre de place</td>
                                <td>Compétences</td>
                                <td>Promotions</td>
                                <td>Actions</td>
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
                                <td>
                                    <div class="actions">
                                        <i ID_internship="<?= $result->ID_internship ?>" class="fas fa-pen logo_edit"></i>
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
}?>