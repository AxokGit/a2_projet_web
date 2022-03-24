<?php
setcookie("username", "louis.dumont", time()+3600, "/");
setcookie("pass", "c499eec73d18319f4066758e1daf8c84a64e52f7", time()+3600, "/");

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
    echo $_COOKIE['username'].$_COOKIE['pass'];
} else {
    echo "non";
}
?>