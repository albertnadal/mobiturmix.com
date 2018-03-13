<?php
include("classe_control_inputs.php");
//require('classe_interficie_video_studio.php');
require('classe_interficie_llistar_continguts.php');

if (isset($_GET["c"])) $categoria = $_GET["c"];
elseif (isset($_POST["c"])) $categoria = $_POST["c"];
else $categoria = MES_RECENT;

if (isset($_GET["s"])) $cerca = $_GET["s"];
elseif (isset($_POST["s"])) $cerca = $_POST["s"];
else $cerca = '';

if (isset($_GET["p"])) $pag = $_GET["p"];
elseif (isset($_POST["p"])) $pag = $_POST["p"];
else $pag = 1;

if (isset($_GET["v"])) $vista = $_GET["v"];
elseif (isset($_POST["v"])) $vista = $_POST["v"];
else $vista = 'l';

if (isset($_GET["ih"])) $ih = $_GET["ih"];
elseif (isset($_POST["ih"])) $ih = $_POST["ih"];
else $ih = '';

//print_r($_POST);

$if = new interficie_llistar_continguts();
$if->mostrar_categoria(VIDEO, $categoria, $cerca, $pag, $vista, $ih, $codi_contingut);
?>
