<!DOCTYPE html>

<?php 
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE username=:user;');
            $query_perm->execute(['user' => $_COOKIE["username"]]);
            $results = $query_perm->fetchALL(PDO::FETCH_OBJ);
            if ($query_perm->rowCount() >= 1) {
                $showEnterprises = false;
                $showStages = false;

                foreach($results as $result){
                    if ($result->code_permission == "SFx2"){ $showEnterprises = true; }
                    if ($result->code_permission == "SFx8"){ $showStages = true; }
                }

                if ($showEnterprises ||  $showStages) {
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Offres - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/offres.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Offres</div>
                    <?php if ($showStages){ ?>
                    <div class="bubble">
                        <div class="title_bubble">Recherche de stage</div>
                            <div class="text_content">
                                Dans la rubrique Stages, vous trouverez assurément un offre qui vous correspondra. Je vous invite à faire vos recherches au plus vite afin d'être le premier servi.
                                <br>
                                Une grande variété de stage est disponible sur notre site internet et il est certain que vous trouverez celui qui vous correspondra.
                                <br><br>
                                Nous vous invitons à vous diriger vers la section de recherche en cliquant sur le bouton suivant.
                                <div>
                                    <a class="btnvoir" href="/offres_stages.php">
                                        <span>Voir les stages</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                    if ($showEnterprises){ ?>
                    <div class="bubble">
                        <div class="title_bubble">Recherche d'entreprise</div>
                            <div class="text_content">
                                Dans la rubrique Entreprises, vous trouverez l'ensemble des entreprises qui proposent des offres de stages aux étudiants CESI.
                                <br>
                                Cela vous permettra de trouver l'ensemble des stages que propose une entreprise spécifique.
                                <br><br>
                                L'accès à cette espace est possible via le bouton suivant.
                                <div>
                                    <a class="btnvoir" href="/offres_entreprises.php">
                                        <span>Voir les entreprises</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="assets/js/offres.js"></script>
    </body>
</html>
<?php

                }
            } else {
                header('HTTP/1.0 403 Forbidden');
                require "controller/403.php";
            }
        } else {
            echo "<script>location.href='/controller/Disconnect.php';</script>";
        }
    } else {
        echo "<script>location.href='/';</script>";
    }
} else {
    echo "<script>location.href='/';</script>";
}
?>