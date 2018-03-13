<?
define("UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT']."/tmp/");

define("PUIJAR_VIDEO",       0);
define("VALIDAR_PREVIEW",         1);
define("ENTRAR_METAINFORMACIO",   2);
define("MOSTRAR_REPORT",	  3);

define("PREVIEW_WIDTH",  85);
define("PREVIEW_HEIGHT", 85);

define("TRUE", 'true');
define("FALSE", 'false');

define("RATIO_VOCALS_CODI_CONTINGUT", 65); //65% prec�cia de vocals als codi de contingut aleatoris
define("MIN_LONG_CODI_CONTINGUT", 4);
define("MAX_LONG_CODI_CONTINGUT", 20);

define("MAX_OUTPUT_VIDEO_FILE_SIZE", 3000000); //Tamany m�ims dels videos generats


require_once("../DB/conexio_bd.php");

class usuari
{
	var $configuracions = array('3gpp_h263_sqcif_amr_nb.3gp'=> ' -vcodec h263 -r 15 -b 150 -s sqcif -acodec amr_nb -ar 8000 -ab 40 ',
				    '3gpp_h263_qcif_amr_nb.3gp'	=> ' -vcodec h263 -r 15 -b 150 -s qcif -acodec amr_nb -ar 8000 -ab 40 ',
				    '3gpp_h263_qcif_aac.3gp'	=> ' -vcodec h263 -r 15 -b 150 -s qcif -acodec aac -ar 8000 -ab 40 ',
				    '3gpp_h263_qcif_amr_wb.3gp'	=> ' -vcodec h263 -r 15 -b 150 -s qcif -acodec amr_wb -ar 16000 -ab 40 -ac 1 ',
				    'mp4_aac_qcif.mp4'		=> ' -vcodec h263 -r 15 -b 150 -s qcif -acodec amr_nb -ar 8000 -ab 40 ',
				    'mp4_aac_sqcif.mp4'		=> ' -vcodec h263 -r 15 -b 150 -s qcif -acodec amr_nb -ar 8000 -ab 40 ',
				    'flv_qcif.flv'		=> ' -b 250 -an -f flv -r 15 -s cif ');

        var $parametres =     array('3gpp_h263_sqcif_amr_nb.3gp'=> array('video_codec' => 'h263',
									 'video_size'  => 'sqcif',
									 'audio_codec' => 'amr_nb',
									 'id_mime_type' => 161,
									 'tipus_contingut' => '3GP'),
                                    '3gpp_h263_qcif_amr_nb.3gp' => array('video_codec' => 'h263',
                                                                         'video_size'  => 'qcif',
                                                                         'audio_codec' => 'amr_nb',
                                                                         'id_mime_type' => 161,
                                                                         'tipus_contingut' => '3GP'),
                                    '3gpp_h263_qcif_aac.3gp'    => array('video_codec' => 'h263',
                                                                         'video_size'  => 'qcif',
                                                                         'audio_codec' => 'aac',
                                                                         'id_mime_type' => 161,
                                                                         'tipus_contingut' => '3GP'),
                                    '3gpp_h263_qcif_amr_wb.3gp' => array('video_codec' => 'h263',
                                                                         'video_size'  => 'qcif',
                                                                         'audio_codec' => 'amr_wb',
                                                                         'id_mime_type' => 161,
                                                                         'tipus_contingut' => '3GP'),
                                    'mp4_aac_qcif.mp4'          => array('video_codec' => 'mp4',
                                                                         'video_size'  => 'qcif',
                                                                         'audio_codec' => 'aac',
                                                                         'id_mime_type' => 162,
                                                                         'tipus_contingut' => 'MP4'),
                                    'mp4_aac_sqcif.mp4'         => array('video_codec' => 'mp4',
                                                                         'video_size'  => 'sqcif',
                                                                         'audio_codec' => 'aac',
                                                                         'id_mime_type' => 162,
                                                                         'tipus_contingut' => 'MP4'),
				    'flv_qcif.flv'		=> array('video_codec' => 'flv',
									 'video_size'  => 'cif',
									 'audio_codec' => 'amr_nb',
									 'id_mime_type' => 163));

        var $metainformacio = array(    'nom'                   => '',
                                        'descripcio'            => '',
                                        'public'                => '',
                                        'codi_contingut'        => '');

        var $nom_fitxer = null;
        var $tamany_fitxer = null;
        var $codi_usuari = null;                                                        //Codi nic d'usuari de sessi�        var $codi_contingut = null;                                                     //Codi de contingut del wallpaper que generar�        var $ip = null;                                                                 //IP de l'usuari
        var $proxim_pas = null;
        var $tamanys_imatge = null;
        var $width = 0;
        var $height = 0;

        function usuari ()
        {
                $this->proxim_pas = PUIJAR_VIDEO;
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

        function guardar_video_a_bd($id_mm, $fitxer_video, $parametre)
	{
                $con_bd = new conexio_bd();

		$dir_tmp = "/var/www/html/tmp/".($this->codi_usuari)."/";
		$ruta_fitxer_video = $dir_tmp.$fitxer_video;

		print "GUARDANT FITXER VIDEO: ".($ruta_fitxer_video)."</br>";

                $contingut = addslashes(file_get_contents($ruta_fitxer_video));
                $tamany = filesize($ruta_fitxer_video);
		$id_mime_type = $parametre['id_mime_type'];
		$tipus_contingut = $parametre['tipus_contingut'];
		$video_codec = $parametre['video_codec'];
		$video_size = $parametre['video_size'];
		$audio_codec = $parametre['audio_codec'];

                $sql = "        insert into contingut_original(contingut, tamany, id_mime_type, data_insert)
                                values ( '$contingut', $tamany, $id_mime_type, NOW())";
                $res = $con_bd->sql_query($sql); //Guarda la informaci�del contingut a la BD
                $res = $con_bd->sql_query("select max(id_contingut_original) from contingut_original");
                $row = $res->fetchRow();
                $id_contingut_original = $row[0];

                $sql = "        insert into mm_contingut_original(id_mm, id_contingut_original, tipus_contingut, width, height, format, video_codec, video_size, audio_codec, data_insert)
                                values ( $id_mm, $id_contingut_original, '$tipus_contingut', 0, 0, 'NORMAL', '$video_codec', '$video_size', '$audio_codec', NOW())";
                $res = $con_bd->sql_query($sql); //Guarda la informaci�de la relaci�entre l'mm i el contingut

        }

        function guardar_contingut_a_bd()
        {
                $codi_contingut = $this->metainformacio['codi_contingut'];
                $categories = $this->metainformacio['categories'];
                if(($codi_contingut == '')||(strlen($codi_contingut)<MIN_LONG_CODI_CONTINGUT)||(strlen($codi_contingut)>MAX_LONG_CODI_CONTINGUT)) $this->codi_contingut = $this->generar_codi_contingut_disponible();
                else if($this->codi_contingut_esta_lliure($codi_contingut)) $this->codi_contingut = $codi_contingut;
                else
                {
                        print "El codi '$codi_contingut' ja est�agafat<br><br>\n";
                        return;
                }

		$fitxer_preview = "/var/www/html/tmp/".($this->codi_usuari)."/preview_mm.gif";
                $con_bd = new conexio_bd();
                $sql="  insert into mm(codi_contingut, id_categoria_contingut, nom, descripcio, preview_jpg, estat, public, ip_autor, data_insert)
                        values
                        (
                                '".($this->codi_contingut)."',
                                3,
                                '".($this->metainformacio['nom'])."',
                                '".($this->metainformacio['descripcio'])."',
                                '".(addslashes(file_get_contents($fitxer_preview)))."',
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
		foreach($this->parametres as $fitxer_video => $parametre)
			$this->guardar_video_a_bd($id_mm, $fitxer_video, $parametre);
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
                $res = $con_bd->sql_query($sql); //Guarda la informacide l'mm a la BD
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

        function obtenir_contingut_imatge_fitxer($file)
        {
                $handle = fopen($file, "r");
                $content = fread($handle, filesize($file));
                fclose($handle);
                //$tamanys = getimagesize($file);
                return $content;
        }

        function ajustar_fotograma($file, $es_preview=false)
        {
                $ci =& new CropInterface(true);
                $contingut_fotograma = $this->obtenir_contingut_imatge_fitxer($file);
                $ci->loadImageFromString($contingut_fotograma);
                $tamanys = getimagesize($file);
                $width = $tamanys[0];
                $height = $tamanys[1];

                $ci->width = $width;
                $ci->height = $height;
                $tamany_fitxer = filesize($file);
		if(!$es_preview) $hi_ha_ajustament = $ci->adjustDimensionsToMinimSize(MIN_IMAGE_SIZE);
		else $hi_ha_ajustament = $ci->adjustDimensionsToMinimSize(PREVIEW_HEIGHT);

                if($hi_ha_ajustament)
                {
                        $contingut_fotograma = $ci->loadStringFromImage($this->codi_usuari);
                        //$tamanys_fotograma = $ci->image_sizes;
                        $width = $ci->width;
                        $height = $ci->height;
                        $tamany_fitxer = strlen($contingut_fotograma);
                }

                return array( 'contingut' => $contingut_fotograma,
                              'width' => $width,
                              'height' => $height,
                              'tamany' => $tamany_fitxer);
        }

	function generar_preview_handset($imatge_handset, $x_ini, $y_ini, $x_fi, $y_fi, $format)
	{

		$dir_tmp = "/var/www/html/tmp/".($this->codi_usuari)."/";
		$width = $x_fi - $x_ini;
                $height = $y_fi - $y_ini;
		$cmd = "/usr/bin/gifsicle -l -d 25 -O2 --colors 256 --color-method median-cut --resize "."$width"."x"."$height $dir_tmp"."preview_tmp.gif -o $dir_tmp"."preview.gif";
		system($cmd);

		//Ara s'ha creat el preview en el tamany de la pantalla de la imatge del handset indicat
		//Ara cal superposar el preview sobre de la pantalla

                $im = imagecreatefromstring($imatge_handset);
                imagegif($im, $dir_tmp."handset.gif");

		$cmd = "/usr/bin/gifsicle -l -d 50 -O2 --colors 128 --color-method median-cut ".$dir_tmp."handset.gif -p $x_ini,$y_ini ".$dir_tmp."preview.gif -o ".$dir_tmp."final_preview.gif";
		system($cmd);
	}

	function generar_animacio_format($format)
	{
		if($format=='preview') { $colors = '64'; $durada = '150'; }
		else { $colors = '256'; $durada = '100'; }

		$cmd = "/usr/bin/gifsicle -l -d $durada -O2 --colors $colors --color-method median-cut ";
		$dir_tmp = "/var/www/html/tmp/".($this->codi_usuari)."/";
		mkdir($dir_tmp); //Cal crear el directori on es copiaran els fotogrames
		$i=0;
		foreach($this->fotogrames[$format] as $fotograma)
		{
                        $fitxer = "$dir_tmp$i.gif";
			$im = imagecreatefromstring($fotograma['contingut']);
			imagegif($im, $fitxer);
			imagedestroy($im);
			$cmd .= "$fitxer ";
			$i++;
		}
		$cmd .= " -w -o ".($dir_tmp)."$format.gif ";
		system($cmd); //Executa la comanda i crea el gif animat :)
	}

        function generar_animacions_diferents_formats()
        {
		$formats = array('normal','vertical','apaisat','preview');
		foreach($formats as $format)
		{
			if($format!='preview') $origen = $this->fotogrames['original'];
			else $origen = $this->fotogrames['preview_tmp'];

	                foreach($origen as $fotograma_original)
        	        {
                	        $ci =& new CropInterface(true);
                        	$width_original = $fotograma_original['width'];
	                        $height_original = $fotograma_original['height'];
        	                $ci->loadImageFromString($fotograma_original['contingut']); //Agafa el contingut del fotograma original
				switch($format)
				{
					case 'normal':		$width = $height = min($width_original, $height_original);
								break;
					case 'vertical':	$height = MIN_IMAGE_SIZE;
								$width = (75*MIN_IMAGE_SIZE)/100;
								break;
                                        case 'apaisat':         $width = MIN_IMAGE_SIZE;
                                                                $height = (75*MIN_IMAGE_SIZE)/100;
                                                                break;
					case 'preview':		$width = PREVIEW_WIDTH;
								$height = PREVIEW_HEIGHT;
								break;
				}

                	        $ci->cropToSize($width, $height); //Fa el tall centrat
                        	$contingut_fotograma = $ci->loadStringFromImage($this->codi_usuari); //S'acaba el tall
	                        //$tamanys_fotograma = $ci->image_sizes;
        	                $tamany_fitxer = strlen($contingut_fotograma);
                	        $fotograma = array( 'contingut' => $contingut_fotograma,
                        	                    'width' => $width,
                                	            'height' => $height,
                                        	    'tamany' => $tamany_fitxer );
				
	                        array_push($this->fotogrames[$format], $fotograma);
        	        }
			$this->generar_animacio_format($format);
			$animacio = $this->obtenir_contingut_imatge_fitxer("/var/www/html/tmp/".($this->codi_usuari)."/$format.gif");
			$this->animacions[$format] = $animacio; //Es guarda l'animacio en memoria
		}
		//$this->alliberar_memoria_fotogrames(); //S'allibera la memoria ocupada pels fotogrames
		system("rm /var/www/html/tmp/".($this->codi_usuari)."/*.gif --force"); //S'eliminen els fitxers generats
        }

	function eliminar_fitxers_generats()
	{
		$dir_tmp = "/var/www/html/tmp/".($this->codi_usuari)."/";
		system($dir_tmp."rm * -fR"); //Elimina els fitxers generats durant la conversi�		system("rmdir $dir_tmp"); //S'elimina el directori temporal
	}

        function generar_preview_video($fitxers)
        {
		if(!count($fitxers)) return;
                $fitxer = $fitxers['file'];
                if(!$fitxer['error'])
                {
                     $dir_tmp = "/var/www/html/tmp/".($this->codi_usuari)."/";
		     mkdir($dir_tmp, 0777); //Cal crear el directori on es copiaran els fotogrames
		     $fitxer_tmp = $fitxer['tmp_name'];

			$tamany = (filesize($fitxer_tmp))/100000;
			$fps = round((-0.6 * $tamany) + 8); //25 fps com a molt
			if($fps<1) $fps = 1;
			$tempo = round(($fps*5)/25);
			$width = 150;
		     $height = 150;

		     //Es creen els frames a partir del qual es crear�el gif per als preview del m�il
		     $cmd = "/usr/local/bin/ffmpeg -i \"$fitxer_tmp\" -s qcif -r $fps -f image2 -an -y \"$dir_tmp"."frame%02d\"; ";
		     $cmd .= "for i in '$dir_tmp"."frame'*; do /usr/bin/convert \$i \"\$i.gif\"; rm -f \$i; done; ";

		     //Ara cal crear el gif a partir del qual es crearan els previews per a movils que esculli l'usuari via web
		     $cmd .= "/usr/bin/gifsicle -l -d $tempo -O2 --colors 256 --color-method median-cut --resize "."$width"."x"."$height $dir_tmp"."*.gif -o $dir_tmp"."preview_tmp.gif; ";

		     $cmd .= "/usr/bin/gifsicle -l -d 35 -O2 --colors 16 --color-method median-cut --resize ".PREVIEW_WIDTH."x".PREVIEW_HEIGHT." $dir_tmp"."frame*.gif -o $dir_tmp"."preview_mm.gif; rm -f $dir_tmp"."frame*.gif; ";
		     system($cmd);

		     //Ara cal crear les corresponents conversions del video per als handsets
		     foreach($this->configuracions as $fitxer_out => $configuracio)
		     {
			if($fitxer_out=="flv_qcif.flv")
			{
				$cmd = "/usr/local/bin/ffmpeg -y -i \"$fitxer_tmp\" $configuracio \"$dir_tmp"."$fitxer_out\"";
				system($cmd);
				print "Creant FLV: $cmd<br>";
//				$cmd = "/usr/local/bin/ffmpeg -y -pass 2 -passlogfile borram.txt -i \"$fitxer_tmp\" $configuracio \"$dir_tmp"."$fitxer_out\"";
//                                system($cmd);
//				print "CMD: $cmd<br>";
			}
			else
			{
				$cmd = "/usr/local/bin/ffmpeg -y -i \"$fitxer_tmp\" $configuracio ";
				$cmd .= "\"$dir_tmp"."$fitxer_out\";";
				system($cmd);
			}
		     }

		     $this->proxim_pas++;
		}
        }

        function processar_pas_actual($fitxers, $confirm='false', $name='', $description='', $public='Y', $code='', $categories)
        {
                switch($this->proxim_pas)
                {
                        case PUIJAR_VIDEO               :       $this->generar_preview_video($fitxers); break;
                        case VALIDAR_PREVIEW            :       if($confirm==TRUE) $this->proxim_pas++; break;
                        case ENTRAR_METAINFORMACIO      :       if($confirm==TRUE)
                                                                {
                                                                        if($public!='Y') $public = 'N';
                                                                        $this->metainformacio = array(  'nom'                   => $name,
                                                                                                        'descripcio'            => $description,
                                                                                                        'public'                => $public,
                                                                                                        'codi_contingut'        => $code,											     'categories'	     => $categories);
                                                                        $this->guardar_contingut_a_bd();
									$this->eliminar_fitxers_generats();
									$this->proxim_pas++;
                                                                }
                                                                break;
                        case MOSTRAR_REPORT             :       $this->proxim_pas = PUIJAR_VIDEO; break;
                        default                         :       $this->proxim_pas = PUIJAR_VIDEO; break;
                }
                return $this->obtenir_seguent_pas();
        }

        function obtenir_seguent_pas()
        {
                return $this->proxim_pas;
        }
}

//inici de la sessi�session_start();

if(!isset($_SESSION["usuari_video"]))
        $_SESSION["usuari_video"] = & new usuari();

?>
