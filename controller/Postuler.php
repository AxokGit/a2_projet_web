<?php
session_start();

if (isset($_SESSION["username"])){
    include "../controller/ConnexionBDD.php";
    if (!$error) {
        if (is_uploaded_file($_FILES['cv']['tmp_name']) && is_uploaded_file($_FILES['lm']['tmp_name']) && isset($_POST["ID_internship"])) {
            $query_ID_user = $bdd->prepare('SELECT ID_user FROM users WHERE username=:user;');
            $query_ID_user->execute(['user' => $_SESSION["username"]]);
            $ID_user = $query_ID_user->fetchALL(PDO::FETCH_OBJ)[0]->ID_user;

            $location_cv = "/documents/users/".$ID_user."/candidatures/".$_POST["ID_internship"]."/".$_FILES['cv']['name'];
            $location_lm = "/documents/users/".$ID_user."/candidatures/".$_POST["ID_internship"]."/".$_FILES['lm']['name'];

            echo $location_cv;
            echo "<br>";
            echo $location_lm;

            try {
                if (!is_dir('../documents/')){
                    mkdir("../documents/", 0700);
                }
                if (!is_dir('../documents/users')){
                    mkdir("../documents/users", 0700);
                }
                if (!is_dir("../documents/users/".$ID_user."/")){
                    mkdir("../documents/users/".$ID_user."/", 0700);
                }
                if (!is_dir("../documents/users/".$ID_user."/"."candidatures/")){
                    mkdir("../documents/users/".$ID_user."/"."candidatures/", 0700);
                }
                if (!is_dir("../documents/users/".$ID_user."/"."candidatures/".$_POST["ID_internship"])){
                    mkdir("../documents/users/".$ID_user."/"."candidatures/".$_POST["ID_internship"], 0700);
                }

                move_uploaded_file($_FILES['cv']['tmp_name'], "..".$location_cv);
                move_uploaded_file($_FILES['lm']['tmp_name'], "..".$location_lm);

                $query_ID_user = $bdd->prepare('INSERT INTO candidatures VALUES (NULL, "1", :location_cv, :location_lm, NULL, NULL, :ID_user, :ID_internship);');
                $query_ID_user->execute(['location_cv' => $location_cv, 'location_lm' => $location_lm, 'ID_user' => $ID_user, 'ID_internship' => $_POST["ID_internship"]]);
                
                echo "<script>location.href='/offres_stages.php';</script>";
            }
            catch (Exception $e) {
                echo "zut une erreur";
            }
        } else {echo "POST file incomplet";}
    }
}
?>