<!DOCTYPE html>

<?php
session_start();

if (isset($_SESSION["username"])){
    include "controller/ConnexionBDD.php";
    if (!$error) {
        $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission=:perm AND username=:user;');
        $query_perm->execute(['user' => $_SESSION["username"], 'perm' => "SFx8"]);
        if ($query_perm->rowCount() == 1) {
            $query = $bdd->prepare('SELECT * FROM internships INNER JOIN localisations ON localisations.ID_localisation = internships.ID_localisation INNER JOIN companies ON companies.ID_company = internships.ID_company;');
            $query->execute();
            $results = $query->fetchALL(PDO::FETCH_OBJ);
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Stages - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/favoris.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Favoris</div>
                    <?php foreach ($results as $result): ?>
                        <div class="bubble">
                            <div class="title_bubble"><u><?php print $result->name_internship; ?></u></div>
                            <div class="text_content">

                                <!--<p> ID entreprise : <?php print $result->ID_company; ?></p>
                                <p> ID stage : <?php print $result->ID_internship; ?></p>-->
                                <p> <b>Nom de l'entreprise :</b> <?= $result->name_company; ?></p>
                                <p> <b>Description du stage :</b> <?= $result->description_internship; ?></p>
                                <p> <b>Localisation du stage :</b> <?= $result->city_localisation; ?> <?= $result->postal_code_localisation; ?> </p>
                                <p> <b>Durée du stage :</b> <?= $result->duration_internship; ?> jours</p>
                                <p> <b>Rémunération :</b> <?= $result->remuneration_internship; ?>€ / heure</p>
                                <p> <b>Date de l'offre :</b> <?= $result->offer_date_internship; ?></p>
                                <p> <b>Compétences requises :</b> <?= $result->competences_internship; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="assets/js/favoris.js"></script>
    </body>
</html>
<?php
        } else {
            header('HTTP/1.0 403 Forbidden');
            require "controller/";
        }
    }
} else {
    echo "<script>location.href='/';</script>";
}
?>