<?php
include("classe_control_inputs.php");
require('classe_interficie_video_studio.php');

if (isset($_GET["imh"])) $imh = $_GET["imh"];
elseif (isset($_POST["imh"])) $imh = $_POST["imh"];
else $imh = '';

if (isset($_GET["ih"])) $ih = $_GET["ih"];
elseif (isset($_POST["ih"])) $ih = $_POST["ih"];
else $ih = '';

//print_r($_POST);

$if = new interficie_video_studio();
if((is_numeric($imh))&&(!$imh)) $if->mostrar_panell_marques();
else if((is_numeric($imh))&&($imh>0)) $if->mostrar_panell_handsets($imh);
else die();
?>
