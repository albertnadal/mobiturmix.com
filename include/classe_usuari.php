<?
//define("UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT']."/tmp/");
define("UPLOAD_DIR", "/tmp/");

define("PUIJAR_ORIGINAL",               0);
define("RETALLAR_NORMAL",               1);
define("RETALLAR_VERTICAL",             2);
define("RETALLAR_APAISAT",              3);
define("VALIDAR_PREVIEW",               4);
define("ENTRAR_METAINFORMACIO",         5);
define("MOSTRAR_REPORT",                6);

define("WALLPAPER_NORMAL_SIZE",         '1:1');
define("WALLPAPER_VERTICAL_SIZE",       '75:100');
define("WALLPAPER_APAISAT_SIZE",        '100:75');

define("MIN_IMAGE_SIZE", 200);
define("MAX_IMAGE_SIZE", 400);

define("PREVIEW_WIDTH",  85);
define("PREVIEW_HEIGHT", 85);

define("TRUE", 'true');
define("FALSE", 'false');

define("RATIO_VOCALS_CODI_CONTINGUT", 65); //65% presència de vocals als codi de contingut aleatoris
define("MIN_LONG_CODI_CONTINGUT", 4);
define("MAX_LONG_CODI_CONTINGUT", 20);

require_once("../DB/conexio_bd.php");
include("../include/file_uploader/file_uploader.php");
require_once("classe_sessio_usuari.php");

function desar_imatge_en_sessio($contingut, $nom_fitxer, $mime_type, $size, $sizes, $tipus)
{
//	if(!isset($_SESSION["sessio_usuari"])) session_start();
        $sid = $_COOKIE['tmixsid'];
        session_id($sid);
        $_SESSION['session_id'] = $sid;
	session_start();

	$_SESSION["usuari"]->wallpaper[$tipus] = $contingut;
        $_SESSION["usuari"]->nom_fitxer = $nom_fitxer;
        $_SESSION["usuari"]->tamany_fitxer = $size;
        $_SESSION["usuari"]->tamanys_imatge = $sizes;
        $_SESSION["usuari"]->width = $sizes[0];
        $_SESSION["usuari"]->height = $sizes[1];
        $_SESSION["usuari"]->ajustar_tamany_imatge_original();
//        print "Tamany imatge: ".($_SESSION["usuari"]->tamany_fitxer);
        $_SESSION["usuari"]->proxim_pas = RETALLAR_NORMAL;
}

class usuari
{
        //atributos de la clase
        var $wallpaper = array( 'original'              =>      null,
                                'apaisat'               =>      null,                           //100:75
                                'vertical'              =>      null,                           //75:100
                                'normal'                =>      null,                           //1:1
                                'preview'               =>      null,                           //85x85
                                'handset_preview'       =>      null);                          //?

        var $wallpaper_tamany = array(  'normal'   =>      array('width'=>0, 'height'=>0),
                                        'vertical' =>      array('width'=>0, 'height'=>0),
                                        'apaisat'  =>      array('width'=>0, 'height'=>0),
                                        'preview'  =>      array('width'=>PREVIEW_WIDTH, 'height'=>PREVIEW_HEIGHT));

        var $metainformacio = array(    'nom'                   => '',
                                        'descripcio'            => '',
                                        'public'                => '',
                                        'codi_contingut'        => '',
                                        'categories'                => '');

        var $nom_fitxer = null;
        var $tamany_fitxer = null;
        var $codi_usuari = null;                                                        //Codi nic d'usuari de sessi�        var $codi_contingut = null;                                                     //Codi de contingut del wallpaper que generar�        var $ip = null;                                                                 //IP de l'usuari
        var $proxim_pas = null;
        var $tamanys_imatge = null;
        var $width = 0;
        var $height = 0;

        function usuari ()
        {
                $this->proxim_pas = PUIJAR_ORIGINAL;
                $this->nom_fitxer = '';
                $this->codi_contingut = '';

                $this->tamany_fitxer = 0;
                $tamanys_imatge = array(0, 0);
                $this->codi_usuari = $this->generar_codi_usuari();
                $this->ip = (getenv(HTTP_X_FORWARDED_FOR)) ?  getenv(HTTP_X_FORWARDED_FOR) :  getenv(REMOTE_ADDR);
        }

