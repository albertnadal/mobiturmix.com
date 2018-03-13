<?php
include("classe_control_inputs.php");
require('../DB/conexio_bd.php');
require('../include/crop_canvas/class.cropinterface.php');

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
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
//header('Content-Disposition: attachment; filename="phone.gif"');

$con_bd = new conexio_bd();
$sql = "	select mm.id_mm as id_mm, mco.width as width, mco.height as height, co.contingut as contingut
		from mm_contingut_original mco, contingut_original co, mm mm
		where mm.codi_contingut = '$codi_contingut'
			and mco.id_mm = mm.id_mm
			and mco.format = 'NORMAL'
			and mco.id_contingut_original = co.id_contingut_original
			and mm.id_categoria_contingut = 1";

$res = $con_bd->sql_query($sql);
$row = $res->fetchRow();

$string_image = $row['contingut'];
$image_width = $row['width'];
$image_height = $row['height'];
$ci =& new CropInterface(true);
$ci->resizeImage($string_image, $image_width, $image_height, 200, 200);
$wm = $ci->loadStringFromFile("wm.png");
//print $wm;
$ci->_imgOrig = $ci->_imgFinal;
//$ci->combineImage($wm, 200, 200, 0, 0, 200, 200, 25); //25% de transparencia
print $ci->loadStringFromImage("imatge_preview_".($row['id_mm']));

// flush content with ordered headers
ob_end_flush();

?>

