<?php
include("classe_control_inputs.php");
require('../DB/conexio_bd.php');
require('constants_smsturmix.php');
require_once "JSON.phpi";

if (isset($_GET["imh"])) $id_marca_handset = $_GET["imh"];
elseif (isset($_POST["imh"])) $id_marca_handset = $_POST["imh"];
else $id_marca_handset = 0;

if (isset($_GET["op"])) $op = $_GET["op"];
elseif (isset($_POST["op"])) $op = $_POST["op"];
else die();

switch($op)
{
	case 'get_handsets':
			        $json = new Services_JSON();
			        $input = $json->decode($resposta);

				$con_bd = new conexio_bd();
				$res = $con_bd->sql_query("select id_handset, model from handset where id_marca_handset=$id_marca_handset order by model asc");
				if(!$res) die();
				$row = $res->fetchRow();
				$output = array();
				$id_handsets = array();
				$name_handsets = array();
				while($row=$res->fetchRow())
				{
					array_push($id_handsets, $row['id_handset']);
					array_push($name_handsets, $row['model']);
				}

				$output['id_handset'] = $id_handsets;
				$output['name_handset'] = $name_handsets;

				print $json->encode($output);
				break;
}
?>
