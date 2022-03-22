<!DOCTYPE html>

<?php 
session_start();

if (isset($_SESSION["username"])){ ?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>TEST - CTS</title>
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php"?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <form action="/test2.php" method="post" enctype="multipart/form-data">
                        <input id="file" type="file" name="file">
                        <button type="submit">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
    </body>
</html>
<?php } else {?>
    <script>location.href='/';</script>
<?php } ?>