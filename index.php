<!DOCTYPE html>
<html lang="es">
<?php
include("classes/autoload.php");

define('TITULO', "Index");

include("head.php");
include("side.php");

if (!isset($_SESSION["usuario"])) header("Refresh:0; url=login.php");
?>

<body id="body-pd">
</body>

</html>