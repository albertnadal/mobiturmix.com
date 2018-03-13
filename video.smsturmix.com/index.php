<?php
include("classe_control_inputs.php");
require('classe_interficie_video_studio.php');

if (isset($_GET["op"])) $op = $_GET["op"];
elseif (isset($_POST["op"])) $op = $_POST["op"];
else $op = '';

if (isset($_GET["imh"])) $imh = $_GET["imh"];
elseif (isset($_POST["imh"])) $imh = $_POST["imh"];
else $imh = '';

if (isset($_GET["ih"])) $ih = $_GET["ih"];
elseif (isset($_POST["ih"])) $ih = $_POST["ih"];
else $ih = '';

if (isset($_GET["s"])) $cerca = $_GET["s"];
elseif (isset($_POST["s"])) $cerca = $_POST["s"];
else $cerca = '';

if (isset($_GET["c"])) $codi = $_GET["c"];
elseif (isset($_POST["c"])) $codi = $_POST["c"];
else $codi = '';

if (isset($_GET["ca"])) $categoria = $_GET["ca"];
elseif (isset($_POST["ca"])) $categoria = $_POST["ca"];
else $categoria = 0;

//print_r($_POST);

$if = new interficie_video_studio();
$if->mostrar_panell_fototeca($cerca, $op, $imh, $ih, $codi, $categoria);
?>
