<?php
include("classe_control_inputs.php");
require('../DB/conexio_bd.php');
require('../include/crop_canvas/class.cropinterface.php');
require('constants_smsturmix.php');

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else $codi_contingut = '';

if (isset($_GET["ih"])) $id_handset = $_GET["ih"];
elseif (isset($_POST["ih"])) $id_handset = $_POST["ih"];
else $id_handset = 441; //Nokia 6680

//if((isset($_GET["ih"]))&&($_GET["ih"]=='')) $id_handset = 441;

// control buffering with output control functions
ob_start();

// anti-cache headers
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// send content headers
//header('Content-Disposition: attachment; filename="phone.gif"');

        $con_bd = new conexio_bd();
        $res = $con_bd->sql_query("select * from imatge_handset where id_handset = $id_handset");
	if($id_handset=='')
	{
		header("Content-Type: image/jpg");
		print base64_decode(IMATGE_PREVIEW_NO_DISPONIBLE);
	}
        else if($res==null) die();
	else if(!$res->numRows())
	{
		header("Content-Type: image/jpg");
		print base64_decode(IMATGE_PREVIEW_NO_DISPONIBLE);
	}
        else
        {
		header("Content-Type: image/jpg");
                $row = $res->fetchRow();
                $imatge_handset = $row['imatge_jpg'];
                $orientacio = strtolower($row['orientacio_pantalla']);
                $x_ini = $row['x_ini'];
                $y_ini = $row['y_ini'];
                $x_fi = $row['x_fi'];
                $y_fi = $row['y_fi'];

		$sql = "select mm.id_mm as id_mm, mco.width as width, mco.height as height, co.contingut as contingut
			from mm_contingut_original mco, contingut_original co, mm mm
			where mm.codi_contingut = '$codi_contingut'
				and mco.id_mm = mm.id_mm
				and mco.format = '".($row['orientacio_pantalla'])."'
				and mco.id_contingut_original = co.id_contingut_original";

		$res = $con_bd->sql_query($sql);
                $row = $res->fetchRow();

                $string_image = $row['contingut'];
                $image_width = $row['width'];
                $image_height = $row['height'];
                $ci =& new CropInterface(true);
                $ci->loadImageFromString($imatge_handset);
                $ci->combineImage($string_image, $image_width, $image_height, $x_ini, $y_ini, $x_fi, $y_fi);

		$ample_maxim = 175;
		$image = $ci->_imgOrig;
		if(imagesx($image)>$ample_maxim)
		{
        	        $width = $ample_maxim;
                	$height = (imagesy($image)*$width)/imagesx($image);
			$ci->resizeImageFromImage($ci->_imgOrig, $width, $height);
		}
                print $ci->loadStringFromImage("handset_preview_".($row['id_mm'])."_$orientacio");
        }

// flush content with ordered headers
ob_end_flush();

?>

