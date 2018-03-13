<?php
require('../include/classe_interficie_video_studio.php');

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else $codi_contingut = '';

if (isset($_GET["imh"])) $id_marca_handset = $_GET["imh"];
elseif (isset($_POST["imh"])) $id_marca_handset = $_POST["imh"];
else $id_marca_handset = 1;

if (isset($_GET["ih"])) $id_handset = $_GET["ih"];
elseif (isset($_POST["ih"])) $id_handet = $_POST["ih"];
else $id_handset = 441; //Nokia 6680

$if = new interficie_video_studio();
$if->mostrar_info_video($codi_contingut, $id_marca_handset, $id_handset);
?>
