<?php
include("classe_control_inputs.php");
require('classe_usuari_animacio.php');
require('constants_smsturmix.php');
require_once("conexio_bd.php");

if (isset($_GET["ih"])) $id_handset = $_GET["ih"];
elseif (isset($_POST["ih"])) $id_handset = $_POST["ih"];
else $id_handset = 581; //Per defecte es mostra el preview en un Nokia N70

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else die();

function obtenir_contingut_imatge_fitxer($file)
{
	$handle = fopen($file, "r");
	$content = fread($handle, filesize($file));
	fclose($handle);
	return $content;
}

function generar_preview_handset($imatge_animacio, $imatge_handset, $x_ini, $y_ini, $x_fi, $y_fi, $format)
{
	$width = $x_fi - $x_ini;
	$height = $y_fi - $y_ini;
	//Es crea un id aleatori per a la ruta temporal on es guardaràn els fitxers...
	srand((double)microtime()*1000000);
	$tmp_id = md5(rand(0,99999999));

	$dir_tmp = "/var/www/html/tmp/$tmp_id/";
	mkdir($dir_tmp); //Cal crear el directori on es copiaran els fitxers
	$file_input = "input.gif";
	$file_output = "output.gif";

	//Cal guardar l'animació original a disc...
	$file = fopen($dir_tmp."$file_input.gif", 'w', 1);
	fwrite($file, $imatge_animacio);
	fclose($file);

	//La següent comanda converteix la imatge al tamany de la pantalla del mòvil
	$cmd = "/usr/bin/gifsicle -l -d 100 -O2 --colors 256 --color-method median-cut --resize "."$width"."x"."$height ".($dir_tmp)."$file_input.gif -w -o ".($dir_tmp)."$file_output.gif";
	system($cmd);

	//Ara cal guardar la imatge del handset a disc...
	$im = imagecreatefromstring($imatge_handset);
	imagegif($im, $dir_tmp."handset.gif");	//Es guarda la imatge del handset al disc
	$x = imagesx($im); //Amplada de la imatge del handset
	$y = imagesy($im); //Llargada de la imatge del handset

	if($x>175)
	{
		$y=round(($y * 175)/$x);
		$x=175;
	}

	//Ara cal superposar el preview sobre de la pantalla
	$im = imagecreatefromstring(obtenir_contingut_imatge_fitxer($dir_tmp."$file_output.gif"));
	$cmd = "/usr/bin/gifsicle -l -d 100 -O2 --colors 256 --color-method median-cut --resize $x"."x"."$y ".$dir_tmp."handset.gif -p $x_ini,$y_ini ".$dir_tmp."$file_output.gif -o ".$dir_tmp."final.gif";
	system($cmd);

	//Es carrega l'animació final de disc a memòria
	$handset_preview = obtenir_contingut_imatge_fitxer($dir_tmp."final.gif");
	system("rm $dir_tmp/*.gif --force"); //S'eliminen els fitxers generats
	return $handset_preview;
}

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
//header('Content-Disposition: attachment; filename="preview.jpg"');

//S'obté la imatge del handset que solicita...
$conn = new conexio_bd();
$sql="select imatge_jpg as imatge_handset, x_ini, y_ini, x_fi, y_fi, orientacio_pantalla as format
	from imatge_handset
	where id_handset = $id_handset";
$res = $conn->sql_query($sql);

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
	header("Content-Type: image/gif");

	$row = $res->fetchRow();
	$x_ini = $row['x_ini'];
	$y_ini = $row['y_ini'];
	$x_fi = $row['x_fi'];
	$y_fi = $row['y_fi'];
	$format = $row['format'];
	$imatge_handset = $row['imatge_handset'];

	//Ara sobté l'animació solicitada...
	$sql = "select mm.id_mm as id_mm, mco.width as width, mco.height as height, co.contingut as contingut
		from mm_contingut_original mco, contingut_original co, mm mm
		where mm.codi_contingut = '$codi_contingut'
			and mco.id_mm = mm.id_mm
			and mco.format = 'NORMAL'
			and mco.id_contingut_original = co.id_contingut_original
			and mm.id_categoria_contingut = 2";

	$res = $conn->sql_query($sql);
	$row = $res->fetchRow();
	$imatge_animacio = $row['contingut'];

	print generar_preview_handset($imatge_animacio, $imatge_handset, $x_ini, $y_ini, $x_fi, $y_fi, $format);
}

// flush content with ordered headers
ob_end_flush();

?>
