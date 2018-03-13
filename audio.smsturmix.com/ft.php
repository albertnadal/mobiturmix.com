<?php
require('../include/classe_interficie_video_studio.php');

if (isset($_GET["s"])) $cerca = $_GET["s"];
elseif (isset($_POST["s"])) $cerca = $_POST["s"];
else $cerca = '';

//print_r($_POST);

$if = new interficie_video_studio();
$if->mostrar_panell_fototeca($cerca);
?>
