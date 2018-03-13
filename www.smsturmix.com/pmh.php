<?php
require('../DB/conexio_bd.php');

if (isset($_GET["imh"])) $id_marca_handset = $_GET["imh"];
elseif (isset($_POST["imh"])) $id_marca_handset = $_POST["imh"];
else die();
//if(!is_number($id_marca_handset)) die();

// control buffering with output control functions
ob_start();

// anti-cache headers
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// send content headers
header("Content-Type: image/png");
//header('Content-Disposition: attachment; filename="phone.gif"');

$con_bd = new conexio_bd();
$sql = "	select	logo_png
		from	marca_handset
		where	id_marca_handset=$id_marca_handset";

$res = $con_bd->sql_query($sql);
$row = $res->fetchRow();
print $row['logo_png'];
ob_end_flush();

?>

