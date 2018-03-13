<?php
include("classe_control_inputs.php");
require('../DB/conexio_bd.php');
require('constants_smsturmix.php');
require_once "JSON.phpi";

if (isset($_GET["id_country"])) $id_country = $_GET["id_country"];
elseif (isset($_POST["id_country"])) $id_country = $_POST["id_country"];
else $id_country = 0;

if (isset($_GET["id_state"])) $id_state = $_GET["id_state"];
elseif (isset($_POST["id_state"])) $id_state = $_POST["id_state"];
else $id_state = 0;

if (isset($_GET["id_city"])) $id_city = $_GET["id_city"];
elseif (isset($_POST["id_city"])) $id_city = $_POST["id_city"];
else $id_city = 0;

if (isset($_GET["op"])) $op = $_GET["op"];
elseif (isset($_POST["op"])) $op = $_POST["op"];
else die();

if (isset($_GET["v"])) $v = $_GET["v"];
elseif (isset($_POST["v"])) $v = $_POST["v"];
else $v = 0;

switch($op)
{
	case 'get_states':
			        $json = new Services_JSON();
			        $input = $json->decode($resposta);

				$con_bd = new conexio_bd();
				$res = $con_bd->sql_query("select id_state, nom from state where id_country=$id_country order by nom asc");
				if(!$res) die();
				$row = $res->fetchRow();
				$output = array();
				$id_states = array();
				$name_states = array();
				while($row=$res->fetchRow())
				{
					array_push($id_states, $row['id_state']);
					array_push($name_states, $row['nom']);
				}

                                $con_bd = new conexio_bd();
                                $res = $con_bd->sql_query("select coordinates from country where id_country=$id_country");
				$row=$res->fetchRow();
				$coordinates = $row['coordinates'];
				$coordinates = explode(',', $coordinates);
				list($y, $x) = $coordinates;

				$output['id_state'] = $id_states;
				$output['name_state'] = $name_states;
				$output['y'] = $y;
				$output['x'] = $x;

				print $json->encode($output);
				break;
        case 'get_cities':
                                $json = new Services_JSON();
                                $input = $json->decode($resposta);

                                $con_bd = new conexio_bd();
                                $res = $con_bd->sql_query("select id_city, nom from city where id_state=$id_state order by nom asc");

				//print "select id_city, nom from city where id_state=$id_state order by nom asc";

                                if(!$res) die();
                                $row = $res->fetchRow();
                                $output = array();
                                $id_cities = array();
                                $name_cities = array();
                                while($row=$res->fetchRow())
                                {
                                        array_push($id_cities, $row['id_city']);
                                        array_push($name_cities, $row['nom']);
                                }

                                $con_bd = new conexio_bd();
                                $res = $con_bd->sql_query("select coordinates from state where id_state=$id_state");
                                $row=$res->fetchRow();
                                $coordinates = $row['coordinates'];
                                $coordinates = explode(',', $coordinates);
                                list($y, $x) = $coordinates;

                                $output['id_city'] = $id_cities;
                                $output['name_city'] = $name_cities;
                                $output['y'] = $y;
                                $output['x'] = $x;

                                print $json->encode($output);
                                break;
	case 'get_city':
                                $json = new Services_JSON();
                                $input = $json->decode($resposta);

                                $con_bd = new conexio_bd();

				$output = array();
                                $res = $con_bd->sql_query("select nom, coordinates from city where id_city=$id_city");
                                $row=$res->fetchRow();
                                $coordinates = $row['coordinates'];
                                $coordinates = explode(',', $coordinates);
                                list($y, $x, $z) = $coordinates;

                                $output['name_city'] = $row['nom'];
                                $output['y'] = $y;
                                $output['x'] = $x;
				//$output['z'] = $z;

                                print $json->encode($output);
                                break;
}
?>
