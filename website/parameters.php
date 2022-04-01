<!DOCTYPE html>

<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){

            $query = $bdd->prepare('SELECT username, lastname_user, firstname_user, email_user, city_localisation, date_creation, connection_count, GROUP_CONCAT(name_promotion SEPARATOR ", ") AS "name_promotion", name_role FROM a2_projet_web.users NATURAL JOIN user_belong_promo NATURAL JOIN promotions NATURAL JOIN localisations NATURAL JOIN roles WHERE username=:user GROUP BY username;');
            $query->execute(['user' => $_COOKIE["username"]]);
            $results = $query->fetchALL(PDO::FETCH_OBJ);
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Paramètres - CTS</title>
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/parameters.css">
    </head>
    <body>

        <div id="modal_edit_password" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title_modal">Modification du mot de passe</div>
                <form class="form_edit_password" method="POST">
                    <div class="table-container">
                        <div class="info_message"></div>
                        <div class="flex-table">
                            <div class="flex-row name">Mot de passe actuel</div>
                            <div class="flex-row value"><input class="input_edit_password" type="password" name="actual_pass" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Nouveau mot de passe</div>
                            <div class="flex-row value"><input class="input_edit_password" type="password" name="new_pass" required></div>
                        </div>
                        <div class="flex-table">
                            <div class="flex-row name">Confirmation du mot de passe</div>
                            <div class="flex-row value"><input class="input_edit_password" type="password" name="confirm_pass" required></div>
                        </div>
                    </div>
                    <button type="submit">Modifier</button>
                </form>
            </div>
        </div>




        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Paramètres</div>
                    <div class="bubble">
                        <div class="title_bubble">Information sur le compte</div>
                        <div class="text_content">
                            <div class="table-container">
                                <div class="flex-table">
                                    <div class="flex-row name">Nom d'utilisateur :</div>
                                    <div class="flex-row value"><?= $results[0]->username ?></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Nom :</div>
                                    <div class="flex-row value"><?= $results[0]->lastname_user ?></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Prénom :</div>
                                    <div class="flex-row value"><?= $results[0]->firstname_user ?></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Email :</div>
                                    <div class="flex-row value"><?= $results[0]->email_user ?></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Mot de passe :</div>
                                    <div class="flex-row value"><button id="button_edit_password">Modifier mon mot de passe</button></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Centre :</div>
                                    <div class="flex-row value"><?= $results[0]->city_localisation ?></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Promotion :</div>
                                    <div class="flex-row value"><?= $results[0]->name_promotion ?></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Status :</div>
                                    <div class="flex-row value"><?= $results[0]->name_role ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bubble">
                        <div class="title_bubble">Statistiques</div>
                        <div class="text_content">
                            <div class="table-container">
                                <div class="flex-table">
                                    <div class="flex-row name">Date de création :</div>
                                    <div class="flex-row value"><?= $results[0]->date_creation ?></div>
                                </div>
                                <div class="flex-table">
                                    <div class="flex-row name">Nombre de connexion :</div>
                                    <div class="flex-row value"><?= $results[0]->connection_count ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a class="btnvoir" href="controller/Disconnect.php">
                            <span>Se déconnecter</span>
                        </a>
                    </div>
                    <div>
                        <a class="btnvoir btn_a_propos" href="a_propos.php">
                            <span>A propos</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="http://ctsstatic.fr/assets/js/parameters.js"></script>
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
?>