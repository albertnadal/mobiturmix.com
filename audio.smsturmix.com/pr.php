<?php
require_once("../DB/conexio_bd.php");

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
else $codi_contingut = '';

// control buffering with output control functions
ob_start();

// anti-cache headers
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// send content headers
header("Content-Type: image/gif");
//header("Content-Disposition: attachment; filename=\"$codi_contingut.jpg\"");

$con_bd = new conexio_bd();
$sql = "select preview_jpg from mm where codi_contingut=\"$codi_contingut\"";
$res = $con_bd->sql_query($sql);
if($res!=null)
{
  $row = $res->fetchRow();
//  $im = imagecreatefromstring($row['preview_jpg']);
//  imagejpeg($im, '', 80);
//  print $row['preview_jpg'];
	print $row['preview_jpg'];
}
// flush content with ordered headers
ob_end_flush();

?>
