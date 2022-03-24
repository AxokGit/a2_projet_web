<?php

print_r($_COOKIE);

if (isset($_COOKIE['username']) && isset($_COOKIE['pass'])) {
    echo $_COOKIE['username'].$_COOKIE['pass'];
} else {
    echo "non";
}
?>