        function generar_codi_usuari()
        {
                srand(floor(time()/86400));
                return md5(mt_rand(time(), crc32($this->ip))); //Genera un identificador d'usuari nic fent un MD5 del random de l'hora actual i la IP de l'usuari
        }

        function generar_codi_contingut_disponible()
        {
                $longitud = MIN_LONG_CODI_CONTINGUT;
                $vocals = array('a','e','i','o','u');
                $consonants = array('b','c','d','f','g','h','i','j','k','l','m','n','p','q','r','s','t','v','w','x','y','z');
                while(true)
                {
                        $intents = 20;          //20 intents per generar un codi de contingut disponible de logitud prefixada
                        while($intents > 0)
                        {
                                $codi_contingut = '';
                                while(strlen($codi_contingut) < $longitud)
                                {
                                        $agafar_vocal = ((rand()%100) <= RATIO_VOCALS_CODI_CONTINGUT);
                                        if($agafar_vocal)       $codi_contingut .= $vocals[(rand()%(count($vocals)))];
                                        else                    $codi_contingut .= $consonants[(rand()%(count($consonants)))];
                                }
                                if($this->codi_contingut_esta_lliure($codi_contingut)) return $codi_contingut;
                                else $intents--;
                        }
                        if($longitud < MAX_LONG_CODI_CONTINGUT) $longitud++;
                        else return (rand()%9999999);    //Es retorna un nmero aleatori quan no queden codis de contingut textuals disponibles...
                }
        }

        function codi_contingut_esta_lliure($codi_contingut)
        {
                $con_bd = new conexio_bd();
                $sql = "        select codi_contingut from mm where codi_contingut = '$codi_contingut'";
                $res = $con_bd->sql_query($sql);
                return ($res->numRows()==0);
        }

        function guardar_wallpaper_a_bd($id_mm, $format)
        {
                $con_bd = new conexio_bd();
                $contingut = addslashes($this->wallpaper[$format]);
                $tamany = strlen($this->wallpaper[$format]);
                $sql = "        insert into contingut_original(contingut, tamany, id_mime_type, data_insert)
                                values ( '$contingut', $tamany, 52, NOW())";
                $res = $con_bd->sql_query($sql); //Guarda la informaci�del contingut a la BD
                $res = $con_bd->sql_query("select max(id_contingut_original) from contingut_original");
                $row = $res->fetchRow();
                $id_contingut_original = $row[0];
                $width = $this->wallpaper_tamany[$format]['width'];
                $height = $this->wallpaper_tamany[$format]['height'];
                $sql = "        insert into mm_contingut_original(id_mm, id_contingut_original, tipus_contingut, width, height, format, data_insert)
                                values ( $id_mm, $id_contingut_original, 'JPG', $width, $height, '".(strtoupper($format))."', NOW() )";
                $res = $con_bd->sql_query($sql); //Guarda la informaci�de la relaci�entre l'mm i el contingut
        }

