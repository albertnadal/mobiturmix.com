<?php
//include("classe_control_inputs.php");
require('classe_usuari_video.php');


session_start();
ob_start();

header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");

$codi_usuari = $_SESSION["usuari_video"]->codi_usuari;

header("Content-Type: image/gif");
header("Content-Length: ".(filesize("/var/www/html/tmp/".$codi_usuari."/final_preview.gif")));

print file_get_contents("/var/www/html/tmp/".$codi_usuari."/final_preview.gif");


ob_end_flush();

?>
