<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php require "Head.php" ?>
        <title>Accès refusé - CTS</title>
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/403.css">
    </head>
    <body>
        <div class="container">
            <?php require "Nav_bar.php "?>
            <div class="main">
                <?php require "Top_bar.php" ?>
                <div class="content">
                    <div class="bubble">
                        <div class="title_bubble">Accès refusé</div>
                        <div class="text_content">
                            Vous n'avez pas les permissions nécessaires pour accéder à cette page ou de réaliser cette action.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require "Script.php" ?>
        <script src="http://ctsstatic.fr/assets/js/403.js"></script>
    </body>
</html>