        function guardar_contingut_a_bd()
        {
		$id_user = $_SESSION["sessio_usuari"]->id_usuari; //Cal agafar l'id de l'usuari que est� loggejat
                $codi_contingut = $this->metainformacio['codi_contingut'];
		$categories = $this->metainformacio['categories'];
                if(($codi_contingut == '')||(strlen($codi_contingut)<MIN_LONG_CODI_CONTINGUT)||(strlen($codi_contingut)>MAX_LONG_CODI_CONTINGUT)) $this->codi_contingut = $this->generar_codi_contingut_disponible();
                else if($this->codi_contingut_esta_lliure($codi_contingut)) $this->codi_contingut = $codi_contingut;
                else
                {
                        print "El codigo '$codi_contingut' ya está cogido<br><br>\n";
                        return;
                }

                $con_bd = new conexio_bd();
                $sql="  insert into mm(codi_contingut, id_categoria_contingut, id_user, id_source, nom, descripcio, preview_jpg, estat, public, ip_autor, data_insert)
                        values
                        (
                                '".($this->codi_contingut)."',
                                1,
				$id_user,
				'MOBITURMIX',
                                '".($this->metainformacio['nom'])."',
                                '".($this->metainformacio['descripcio'])."',
                                '".(addslashes($this->wallpaper['preview']))."',
                                'DESAPROVAT',
                                '".($this->metainformacio['public'])."',
                                '".($this->ip)."',
                                NOW()
                        )";
                $res = $con_bd->sql_query($sql); //Guarda la informaci�de l'mm a la BD
                $res = $con_bd->sql_query("select max(id_mm) from mm");
                $row = $res->fetchRow();
                $id_mm = $row[0];
		foreach($categories as $id_mm_categoria => $escollida)
			if($escollida) $this->afegir_contingut_a_categoria($id_mm, $id_mm_categoria);
                $this->guardar_wallpaper_a_bd($id_mm, 'original');
                $this->guardar_wallpaper_a_bd($id_mm, 'apaisat');
                $this->guardar_wallpaper_a_bd($id_mm, 'vertical');
                $this->guardar_wallpaper_a_bd($id_mm, 'normal');
                $this->proxim_pas++;
        }

	function afegir_contingut_a_categoria($id_mm, $id_mm_categoria)
	{
                $con_bd = new conexio_bd();
                $sql="  insert into mm_categoria_mm(id_mm_categoria, id_mm, aprovat, data_insert)
                        values
                        (
                                $id_mm_categoria,
                                $id_mm,
                                'Y',
                                NOW()
                        )";
                $res = $con_bd->sql_query($sql); //Guarda la informaci�de l'mm a la BD
                $row = $res->fetchRow();
	}

        function generar_preview_contingut()
        {
                $ci =& new CropInterface(true);
                $width = $this->wallpaper_tamany['normal']['width'];
                $height = $this->wallpaper_tamany['normal']['height'];
                $ci->resizeImage($this->wallpaper['normal'], $width, $height, PREVIEW_WIDTH, PREVIEW_HEIGHT);
                $this->wallpaper['preview'] = $ci->loadStringFromImage($this->codi_usuari);
        }

        function carregar_imatge_desde_fitxer($filename, $tipus)
        {
                $ext  = strtolower($this->_getExtension($filename));
                $func = 'imagecreatefrom' . ($ext == 'jpg' ? 'jpeg' : $ext);
                if (!$this->_isSupported($filename, $ext, $func, false)) return false;
                $this->wallpaper[$tipus] = $func($filename);
                if ($this->wallpaper[$tipus] == null)
                {
                        print "The image could not be created from the '$filename' file using the '$func' function.";
                        return false;
                }
                return true;
        }

        function carregar_imatge_desde_memoria($string, $tipus)
        {
                $this->wallpaper[$tipus] = $contingut;
                return true;
        }

        function ajustar_tamany_imatge_original()
        {
                $ci =& new CropInterface(true);
                $ci->loadImageFromString($this->wallpaper['original']);
		$string = $this->wallpaper['original'];
                $ci->width = $this->width;
                $ci->height = $this->height;
                $hi_ha_ajustament = $ci->adjustDimensionsToRange($string, MIN_IMAGE_SIZE, MAX_IMAGE_SIZE); //Els costats tindran un m�im de 200px i un m�im de 600px
                if($hi_ha_ajustament)
                {
                        $this->wallpaper['original'] = $ci->loadStringFromImage($this->codi_usuari);
                        $this->tamanys_imatge = $ci->image_sizes;
                        $this->width = $ci->width;
                        $this->height = $ci->height;
                        $this->tamany_fitxer = strlen($this->wallpaper['original']);
                }
        }

        function puijar_imatge_original()
        {
                $uploader = new file_uploader('es');
                $uploader->set_upload_dir(UPLOAD_DIR);
                $uploader->set_allowed_file_extensions(array(".jpg", ".jpeg", ".gif", ".png", ".bmp"));
                $uploader->delete_files_after_on_upload_function_call(true);
                $uploader->set_on_upload_function('desar_imatge_en_sessio', 'original');
                $uploader->upload_files();
        }

