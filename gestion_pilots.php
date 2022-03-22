<!DOCTYPE html>

<?php 
session_start();

if (isset($_SESSION["username"])){ ?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Pilotes - Gestion - CTS</title>
        <link rel="stylesheet" type="text/css" href="assets/css/gestion_pilots.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php"?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Gestions des pilotes</div>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="assets/js/gestion_pilots.js"></script>
    </body>
</html>
<?php } else {?>
    <script>location.href='/';</script>
<?php } ?>