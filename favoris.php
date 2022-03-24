<!DOCTYPE html>

<?php

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
	require "controller/ConnexionBDD.php";
	if (!$error) {
		$query_check_cookie = $bdd->prepare('SELECT * FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE code_permission="SFx1" AND username=:user AND password_user=:password_user;');
		$query_check_cookie->execute(['user' => $_COOKIE['username'], 'password_user' => $_COOKIE['pass']]);
		if ($query_check_cookie->rowCount() == 1){
            $sql = 'SELECT ID_internship ,name_internship, description_internship, duration_internship, remuneration_internship, offer_date_internship, place_number_internship, competences_internship, city_localisation, postal_code_localisation, GROUP_CONCAT(name_promotion SEPARATOR ", ") AS "name_promotion", name_company, visibility_company, note FROM internships NATURAL JOIN localisations NATURAL JOIN companies NATURAL JOIN internship_for_promo NATURAL JOIN promotions NATURAL JOIN evaluate INNER JOIN users ON evaluate.ID_user=users.ID_user NATURAL JOIN roles WHERE name_role="Pilote" AND visibility_company="O" GROUP BY ID_internship ORDER BY offer_date_internship;';
                $query_perm = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE username=:user;');
                $query_perm->execute(['user' => $_COOKIE["username"]]);
                $results_perm = $query_perm->fetchALL(PDO::FETCH_OBJ);
                if ($query_perm->rowCount() >= 1) {
                    $showFavoris = false;

                    foreach ($results_perm as $result) {
                        if ($result->code_permission == "SFx27" || $result->code_permission == "SFx28"){
                            $showFavoris = true;
                        }
                    }
                    
                    if ($showFavoris){
                        $query_internships = $bdd->prepare($sql);
                        $query_internships->execute();
                        $results_internships = $query_internships->fetchALL(PDO::FETCH_OBJ);

                        $query_wishlist = $bdd->prepare('SELECT ID_internship FROM internships NATURAL JOIN wishlist INNER JOIN users ON wishlist.ID_user=users.ID_user WHERE username=:user;');
                        $query_wishlist->execute(['user' => $_COOKIE["username"]]);
                        $results_wishlist = $query_wishlist->fetchALL(PDO::FETCH_OBJ);
                        $wishlist = [];
                        foreach ($results_wishlist as $result) {
                            array_push($wishlist, $result->ID_internship);
                        }
            



?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Favoris - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/favoris.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Favoris</div>
                    <?php
                    if (count($wishlist) >= 1) {
                        foreach ($results_internships as $result) {
                            if ($result->visibility_company == "O" && in_array($result->ID_internship, $wishlist)) {
                    ?>
                        <div class="bubble">
                            <div class="title_bubble"><?= $result->name_internship; ?>
                                    <div id="<?= $result->ID_internship ?>_1" class="heart1 heart-hidden"><i class="far fa-heart"></i></div>
                                    <div id="<?= $result->ID_internship ?>_2" class="heart2" style="color: red;"><i class="fas fa-heart"></i></div>
                            </div>
                            <div class="text_content">
                                <div class="divLeft">
                                    <div class="name_company">
                                        <?= $result->name_company; ?>
                                    </div>
                                    <?= $result->city_localisation; ?> <?= $result->postal_code_localisation; ?>
                                    <div class="description">
                                        <?= $result->description_internship; ?>
                                    </div>
                                </div>
                                <div class="divRight">  
                                    <table>
                                        <div class="table_title">Points importants:</div>
                                        
                                        <tr>
                                            <td><i class="far fa-clock"></i></td>
                                            <td><?= $result->duration_internship; ?> jours</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-euro-sign"></i></td>
                                            <td><?= $result->remuneration_internship; ?> €/h</td>
                                        </tr>
                                        <tr>
                                            <td><i class="far fa-calendar-alt"></i></td>
                                            <td><?= $result->offer_date_internship; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-graduation-cap"></i></td>
                                            <td><?= $result->name_promotion; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-user-alt"></i></td>
                                            <td>
                                                <?php
                                                echo $result->place_number_internship;
                                                if($result->place_number_internship > 1) {echo " places disponibles";}
                                                else {echo " place disponible";}?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-chart-bar"></i></td>
                                            <td><?= $result->competences_internship; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-handshake"></i></td>
                                            <td>Confiance du pilote : <?= $result->note; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php 
                                }
                            } 
                        } else {
                    ?>
                    <div class="bubble">
                        <div class="title_bubble">Tout est vide...</div>
                        <div class="text_content">
                            Il n'y a aucun favori dans les parages &nbsp;<i class="fas fa-wind"></i>
                        </div>
                    </div>
                    <?php } ?>
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
                    require "controller/403.php";
                }
            } else {
                header('HTTP/1.0 403 Forbidden');
                require "controller/403.php";
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