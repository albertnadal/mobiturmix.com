<?php
include("classe_control_inputs.php");
include("classe_sessio_usuari.php");

if (isset($_POST["login"])) $login = $_POST["login"];
else $login = '';

if (isset($_POST["pwd"])) $password = $_POST["pwd"];
else $password = '';

session_start;
$_SESSION["sessio_usuari"]->fer_login($login, $password);
?>
