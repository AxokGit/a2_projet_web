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
                $showGestion_Enterprises = false;
                $showGestion_Studients = false;
                $showGestion_Pilots = false;
                $showGestion_Delegates = false;
                $showGestion_Stages = false;

                foreach($results as $result){
                    if ($result->code_permission == "SFx3" || $result->code_permission == "SFx4" || $result->code_permission == "SFx5" || $result->code_permission == "SFx6" || $result->code_permission == "SFx7"){
                        $showGestion_Enterprises = true;
                    }
                    if ($result->code_permission == "SFx22" || $result->code_permission == "SFx23" || $result->code_permission == "SFx24" || $result->code_permission == "SFx25" || $result->code_permission == "SFx26"){
                        $showGestion_Studients = true;
                    }
                    if ($result->code_permission == "SFx13" || $result->code_permission == "SFx14" || $result->code_permission == "SFx15" || $result->code_permission == "SFx16"){
                        $showGestion_Pilots = true;
                    }
                    if ($result->code_permission == "SFx17" || $result->code_permission == "SFx18" || $result->code_permission == "SFx19" || $result->code_permission == "SFx20" || $result->code_permission == "SFx21"){
                        $showGestion_Delegates = true;
                    }
                    if ($result->code_permission == "SFx9" || $result->code_permission == "SFx10" || $result->code_permission == "SFx11" || $result->code_permission == "SFx12"){
                        $showGestion_Stages = true;
                    }
                }
?>
<html lang="fr">
    <head>
        <?php require "controller/Head.php" ?>
        <title>Gestions - CTS</title>
        <link rel="stylesheet" type="text/css" href="http://ctsstatic.fr/assets/css/gestions.css">
    </head>
    <body>
        <div class="container">
            <?php require "controller/Nav_bar.php" ?>
            <div class="main">
                <?php require "controller/Top_bar.php" ?>
                <div class="content">
                    <div class="content_title">Gestions</div>
                    <div class="bubble_content">
                        <?php if ($showGestion_Enterprises){ ?>
                        <div class="bubble">
                            <div class="title_bubble">Entreprises</div>
                            <div class="text_content">
                                <a class="btnvoir" href="/gestion_entreprises.php">
                                    <span>G??rer</span>
                                </a>
                            </div>
                        </div>
                        <?php }
                        if ($showGestion_Studients){ ?>
                        <div class="bubble">
                            <div class="title_bubble">Etudiants</div>
                            <div class="text_content">
                                <a class="btnvoir" href="/gestion_students.php">
                                    <span>G??rer</span>
                                </a>
                            </div>
                        </div>
                        <?php } 
                        if ($showGestion_Pilots){ ?>
                        <div class="bubble">
                            <div class="title_bubble">Pilotes</div>
                            <div class="text_content">
                                <a class="btnvoir" href="/gestion_pilots.php">
                                    <span>G??rer</span>
                                </a>
                            </div>
                        </div>
                        <?php }
                        if ($showGestion_Delegates){ ?>
                        <div class="bubble">
                            <div class="title_bubble">D??l??gu??s</div>
                            <div class="text_content">
                                <a class="btnvoir" href="/gestion_delegates.php">
                                    <span>G??rer</span>
                                </a>
                            </div>
                        </div>
                        <?php } 
                        if ($showGestion_Stages){ ?>
                        <div class="bubble">
                            <div class="title_bubble">Stages</div>
                            <div class="text_content">
                                <a class="btnvoir" href="/gestion_stages.php">
                                    <span>G??rer</span>
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    
                </div>
            </div>
        </div>
        <?php require "controller/Script.php" ?>
        <script src="http://ctsstatic.fr/assets/js/gestions.js"></script>
    </body>
</html>
<?php
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