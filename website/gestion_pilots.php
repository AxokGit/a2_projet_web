<!DOCTYPE html>

<?php 
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            $sql = ('SELECT users.ID_user, username, firstname_user, lastname_user, email_user, city_localisation, promotions.name_promotion FROM users INNER JOIN roles ON roles.ID_role=users.ID_role INNER JOIN localisations ON localisations.ID_localisation=users.ID_localisation INNER JOIN user_belong_promo ON user_belong_promo.ID_user=users.ID_user INNER JOIN promotions ON promotions.ID_promotion=user_belong_promo.ID_promotion WHERE roles.name_role = "Pilote";');

            $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission=:perm AND username=:user;');
            $query_perm->execute(['user' => $_COOKIE["username"], 'perm' => "SFx2"]);
            if ($query_perm->rowCount() == 1) {
                $query_pilots = $bdd->prepare($sql);
                $query_pilots->execute();
                $results_pilots = $query_pilots->fetchALL(PDO::FETCH_OBJ);

                $query_localisations = $bdd->prepare('SELECT ID_localisation, city_localisation FROM localisations GROUP BY city_localisation ORDER BY city_localisation ASC;');
                $query_localisations->execute();
                $results_localisations = $query_localisations->fetchALL(PDO::FETCH_OBJ);
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Pilotes - Gestion - CTS</title>
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/gestion_pilots.css">
    </head>
    <body>
        <div id="modal_add_edit" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title_modal"></div>
                <form class="form_add_edit" method="POST" action="/controller/Manage_pilots.php">
                    <input type="hidden" name="action" value="add">
                    <div class="table-container">
                        <div class="info_message"></div>
                        <div class="flex-table">
                            <div class="flex-row name">Nom :</div>
                            <div class="flex-row value"><input class="input" type="text" name="name" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Nom d'utilisateur :</div>
                            <div class="flex-row value"><input class="input" type="text" name="activity_sector" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Nom :</div>
                            <div class="flex-row value"><input class="input" type="text" name="nb_intern_cesi" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Prenom :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Email :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Centre :</div>
                            <div class="flex-row value"><input class="input" type="text" name="email" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Promotion :</div>
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
                    <div class="content_title">Gestions des pilotes</div>
                    <div class="info_message">Le pilote a été supprimé !</div>
                    <div class="logo_plus"><i class="fas fa-plus-square logo_add"></i> <i class="fas fa-chart-bar logo_stat"></i></div>
                    <div class="table">
                        <table class="table">
                            <tr>
                                <td>ID</td>
                                <td>Nom d'utilisateur</td>
                                <td>Nom</td>
                                <td>Prénom</td>
                                <td>Email</td>
                                <td>Centre</td>
                                <td>Promotion</td>
                                <td>Actions</td>
                            </tr>
                            <?php foreach ($results_pilots as $result) { ?>
                            <tr>
                                <td><?= $result-> ID_user?></td>
                                <td><?= $result->username ?></td>
                                <td><?= $result->firstname_user ?></td>
                                <td><?= $result->lastname_user ?></td>
                                <td><?= $result->email_user ?></td>
                                <td><?= $result->city_localisation ?></td>
                                <td><?= $result->name_promotion ?></td>
                                <td>
                                    <div class="actions">
                                        <i ID_user="<?= $result->ID_user ?>" name="<?= $result->name_intership ?>" duration="<?= $result->duration_intership ?>" remuneration="<?= $result->remuneration_intership ?>" offer_date="<?= $result->offer_date_intership?>" place_number="<?= $result->place_number_intership ?>" competences="<?= $result->competences_intership ?>" promotions="<?= $result->promotions_intership ?>" class="fas fa-pen logo_edit"></i>
                                        <i ID_user="<?= $result->ID_user ?>" class="fas fa-trash-alt logo_delete"></i>
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
        <script src="http://ctsstatic.fr/assets/js/gestion_pilots.js"></script>
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