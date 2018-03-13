<?php
require('../include/classe_usuari_video.php');
require('../include/classe_interficie_video_studio.php');

$categories = array(    CATEGORIA_AMOR => false,
                        CATEGORIA_DEPORTE => false,
                        CATEGORIA_MUSICA => false,
                        CATEGORIA_AMIGOS => false,
                        CATEGORIA_FAMILIA => false,
                        CATEGORIA_PAISAJES => false,
                        CATEGORIA_VIAJES => false,
                        CATEGORIA_DIVERTIDA => false,
                        CATEGORIA_EROTICA => false);

/** Comprovació i processat dels paràmetres d'entrada generats per l'usuari **/

if (isset($_GET["a"])) $accio = $_GET["a"];
elseif (isset($_POST["a"])) $accio = $_POST["a"];
else $accio = '';

if (isset($_GET["confirm"])) $confirm = $_GET["confirm"];
elseif (isset($_POST["confirm"])) $confirm = $_POST["confirm"];
else $confirm = 'false';

if (isset($_GET["name"])) $name = $_GET["name"];
elseif (isset($_POST["name"])) $name = $_POST["name"];
else $name = '';

if (isset($_GET["description"])) $description = $_GET["description"];
elseif (isset($_POST["description"])) $description = $_POST["description"];
else $description = '';

if (isset($_GET["public"])) $public = $_GET["public"];
elseif (isset($_POST["public"])) $public = $_POST["public"];
else $public = 'Y';

if (isset($_GET["code"])) $code = $_GET["code"];
elseif (isset($_POST["code"])) $code = $_POST["code"];
else $code = '';

if (isset($_GET["imh"])) $id_marca_handset = $_GET["imh"];
elseif (isset($_POST["imh"])) $id_marca_handset = $_POST["imh"];
else $id_marca_handset = 1; //Per defecte es mostren els handsets de Nokia

if (isset($_GET["ih"])) $id_handset = $_GET["ih"];
elseif (isset($_POST["ih"])) $id_handset = $_POST["ih"];
else $id_handset = 779; //Per defecte es mostra el preview en un Nokia 6280

foreach($categories as $id_mm_categoria => $assignada)
        if ((isset($_GET[$id_mm_categoria]))||(isset($_POST[$id_mm_categoria]))) $categories[$id_mm_categoria] = true;

session_start();

$pas = $_SESSION["usuari_video"]->proxim_pas;
if($pas==MOSTRAR_REPORT) $_SESSION["usuari_video"]->proxim_pas = $pas = PUIJAR_VIDEO;

switch($accio)
{
        case 's':       $pas = $_SESSION["usuari_video"]->processar_pas_actual($_FILES, $confirm, $name, $description, $public, $code, $categories); break;
	case 'a':       $_SESSION["usuari_video"]->proxim_pas = --$pas; break;
}

$if = new interficie_video_studio();

switch($pas)
{
        case PUIJAR_VIDEO               :       $_SESSION["usuari_video"] = & new usuari();
						$if->mostrar_panell_puijada_video(); break;
        case VALIDAR_PREVIEW            :       $if->mostrar_panell_validar_preview($id_marca_handset,$id_handset); break;
        case ENTRAR_METAINFORMACIO      :       $if->mostrar_panell_entrar_metainformacio(); break;
        case MOSTRAR_REPORT             :       $if->mostrar_panell_report_final(); break;
        default                         :       $if->mostrar_panell_puijada_video();
}
?>
