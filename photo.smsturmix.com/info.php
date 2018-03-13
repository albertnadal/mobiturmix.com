<?php
include("classe_control_inputs.php");
require('classe_interficie_wallpaper_studio.php');

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else $codi_contingut = '';

if (isset($_GET["p"])) $pag = $_GET["p"];
elseif (isset($_POST["p"])) $pag = $_POST["p"];
else $pag = 1;

if (isset($_GET["s"])) $cerca = $_GET["s"];
elseif (isset($_POST["s"])) $cerca = $_POST["s"];
else $cerca = '';

if (isset($_GET["ca"])) $categoria = $_GET["ca"];
elseif (isset($_POST["ca"])) $categoria = $_POST["ca"];
else $categoria = '';

if (isset($_GET["imh"])) $id_marca_handset = $_GET["imh"];
elseif (isset($_POST["imh"])) $id_marca_handset = $_POST["imh"];
elseif (isset($_GET["ih"])) $id_marca_handset = '';
else $id_marca_handset = 1;

if (isset($_GET["ih"])) $id_handset = $_GET["ih"];
elseif (isset($_POST["ih"])) $id_handset = $_POST["ih"];

if(((!isset($_GET["ih"]))||($id_handset==''))&&(!isset($_GET["imh"])))
{
	$id_handset = 441; //Nokia 6680
	$id_marca_handset = 1; //Nokia
}

if (isset($_GET["iih"])) $init_id_handset = $_GET["iih"];
elseif (isset($_POST["iih"])) $init_id_handet = $_POST["iih"];
else $init_id_handset = ''; //Nokia 6680

$if = new interficie_wallpaper_studio();
$if->mostrar_info_foto($codi_contingut, $id_marca_handset, $id_handset, $categoria, $pag, $cerca, $init_id_handset);
?>
