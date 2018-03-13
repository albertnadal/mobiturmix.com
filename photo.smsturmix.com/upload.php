<?php
include("classe_control_inputs.php");
require('classe_interficie_wallpaper_studio.php');
require('classe_usuari.php');
require_once("classe_enviament_emails.php");

$categories = array(	CATEGORIA_AMOR => false,
			CATEGORIA_DEPORTE => false,
			CATEGORIA_MUSICA => false,
			CATEGORIA_AMIGOS => false,
			CATEGORIA_FAMILIA => false,
			CATEGORIA_PAISAJES => false,
			CATEGORIA_VIAJES => false,
			CATEGORIA_DIVERTIDA => false,
			CATEGORIA_EROTICA => false);

/** Comprovació i processat dels par�etres d'entrada generats per l'usuari **/
if (isset($_GET["a"])) $accio = $_GET["a"];
elseif (isset($_POST["a"])) $accio = $_POST["a"];
else $accio = '';

if (isset($_GET["sx"])) $sx = $_GET["sx"];
elseif (isset($_POST["sx"])) $sx = $_POST["sx"];
else $sx = null;

if (isset($_GET["sy"])) $sy = $_GET["sy"];
elseif (isset($_POST["sy"])) $sy = $_POST["sy"];
else $sy = null;

if (isset($_GET["ex"])) $ex = $_GET["ex"];
elseif (isset($_POST["ex"])) $ex = $_POST["ex"];
else $ex = null;

if (isset($_GET["ey"])) $ey = $_GET["ey"];
elseif (isset($_POST["ey"])) $ey = $_POST["ey"];
else $ey = null;

if (isset($_GET["confirm"])) $confirm = $_GET["confirm"];
elseif (isset($_POST["confirm"])) $confirm = $_POST["confirm"];
else $confirm = 'false';

if (isset($_GET["email"])) $email = $_GET["email"];
elseif (isset($_POST["email"])) $email = $_POST["email"];
else $email = '';

if (isset($_GET["name"])) $name = $_GET["name"];
elseif (isset($_POST["name"])) $name = $_POST["name"];
else $name = '';

if (isset($_GET["description"])) $description = $_GET["description"];
elseif (isset($_POST["description"])) $description = $_POST["description"];
else $description = '';

if ((isset($_GET["public"]))||(isset($_POST["public"]))) $public = 'Y';
else $public = 'N';

if (isset($_GET["code"])) $code = $_GET["code"];
elseif (isset($_POST["code"])) $code = $_POST["code"];
else $code = '';

if (isset($_GET["imh"])) $id_marca_handset = $_GET["imh"];
elseif (isset($_POST["imh"])) $id_marca_handset = $_POST["imh"];
else $id_marca_handset = 1; //Per defecte es mostren els handsets de Nokia

if (isset($_GET["ih"])) $id_handset = $_GET["ih"];
elseif (isset($_POST["ih"])) $id_handset = $_POST["ih"];
else $id_handset = 581; //Per defecte es mostra el preview en un Nokia N70

foreach($categories as $id_mm_categoria => $assignada)
	if ((isset($_GET[$id_mm_categoria]))||(isset($_POST[$id_mm_categoria]))) $categories[$id_mm_categoria] = true;
/*
print_r($_POST);
print_r($_GET);
print_r($categories);
*/

session_start();

$pas = $_SESSION["usuari"]->proxim_pas;

if($pas==MOSTRAR_REPORT)
{
/*	print "MOSTRANT REPORT<br>";
	print "<pre>";
	print_r($_GET);
	print_r($_POST);
	print "</pre>";
*/
        $path_preview = "/var/www/html/tmp/".($_SESSION["usuari"]->codi_usuari).".gif";
        if($email!='')
        {

//                print "PATH: $path_preview EMAILS: $email";
                $codi_contingut = $_SESSION["usuari"]->codi_contingut;
//		print_r($_SESSION["usuari"]->codi_contingut);
                $enviador = new enviador_emails();
//		print "CODI: $codi_contingut<br>\n";
                $enviador->enviar_email_a_amics($email, $codi_contingut, $path_preview, PHOTO);
//                print "email enviat!";
        }
        system("rm -f $path_preview");
	$_SESSION["usuari"]->proxim_pas = $pas = PUIJAR_ORIGINAL;
}

switch($accio)
{
	case 'c':	$pas = $_SESSION["usuari"]->proxim_pas = PUIJAR_ORIGINAL; break;
	case 's':	$pas = $_SESSION["usuari"]->processar_pas_actual($sx, $sy, $ex, $ey, $confirm, $name, $description, $public, $code, $categories); break;
	case 'a':	$_SESSION["usuari"]->proxim_pas = --$pas; break;
}

//print "STEP: $pas<br>";

/** Mostrar el panell corresponen al pas actual **/
$if = new interficie_wallpaper_studio();

switch($pas)
{
        case PUIJAR_ORIGINAL            :	$_SESSION["usuari"] = & new usuari();
					        $if->mostrar_panell_puijada_foto_original(); break;
        case RETALLAR_NORMAL            :       $if->mostrar_panell_retallar_wallpaper(RETALLAR_NORMAL); break;
        case RETALLAR_VERTICAL          :       $if->mostrar_panell_retallar_wallpaper(RETALLAR_VERTICAL); break;
        case RETALLAR_APAISAT           :       $if->mostrar_panell_retallar_wallpaper(RETALLAR_APAISAT); break;
        case VALIDAR_PREVIEW            :       $if->mostrar_panell_validar_preview($id_marca_handset,$id_handset); break;
        case ENTRAR_METAINFORMACIO      :       $if->mostrar_panell_entrar_metainformacio(); break;
        case MOSTRAR_REPORT             :       $if->mostrar_panell_report_final(); /* $_SESSION["usuari"] = & new usuari(); */ break;
        default                         :       $if->mostrar_panell_puijada_foto_original();
}
?>
