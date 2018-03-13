<?php
require('../DB/conexio_bd.php');
//require('../include/crop_canvas/class.cropinterface.php');

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else $codi_contingut = '';

//control buffering with output control functions
ob_start();

header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
header("Content-Type: text/plain; charset=UTF-8");

$con_bd = new conexio_bd();
$sql = "        select mm.id_mm as id_mm, mco.width as width, mco.height as height, co.contingut as contingut
                from mm_contingut_original mco, contingut_original co, mm mm
                where mm.codi_contingut = '$codi_contingut'
                        and mco.id_mm = mm.id_mm
                        and mco.format = 'NORMAL'
                        and (mco.video_size = 'cif' or mco.video_size = 'qcif')
                        and mco.video_codec = 'flv'
                        and mco.id_contingut_original = co.id_contingut_original
                        and mm.id_categoria_contingut = 3";

//print "SQL: $sql<br>";

$res = $con_bd->sql_query($sql);
$row = $res->fetchRow();
//print "SQL: $sql<br>\n";

$contingut = $row['contingut'];
//header("Content-Length: ".(sizeof($contingut)));
header("Accept-Ranges: bytes");
header("Keep-Alive: timeout=15, max=92");

print $contingut;

//flush content with ordered headers
ob_end_flush();
?>
