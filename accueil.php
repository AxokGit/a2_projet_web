<!DOCTYPE html>

<?php 
session_start();

if (isset($_SESSION["username"])){ ?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Accueil - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/accueil.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php"?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="bubble">
                        <img src="assets/images/fond_cesi_large.png" alt="fond cesi">
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
        <script src="assets/js/accueil.js"></script>
    </body>
</html>
<?php } else {?>
    <script>location.href='/';</script>
<?php } ?>