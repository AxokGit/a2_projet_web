<?php 

if (isset($_SESSION["username"])){
    include "ConnexionBDD.php";
    if (!$error) {
        $query_perm_nav = $bdd->prepare('SELECT username, code_permission FROM users NATURAL JOIN roles NATURAL JOIN roles_has_permissions NATURAL JOIN permissions WHERE username = :user;');
        $query_perm_nav->execute(['user' => $_SESSION["username"]]);
        $results_nav = $query_perm_nav->fetchALL(PDO::FETCH_OBJ);
        if ($query_perm_nav->rowCount() >= 1) {
            $showOffres = false;
            $showStages = false;
            $showEntreprises = false;
            $showFavoris = false;
            $showCandidatures = false; //A revoir au niveau des permissions
            $showGestions = false;
            $showGestion_Enterprises = false;
            $showGestion_Studients = false;
            $showGestion_Pilots = false;
            $showGestion_Delegates = false;
            $showGestion_Stages = false;
            
            foreach($results_nav as $result){
                if ($result->code_permission == "SFx2" || $result->code_permission == "SFx8"){
                    $showOffres = true;
                }
                if ($result->code_permission == "SFx8"){
                    $showStages = true;
                }
                if ($result->code_permission == "SFx2"){
                    $showEntreprises = true;
                }
                if ($result->code_permission == "SFx27" || $result->code_permission == "SFx28"){
                    $showFavoris = true;
                }
                if ($result->code_permission == "SFx29" || $result->code_permission == "SFx30" || $result->code_permission == "SFx31" || $result->code_permission == "SFx32" || $result->code_permission == "SFx33" || $result->code_permission == "SFx34" || $result->code_permission == "SFx35"){
                    $showCandidatures = true;
                }
                if ($result->code_permission == "SFx3" || $result->code_permission == "SFx4" || $result->code_permission == "SFx5" || $result->code_permission == "SFx6" || $result->code_permission == "SFx7"){
                    $showGestion_Enterprises = true;
                    $showGestions = true;
                }
                if ($result->code_permission == "SFx22" || $result->code_permission == "SFx23" || $result->code_permission == "SFx24" || $result->code_permission == "SFx25" || $result->code_permission == "SFx26"){
                    $showGestion_Studients = true;
                    $showGestions = true;
                }
                if ($result->code_permission == "SFx13" || $result->code_permission == "SFx14" || $result->code_permission == "SFx15" || $result->code_permission == "SFx16"){
                    $showGestion_Pilots = true;
                    $showGestions = true;
                }
                if ($result->code_permission == "SFx17" || $result->code_permission == "SFx18" || $result->code_permission == "SFx19" || $result->code_permission == "SFx20" || $result->code_permission == "SFx21"){
                    $showGestion_Delegates = true;
                    $showGestions = true;
                }
                if ($result->code_permission == "SFx9" || $result->code_permission == "SFx10" || $result->code_permission == "SFx11" || $result->code_permission == "SFx12"){
                    $showGestion_Stages = true;
                    $showGestions = true;
                }
            }
        }
    }
}
?>

<div class="navigation">
    <ul>
        <li class="nav_title">
            <a href="#">
                <img src="assets/images/logo_petit.png" alt="Cesi Ton Stage">
                <span class="title">Cesi Ton Stage</span>
            </a>
        </li>
        
        <li id="li_accueil">
            <a href="/accueil.php">
                <span class="icon"><i class="fas fa-home"></i></span>
                <span class="title">Accueil</span>
            </a>
        </li>
        <?php if ($showOffres) { ?>
        <li id="li_offres">
            <a href="/offres.php">
                <span class="icon"><i class="fab fa-buffer"></i></span>
                <span class="title">Offres</span>
            </a>
        </li>
        <?php } ?>
                    <?php if ($showStages) { ?>
                    <li class="sous_li"  id="li_stages">
                        <a href="/offres_stages.php">
                            <span class="icon"><i class="fas fa-angle-right"></i></span>
                            <span class="title">Stages</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($showEntreprises) { ?>
                    <li class="sous_li"  id="li_entreprises">
                        <a href="/offres_entreprises.php">
                            <span class="icon"><i class="fas fa-angle-right"></i></span>
                            <span class="title">Entreprises</span>
                        </a>
                    </li>
                    <?php } ?>
        <?php if ($showFavoris) { ?>
        <li  id="li_favoris">
            <a href="/favoris.php">
                <span class="icon"><i class="fas fa-bookmark"></i></span>
                <span class="title">Favoris</span>
            </a>
        </li>
        <?php } ?>
        <?php if ($showCandidatures) { ?>
        <li  id="li_candidatures">
            <a href="/candidatures.php">
                <span class="icon"><i class="fas fa-envelope"></i></span>
                <span class="title">Candidatures</span>
            </a>
        </li>
        <?php } ?>
        <?php if ($showGestions) { ?>
        <li id="li_gestions">
            <a href="/gestions.php">
                <span class="icon"><i class="fas fa-network-wired"></i></span>
                <span class="title">Gestions</span>
            </a>
        </li>
        <?php } ?>
                    <?php if ($showGestion_Enterprises) { ?>
                    <li class="sous_li" id="li_gestion_entreprises">
                        <a href="/gestion_entreprises.php">
                            <span class="icon"><i class="fas fa-angle-right"></i></span>
                            <span class="title">Entreprises</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($showGestion_Studients) { ?>
                    <li class="sous_li" id="li_gestion_etudiants">
                        <a href="/gestion_students.php">
                            <span class="icon"><i class="fas fa-angle-right"></i></span>
                            <span class="title">Etudiants</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($showGestion_Pilots) { ?>
                    <li class="sous_li" id="li_gestion_pilotes">
                        <a href="/gestion_pilots.php">
                            <span class="icon"><i class="fas fa-angle-right"></i></span>
                            <span class="title">Pilotes</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($showGestion_Delegates) { ?>
                    <li class="sous_li" id="li_gestion_delegues">
                        <a href="/gestion_delegates.php">
                            <span class="icon"><i class="fas fa-angle-right"></i></span>
                            <span class="title">Delegues</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($showGestion_Stages) { ?>
                    <li class="sous_li" id="li_gestion_stages">
                        <a href="/gestion_stages.php">
                            <span class="icon"><i class="fas fa-angle-right"></i></span>
                            <span class="title">Stages</span>
                        </a>
                    </li>
                    <?php } ?>
        <li id="li_parametres">
            <a href="/parameters.php">
                <span class="icon"><i class="fas fa-cog"></i></span>
                <span class="title">Parametres</span>
            </a>
        </li>
    </ul>
</div>