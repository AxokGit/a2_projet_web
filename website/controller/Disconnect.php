<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
    setcookie("username", "", time() - 3600, "/");
    setcookie("pass", "", time() - 3600, "/");
    echo "<script>location.href='/';</script>";
} else {
    echo "<script>location.href='/';</script>";
}
?>