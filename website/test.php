<?php
require "controller/ConnexionBDD.php";
if (!$error) {
    $query_check_cookie = $bdd->prepare('SELECT * FROM users LIMIT :limite;');
    $query_check_cookie->bindValue(':limite', 2, PDO::PARAM_INT);
    $query_check_cookie->execute();
    echo $query_check_cookie->rowCount();
}
?>