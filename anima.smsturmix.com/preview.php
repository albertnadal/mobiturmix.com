<?php
include("classe_control_inputs.php");
require('../include/classe_usuari_animacio.php');

// control buffering with output control functions
session_start();
ob_start();

// anti-cache headers
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// send content headers
header("Content-Type: image/jpeg");
//header('Content-Disposition: attachment; filename="preview.jpg"');
print $_SESSION["usuari_animacio"]->animacions['handset_preview'];

// flush content with ordered headers
ob_end_flush();

?>
