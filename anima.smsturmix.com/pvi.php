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
			and mm.id_categoria_contingut = 2";

$res = $con_bd->sql_query($sql);
$row = $res->fetchRow();

$string_image = $row['contingut'];
$width = $row['width'];
$height = $row['height'];

$handle = fopen("/var/www/html/tmp/".($codi_contingut).".gif", "w");
fwrite($handle, $string_image);
fclose($handle);

$cmd = "/usr/bin/gifsicle -l -d 150 -O2 --colors 256 --color-method median-cut --resize 200x200";
$cmd .= " /var/www/html/tmp/".($codi_contingut).".gif -o /var/www/html/tmp/out_".($codi_contingut).".gif";
system($cmd);
$handle = fopen("/var/www/html/tmp/out_".($codi_contingut).".gif", "r");
$content = fread($handle, filesize("/var/www/html/tmp/out_".($codi_contingut).".gif"));
fclose($handle);
system("rm /var/www/tml/tmp/out_".($codi_contingut).".gif -f");
system("rm /var/www/tml/tmp/".($codi_contingut).".gif -f");
print $content;
// flush content with ordered headers
ob_end_flush();

?>

