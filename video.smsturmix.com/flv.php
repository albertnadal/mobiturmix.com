<?php
require('../DB/conexio_bd.php');
//require('../include/crop_canvas/class.cropinterface.php');

$nom_fitxer = mysql_escape_string(str_replace(".flv", "", $_SERVER['PHP_SELF']));
$codi_contingut = trim($nom_fitxer, "/");

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

$res = $con_bd->sql_query($sql);
if($res==null) die();
$row = $res->fetchRow();
//print "SQL: $sql<br>\n";

//$contingut = $row['contingut'];
//print "SIZE:".(strlen($contingut))."<br>";

//die();



//control buffering with output control functions
ob_start();

header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Content-Type: text/plain; charset=UTF-8");

header("Content-Length: ".(strlen($row['contingut'])));
header("Accept-Ranges: bytes");
header("Keep-Alive: timeout=15, max=92");

$parts = str_split($row['contingut'], 1024);

for($i=0; $i<1024000; $i++)
{
	print $parts[$i];
	ob_end_flush();
}

//flush content with ordered headers
ob_end_flush();
?>
