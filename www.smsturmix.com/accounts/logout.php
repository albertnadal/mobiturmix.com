<?php
include("classe_control_inputs.php");
include("classe_sessio_usuari.php");

session_start;
$_SESSION["sessio_usuari"]->fer_logout();
?>