        function retallar_imatge_original($pas, $sx, $sy, $ex, $ey)
        {
                if(($sx!=null)&&($sy!=null)&&($ex!=null)&&($ey!=null))
                {
                        $ci =& new CropInterface(true);
                        $ci->loadImageFromString($this->wallpaper['original']);
                        $ci->cropToDimensions($sx,$sy,$ex,$ey);
                        $retall = $ci->loadStringFromImage($this->codi_usuari);
                        $width = $ex - $sx;
                        $height = $ey - $sy;

                        switch($pas)
                        {
                                case RETALLAR_NORMAL :          $this->wallpaper['normal'] = $retall;
                                                                $this->wallpaper_tamany['normal'] = array('width' => $width, 'height' => $height);
                                                                break;
                                case RETALLAR_VERTICAL :        $this->wallpaper['vertical'] = $retall;
                                                                $this->wallpaper_tamany['vertical'] = array('width' => $width, 'height' => $height);
                                                                break;
                                case RETALLAR_APAISAT :         $this->wallpaper['apaisat'] = $retall;
                                                                $this->wallpaper_tamany['apaisat'] = array('width' => $width, 'height' => $height);
                                                                break;
                                default :                       return;
                        }
                        $this->proxim_pas++;
                }
        }

        function processar_pas_actual($sx=null, $sy=null, $ex=null, $ey=null, $confirm='false', $name='', $description='', $public='Y', $code='', $categories)
        {
                switch($this->proxim_pas)
                {
                        case PUIJAR_ORIGINAL            :       $this->puijar_imatge_original(); break;
                        case RETALLAR_NORMAL            :       $this->retallar_imatge_original(RETALLAR_NORMAL, $sx, $sy, $ex, $ey); break;
                        case RETALLAR_VERTICAL          :       $this->retallar_imatge_original(RETALLAR_VERTICAL, $sx, $sy, $ex, $ey); break;
                        case RETALLAR_APAISAT           :       $this->retallar_imatge_original(RETALLAR_APAISAT, $sx, $sy, $ex, $ey); break;
                        case VALIDAR_PREVIEW            :       if($confirm=='true') $this->proxim_pas++; break;
                        case ENTRAR_METAINFORMACIO      :       if($confirm==TRUE)
                                                                {
                                                                        if($public!='Y') $public = 'N';
                                                                        $this->metainformacio = array(  'nom'                   => $name,
                                                                                                        'descripcio'            => $description,
                                                                                                        'public'                => $public,
                                                                                                        'codi_contingut'        => $code,
                                                                                                        'categories'		=> $categories);
                                                                        $this->generar_preview_contingut();
                                                                        $this->guardar_contingut_a_bd();
                                                                }
                                                                break;
                        case MOSTRAR_REPORT             :       $this->proxim_pas = PUIJAR_ORIGINAL; break;
                        default                         :       $this->proxim_pas = PUIJAR_ORIGINAL; break;
                }
                return $this->obtenir_seguent_pas();
        }

        function obtenir_seguent_pas()
        {
/*                print "Obtenint segent pas...<br>";
                $i=0;
                print_r($this->passos_completats);
                foreach($this->passos_completats as $pas)
                {
                        print ".";
                        if(!$pas) return $i;
                        else $i++;
                }
                print "Acabant!...<br>";
                return PUIJAR_ORIGINAL;*/
                return $this->proxim_pas;
        }
}



if(!isset($_COOKIE['tmixsid']))
{
        session_start();
        $sid = session_id();
        setcookie("tmixsid", $sid, strtotime("+1 day"), "/", ".mobiturmix.com", 0);
}
else
{
        $sid = $_COOKIE['tmixsid'];
        session_id($sid);
        $_SESSION['session_id'] = $sid;
}



//inici de la sessi�session_start();
session_start();






if(!isset($_SESSION["usuari"])) $_SESSION["usuari"] = & new usuari();

?>
