<?php
include("classe_control_inputs.php");
require_once("../DB/conexio_bd.php");

if (isset($_GET["id"])) $id_handset = $_GET["id"];
else $id_handset = 0;

// control buffering with output control functions
ob_start();

// anti-cache headers
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// send content headers
header("Content-Type: image/gif");
header('Content-Disposition: attachment; filename="phone.gif"');

$con_bd = new conexio_bd();
$sql = "select mini_imatge_gif from imatge_handset where id_handset=$id_handset";
$res = $con_bd->sql_query($sql);
if($res!=null)
{
  $row = $res->fetchRow();
  print "".($row['mini_imatge_gif']);
}

// flush content with ordered headers
ob_end_flush();

?>
