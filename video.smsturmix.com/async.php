<?php
include("classe_control_inputs.php");
require('../DB/conexio_bd.php');
require('constants_smsturmix.php');

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else $codi_contingut = '';

if (isset($_GET["op"])) $op = $_GET["op"];
elseif (isset($_POST["op"])) $op = $_POST["op"];
else die();

if (isset($_GET["v"])) $v = $_GET["v"];
elseif (isset($_POST["v"])) $v = $_POST["v"];
else $v = 0;

if(!is_numeric($v)) die();

switch($op)
{
	case 'vot':	$con_bd = new conexio_bd();
			$res = $con_bd->sql_query("select id_mm, puntuacio, vots from mm where codi_contingut='$codi_contingut'");
			if(!$res) die();
			$row = $res->fetchRow();
			$id_mm = $row['id_mm'];
			$puntuacio = $row['puntuacio'];
			$vots = $row['vots'];

			$nova_puntuacio = (($puntuacio*$vots) + $v) / ($vots + 1);

			$res = $con_bd->sql_query("update mm set puntuacio = $nova_puntuacio, data_insert = data_insert where id_mm = $id_mm limit 1;");
			if(!$res) die();

                        $res = $con_bd->sql_query("update mm set vots = vots + 1, data_insert = data_insert where id_mm = $id_mm limit 1;");
                        if(!$res) die();

			$ip = (getenv(HTTP_X_FORWARDED_FOR)) ?  getenv(HTTP_X_FORWARDED_FOR) :  getenv(REMOTE_ADDR); 
			$res = $con_bd->sql_query("insert into usuari_vot(ip, id_mm, data_insert) values ('$ip',$id_mm,NOW())");
			break;
}
     
?>
