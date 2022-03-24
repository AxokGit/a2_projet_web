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
        <title>Etudiants - Gestion - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/gestion_students.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php"?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Gestion des étudiants</div>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="assets/js/gestion_students.js"></script>
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