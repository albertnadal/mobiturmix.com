<?php
include("../include/crop_canvas/class.cropcanvas.php");
require('../include/crop_canvas/class.cropinterface.php');
include("classe_control_inputs.php");
require('conexio_bd.php');
require('constants_smsturmix.php');
require_once "JSON.phpi";

if (isset($_GET["user"])) $login = $_GET["user"];
elseif (isset($_POST["user"])) $login = $_POST["user"];
else $login = '';

if (isset($_GET["op"])) $op = $_GET["op"];
elseif (isset($_POST["op"])) $op = $_POST["op"];
else die();

switch($op)
{
	case 'get_avatar':
				$con_bd = new conexio_bd();
				$res = $con_bd->sql_query("	select	ua.image as image
								from	user_avatar ua,
									user u
								where	u.login = '$login'
									and ua.id_user = u.id_user");
				if(!$res) die();
				if(!($res->numRows())) header('Location: http://www.mobiturmix.com/accounts/uu.gif');
				else
				{
					$row = $res->fetchRow();
					header('Content-Type: image/gif');
					print $row['image'];
				}

				break;
	case 'get_mini_avatar':
				$con_bd = new conexio_bd();
                                $res = $con_bd->sql_query("     select  ua.image as image
                                                                from    user_avatar ua,
                                                                        user u
                                                                where   u.login = '$login'
                                                                        and ua.id_user = u.id_user");
                                if(!$res) die();
                                if(!($res->numRows())) header('Location: http://www.mobiturmix.com/accounts/uu.gif');
                                else
                                {
					ob_start();
					header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
					header("Content-Type: image/jpeg");
                                        $row = $res->fetchRow();
					$canvas = new CropCanvas();
			                $im = $canvas->imagecopyresized($row['image'], 38, 38);
			                imagejpeg($im, '', 50);
					ob_end_flush();
                                }

                                break;

/*        case 'get_cities':
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
*/
}
?>
