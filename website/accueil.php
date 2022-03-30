<!DOCTYPE html>

<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Accueil - CTS</title>
        <meta name="description" content="Page d'accueil de CTS">
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/accueil.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php"?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="bubble">
                        <img src="http://ctsstatic.fr/assets/images/fond_cesi_large.png" alt="fond cesi">
                        <div class="centered">CTS vous conduit vers la réussite</div>
                    </div>
                    <div class="bubble">
                        <div class="title_bubble">Notre histoire</div>
                        <div class="text_content">
                        Cesi Ton Stage a débuté dans le salon du co-fondateur Olivier SANDEL en 2022 et a été officiellement lancé le 1 avril 2022.
                        <br>
                        <br>
                        Sous la direction de Justine ADLER, CTS mène une activité diversifiée et assure le futur de milliers d'étudiants.
                        <br>
                        En février 2022, CESI a finalisé son acquisition de CTS, permettant ainsi la fusion entre la meilleure école professionnelle au monde et le meilleur site de stage.
                        </div>
                    </div>
                    <div class="bubble">
                        <div class="title_bubble">Notre but</div>
                        <div class="text_content">
                            La mission de Cesi Ton Stage est simple : mettre en relation les étudiants CESI de la France entière avec des entreprises qui leurs sont adaptées
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="http://ctsstatic.fr/assets/js/accueil.js"></script>
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