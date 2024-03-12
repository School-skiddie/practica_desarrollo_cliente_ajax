<?php
    define("TITULO", "logout");
    include("head.php");

    session_destroy();

    header("Refresh:0; url=login.php");
?>