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
header("Content-Type: image/gif");
//header("Content-Disposition: attachment; filename=\"$codi_contingut.jpg\"");

$con_bd = new conexio_bd();
$sql = "select preview_jpg, id_mm from mm where codi_contingut=\"$codi_contingut\"";
$res = $con_bd->sql_query($sql);
if($res!=null)
{
  $row = $res->fetchRow();
//  $im = imagecreatefromstring($row['preview_jpg']);
//  imagejpeg($im, '', 80);
//  print $row['preview_jpg'];


  switch($es_front)
  {
	case 5: if($es_front==5) $imatge_polaroid = file_get_contents('/var/www/html/www.smsturmix.com/frame2.jpg');
        case 4: if($es_front==4) $imatge_polaroid = file_get_contents('/var/www/html/www.smsturmix.com/frame_red.jpg');
        case 3:	if($es_front==3) $imatge_polaroid = file_get_contents('/var/www/html/www.smsturmix.com/frame.jpg');

                $x_ini = 11;
                $y_ini = 0;
                $x_fi = 99+3;
                $y_fi = 90;

                $string_image = $row['preview_jpg'];
                $image_width = 88 - 3;
                $image_height = 90 - 6;

                $ci =& new CropInterface(true);
                $ci->loadImageFromString($imatge_polaroid);
                $ci->combineImage($string_image, $image_width, $image_height, $x_ini, $y_ini, $x_fi, $y_fi);

                if($es_front==5) $ample_maxim = 55;
		else $ample_maxim = 175;

                $image = $ci->_imgOrig;
                if(imagesx($image)>$ample_maxim)
                {
                        $width = $ample_maxim;
                        $height = (imagesy($image)*$width)/imagesx($image);
                        $ci->resizeImageFromImage($ci->_imgOrig, $width, $height);
                }
                print $ci->loadStringFromImage("frame_".($row['id_mm']));
                break;
	case 2:	$canvas = new CropCanvas();
		$im = $canvas->imagecopyresized($row['preview_jpg'], 37, 28, 'gif');
		imagegif($im, '', 50);
		break;
        case 1:	$canvas = new CropCanvas();
		$im = $canvas->imagecopyresized($row['preview_jpg'], 53, 40, 'gif');
		imagegif($im, '', 50);
                break;
        case 0:	print $row['preview_jpg'];
                break;
  }
}
// flush content with ordered headers
ob_end_flush();

?>
