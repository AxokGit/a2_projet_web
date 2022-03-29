<!DOCTYPE html>

<html lang="fr">
    <head>
        <title>{$title}</title>
        <meta name="description" content="{$description}">
        <link rel="stylesheet" href="./assets/vendors/fontawesome/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="assets/images/logo_petit.png">
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="assets/css/a_propos.css">
    </head>
    <body>
        <div class="container">
            <div class="main">
                <div class="content">
                    <div class="bubble">
                        <img src="assets/images/fond_cesi_large.png" alt="fond cesi">
                        <div class="centered">{$page_title}</div>
                    </div>
                    {foreach from=$bubbles key=$key item=$bubble}
                    <div class="bubble">
                        <div class="title_bubble">{$key}</div>
                        <div class="text_content">{$bubble}</div>
                    </div>
                    {/foreach}
                    <div>
                        <a class="btnvoir" href="accueil.php">
                            <span>Retour à l'accueil</span>
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </body>
</html>