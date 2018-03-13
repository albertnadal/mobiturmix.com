<?php

require('constants_smsturmix.php');
include("classe_control_inputs.php");
include("../include/crop_canvas/class.cropcanvas.php");
require('../include/crop_canvas/class.cropinterface.php');
require_once("../DB/conexio_bd.php");

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
else $codi_contingut = '';

if (isset($_GET["f"])) $es_front = $_GET["f"];
else $es_front = 0;

// control buffering with output control functions
ob_start();

// anti-cache headers
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// send content headers
header("Content-Type: image/jpeg");
//header("Content-Disposition: attachment; filename=\"$codi_contingut.jpg\"");

$con_bd = new conexio_bd();
$sql = "	select	mm.preview_jpg as preview_jpg,
			mm.id_mm as id_mm,
			s.name as name
		from	mm mm,
			source s
		where	mm.codi_contingut=\"$codi_contingut\"
			and s.id_source = mm.id_source";
$res = $con_bd->sql_query($sql);
if($res!=null)
{
  $row = $res->fetchRow();
  switch($es_front)
  {
	case 2: $imatge_polaroid = file_get_contents("/var/www/html/www.smsturmix.com/polaroid_".($row['name']).".jpg");

                $x_ini = 10;
                $y_ini = 10;
                $x_fi = 80;
                $y_fi = 80;

                $string_image = $row['preview_jpg'];
                $image_width = 70;
                $image_height = 70;

                $ci =& new CropInterface(true);
                $ci->loadImageFromString($imatge_polaroid);
                $ci->combineImage($string_image, $image_width, $image_height, $x_ini, $y_ini, $x_fi, $y_fi);

		$ample_maxim = 45;

                $image = $ci->_imgOrig;
                if(imagesx($image)>$ample_maxim)
                {
                        $width = $ample_maxim;
                        $height = (imagesy($image)*$width)/imagesx($image);
                        $ci->resizeImageFromImage($ci->_imgOrig, $width, $height);
                }
                print $ci->loadStringFromImage("polaroid_preview_".($row['id_mm']));	
		break;
	case 1:	$canvas = new CropCanvas();
		$im = $canvas->imagecopyresized($row['preview_jpg'], 50, 50);
		imagejpeg($im, '', 50);
		break;

	case 0:	$im = imagecreatefromstring($row['preview_jpg']);
		imagejpeg($im, '', 70);
		break;
  }
//  print $row['preview_jpg'];
}
// flush content with ordered headers
ob_end_flush();

?>
