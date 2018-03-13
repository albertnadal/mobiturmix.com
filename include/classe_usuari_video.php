<?
define("DEBUG", false);

define("UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT']."/tmp/");

define("PUIJAR_VIDEO",       0);
define("VALIDAR_PREVIEW",         1);
define("ENTRAR_METAINFORMACIO",   2);
define("MOSTRAR_REPORT",	  3);

define("PREVIEW_WIDTH",  85);
define("PREVIEW_HEIGHT", 85);

define("TRUE", 'true');
define("FALSE", 'false');

define("RATIO_VOCALS_CODI_CONTINGUT", 65); //65% precÔøΩcia de vocals als codi de contingut aleatoris
define("MIN_LONG_CODI_CONTINGUT", 4);
define("MAX_LONG_CODI_CONTINGUT", 20);

define("MAX_OUTPUT_VIDEO_FILE_SIZE", 3000000); //Tamany mÔøΩims dels videos generats


require_once("conexio_bd.php");
require_once("classe_video_convert.php");
require_once("classe_sessio_usuari.php");

class usuari
{
	var $configuracions = array('3gpp_h263_sqcif_amr_nb.3gp'=> ' -vcodec h263 -b %d -r %d -s sqcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
				    '3gpp_h263_qcif_amr_nb.3gp'	=> ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
				    '3gpp_h263_qcif_aac.3gp'	=> ' -vcodec h263 -b %d -r %d -s qcif -acodec aac -ar 8000 -ab %d -ac 1 ',
				    '3gpp_h263_qcif_amr_wb.3gp'	=> ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_wb -ar 16000 -ab %d -ac 1 ',
				    'mp4_aac_qcif.mp4'		=> ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
				    'mp4_aac_sqcif.mp4'		=> ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
				    'flv_mp3_cif.flv'		=> ' -b %d -f flv -r %d -s cif -acodec mp3 -ar 11025 -ab 24 -ac 1 ');

	var $tamanys = array(102400, 122880, 153600, 248000, 1024000); //Tamanys amb que es codificaran els vÌdeos

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
				    'flv_mp3_cif.flv'		=> array('video_codec' => 'flv',
									 'video_size'  => 'cif',
									 'audio_codec' => 'mp3',
									 'id_mime_type' => 163,
									 'tipus_contingut' => 'FLV'));

        var $metainformacio = array(    'nom'                   => '',
					'id_user'		=> 0,
					'id_source'		=> 'MOBITURMIX',
                                        'descripcio'            => '',
                                        'durada'		=> 0,
                                        'public'                => 'Y',
                                        'codi_contingut'        => '',
					'email'			=> '',
					'categories'		=> array(
									CATEGORIA_AMOR		=> false,
									CATEGORIA_DEPORTE	=> false,
									CATEGORIA_MUSICA	=> false,
									CATEGORIA_AMIGOS	=> false,
									CATEGORIA_FAMILIA	=> false,
									CATEGORIA_PAISAJES	=> false,
									CATEGORIA_VIAJES	=> false,
									CATEGORIA_DIVERTIDA	=> false,
									CATEGORIA_EROTICA	=> false
									));


        var $nom_fitxer = null;
        var $tamany_fitxer = null;
        var $codi_usuari = null;                                                        //Codi nic d'usuari de sessiÔøΩ        var $codi_contingut = null;                                                     //Codi de contingut del wallpaper que generarÔøΩ        var $ip = null;                                                                 //IP de l'usuari
        var $proxim_pas = null;
        var $tamanys_imatge = null;
        var $width = 0;
        var $height = 0;
	var $durada;
	var $video_esta_processat = true;

        function usuari ()
        {
		$this->video_esta_processat = true;
                $this->proxim_pas = PUIJAR_VIDEO;
                $this->nom_fitxer = '';
                $this->codi_contingut = '';

		$this->durada = 0;
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

        function guardar_contingut_a_bd()
        {
		$id_user = $_SESSION["sessio_usuari"]->id_usuari; //Cal agafar l'id de l'usuari que est‡ loggejat
                $codi_contingut = $this->metainformacio['codi_contingut'];
                $categories = $this->metainformacio['categories'];
                if(($codi_contingut == '')||(strlen($codi_contingut)<MIN_LONG_CODI_CONTINGUT)||(strlen($codi_contingut)>MAX_LONG_CODI_CONTINGUT)) $this->codi_contingut = $this->generar_codi_contingut_disponible();
                else if($this->codi_contingut_esta_lliure($codi_contingut)) $this->codi_contingut = $codi_contingut;
                else die("El c√≥digo '$codi_contingut' ya est√° cogido.");

		$fitxer_preview = "/var/www/html/tmp/".($this->codi_usuari)."/preview_mm.gif";
                $con_bd = new conexio_bd();
                $sql="  insert into mm(codi_contingut, id_categoria_contingut, id_user, id_source, nom, descripcio, durada, preview_jpg, estat, public, ip_autor, data_insert)
                        values
                        (
                                '".($this->codi_contingut)."',
                                3,
				$id_user,
				'MOBITURMIX',
                                '".($this->metainformacio['nom'])."',
                                '".($this->metainformacio['descripcio'])."',
                                '".($this->metainformacio['durada'])."',
                                '".(addslashes(file_get_contents($fitxer_preview)))."',
                                'DESAPROVAT',
                                '".($this->metainformacio['public'])."',
                                '".($this->ip)."',
                                NOW()
                        )";
                $res = $con_bd->sql_query($sql); //Guarda la informaciÔøΩde l'mm a la BD
                $res = $con_bd->sql_query("select max(id_mm) from mm");
                $row = $res->fetchRow();
                $id_mm = $row[0];
                foreach($categories as $id_mm_categoria => $escollida)
                        if($escollida) $this->afegir_contingut_a_categoria($id_mm, $id_mm_categoria);

                $queue = new video_convert();
                $queue->guardar_videos_generats_a_bd($id_mm, $this->codi_usuari);
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
                //$row = $res->fetchRow();
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
		system($dir_tmp."rm * -fR"); //Elimina els fitxers generats durant la conversiÔøΩ		system("rmdir $dir_tmp"); //S'elimina el directori temporal
	}

	function get_file_size($fitxer)
	{
		$sortida = shell_exec("/usr/bin/du -b \"$fitxer\"");
		$sortida = explode(" ", $sortida);
		return $sortida[0] + 0;
	}

	function codificar_video($input, $dir_tmp, $file_output, $configuracio)
	{
		if(strpos($file_output, ".flv")) $es_flv = true; else $es_flv = false;

		foreach($this->tamanys as $tamany)
		{
	                $max_video_bitrate = MAX_VIDEO_BITRATE;
	                $min_video_bitrate = MIN_VIDEO_BITRATE;
        	        $max_video_fps = MAX_VIDEO_FPS;
	                $min_video_fps = MIN_VIDEO_FPS;
			$max_audio_bitrate = MAX_AUDIO_BITRATE;
			$min_audio_bitrate = MIN_AUDIO_BITRATE;
			$max_video_length = MAX_VIDEO_LENGTH;
			$min_video_length = MIN_VIDEO_LENGTH;

			$reduir_video_bitrate = true;
			$reduir_video_fps = false;
			$reduir_audio_bitrate = false;
			$reduir_video_length = false;

			$fps = $max_video_fps;
			$abrate = $max_audio_bitrate;
			$length = $max_video_length;
			$queden_intents = 2;

			$tamany_assolit = false;
			while(!$tamany_assolit)
			{
				if($reduir_video_bitrate) $bitrate = floor(($max_video_bitrate + $min_video_bitrate) / 2);
				else if($reduir_video_fps) $fps = floor(($max_video_fps + $min_video_fps) / 2);
				else if($reduir_audio_bitrate) $abrate=floor(($max_audio_bitrate + $min_audio_bitrate)/2);
				else if($reduir_video_length) $length =floor(($max_video_length + $min_video_length) / 2);

				$set_conf = sprintf($configuracio, $bitrate, $fps, $abrate);
				if($reduir_video_length) $set_conf .= " -t $length ";

				if($es_flv) $tamany = 1024000; //Pels FLV nom√©s es vol el amany maxim d'1MB

				$output = $dir_tmp.$tamany.$file_output;

				$cmd = "/usr/local/bin/ffmpeg -y -i $input $set_conf $output";
				system($cmd);
//				print "$cmd<br>";

				$tamany_fitxer_generat = $this->get_file_size("$output");

//        	                print "IMPOSO $tamany i obtinc $tamany_fitxer_generat MIN: $min_video_bitrate MAX: $max_video_bitrate MINFPS: $min_video_fps MAXFPS: $max_video_fps MAXABRATE: $max_audio_bitrate MINABRATE: $min_audio_bitrate<br>$cmd\n";

				if($tamany_fitxer_generat <= 0)
				{
					//No s'ha pogut codificar el v√≠deo, es possible que l'ffmpeg no reconegui el c√≤dec d'audio
					$set_conf = substr($set_conf, 0, strpos($set_conf, "-acodec"))." -an ";
					$cmd = "/usr/local/bin/ffmpeg -y -i \"$input\" $set_conf \"$output\";";
					system($cmd);

					$tamany_fitxer_generat = $this->get_file_size("$output");

					if((!file_exists("$output")) || ($tamany_fitxer_generat<=0)) return false;
				}

				$diferencia_tamany = $tamany_fitxer_generat - $tamany;

				//Primer de tot es redueix el video bitrate
				if($reduir_video_bitrate)
				{
					if($diferencia_tamany > 0) $max_video_bitrate = $bitrate;
					else if(($diferencia_tamany < -TOLERABLE_VIDEO_SIZE_OFFSET)
						&& $queden_intents)
					{
						$min_video_bitrate = $bitrate;
						$queden_intents--;
					}
					else $tamany_assolit = true;
					if(($max_video_bitrate - $min_video_bitrate) <= 10)
					{
						$reduir_video_bitrate = false;
						$reduir_video_fps = true;
					}
				}

				//Si reduir el videobitrate no es suficient aleshores es redueix els fps
				if($reduir_video_fps)
				{
	                                if($diferencia_tamany > 0) $max_video_fps = $fps;
        	                        else if(($diferencia_tamany < -TOLERABLE_VIDEO_SIZE_OFFSET)
						&& $queden_intents)
					{
						$min_video_fps = $fps;
						$queden_intents--;
					}
                	                else $tamany_assolit = true;
					if(($max_video_fps - $min_video_fps) < 3)
					{
						$reduir_video_fps = false;
						if($es_flv) $reduir_video_length = true;
						else $reduir_audio_bitrate = true;
					}
				}

                                //Si reduir els fps no es suficient aleshores es redueix l'audio bitrate
                                if($reduir_audio_bitrate)
                                {
                                        if($diferencia_tamany > 0) $max_audio_bitrate = $abrate;
                                        else if(($diferencia_tamany < -TOLERABLE_VIDEO_SIZE_OFFSET)
                                                && $queden_intents)
                                        {
                                                $min_audio_bitrate = $abrate;
                                                $queden_intents--;
                                        }
                                        else $tamany_assolit = true;
                                        if(($max_audio_bitrate - $min_audio_bitrate) < 3)
                                        {
                                                $reduir_audio_bitrate = false;
                                                $reduir_video_length = true;
                                        }
                                }

                                //Si reduir l'audio rate no es suficient aleshores es redueix la durada del video...
                                if($reduir_video_length)
                                {
                                        if($diferencia_tamany > 0) $max_video_length = $length;
                                        else if(($diferencia_tamany < -TOLERABLE_VIDEO_SIZE_OFFSET)
                                                && $queden_intents)
                                        {
                                                $min_video_length = $length;
                                                $queden_intents--;
                                        }
                                        else $tamany_assolit = true;
                                }

/*		                $handle = fopen("/var/www/html/tmp/resultats.txt", "w");
        		        fwrite($handle, "MIN: $min_video_bitrate  MAX: $max_video_bitrate T:$tamany_fitxer_generat T2:$tamany BITRATE: $bitrate DIF: $diferencia_tamany MINFPS: $min_video_fps MAXFPS: $max_video_fps MINABRATE: $min_audio_bitrate MAXABRATE: $max_audio_bitrate\n$cmd");
	        	        fclose($handle);*/


				if($es_flv && $tamany_assolit) return true;
//				if($tamany_assolit) return true; //S'ha assolit el tamany m‡xim del vÌdeo
			}
		}
		return true;
	}

	function obtenir_durada_video($fitxer_tmp, $dir_tmp)
	{
		system("/usr/local/bin/ffmpeg -i $fitxer_tmp &> $dir_tmp"."info.txt");
		system("grep \"Duration:\" $dir_tmp"."info.txt > $dir_tmp"."time.txt");
		$cadena_durada = file_get_contents("$dir_tmp"."time.txt");
		preg_match("/([0-9][0-9]):([0-9][0-9]):([0-9][0-9])/", $cadena_durada, $matches, PREG_OFFSET_CAPTURE);
		return ($matches[1][0] * 60*60) + ($matches[2][0] * 60) + $matches[3][0];
	}

        function generar_preview_video($fitxers)
        {
		$conversio_fallida = false;

		if(!count($fitxers)) return;
                $fitxer = $fitxers['file'];
                if(!$fitxer['error'])
                {
		     $dir_tmp = "/var/www/html/tmp/".($this->codi_usuari)."/";
		     mkdir($dir_tmp, 0777); //Cal crear el directori on es copiaran els fotogrames
		     system("mv ".($fitxer['tmp_name'])." $dir_tmp"."video");
		     $fitxer_tmp = $dir_tmp."video";

		     $tamany = (filesize($fitxer_tmp))/100000;
		     $fps = round((-0.6 * $tamany) + 8); //25 fps com a molt
		     if($fps<1) $fps = 1;
		     $tempo = round(($fps*5)/25);
		     $width = 150;
		     $height = 150;

		     //Es creen els frames a partir del qual es crear√† el gif per als preview del mÔøΩil

		     $cmd = "/usr/local/bin/ffmpeg -i \"$fitxer_tmp\" -s qcif -r $fps -f image2 -an -y \"$dir_tmp"."frame%02d\"; ";
		     system($cmd);

		     system("ls $dir_tmp"."frame* | wc -l &> $dir_tmp"."count.txt");
		     $num_fitxers = file_get_contents("$dir_tmp"."count.txt");

		     //Ara s'eliminen els frames parells per redu√Ør el pes del preview final...
		     for($i=0; $i<$num_fitxers; $i++)
		     {
			if(($i%($num_fitxers/MAX_FRAMES_PREVIEW_VIDEO) > 0)||($i>$num_fitxers))
			{
			        if($i<10) $num="0$i"; else $num = "$i";
				system("rm -f $dir_tmp"."frame$num");
			}
		     }

		     $cmd = "for i in '$dir_tmp"."frame'*; do /usr/bin/convert \$i \"\$i.gif\"; rm -f \$i; done; ";
		     system($cmd);


		     //Ara cal crear el gif a partir del qual es crearan els previews per a movils que esculli l'usuari via web
		     $cmd = "/usr/bin/gifsicle -l -d $tempo -O2 --colors 256 --color-method median-cut --resize "."$width"."x"."$height $dir_tmp"."*.gif -o $dir_tmp"."preview_tmp.gif; ";
		     system($cmd);

		     $cmd = "/usr/bin/gifsicle -l -d 80 -O2 --colors 32 --color-method median-cut --resize ".PREVIEW_WIDTH."x".PREVIEW_HEIGHT." $dir_tmp"."frame*.gif -o $dir_tmp"."preview_mm.gif; rm -f $dir_tmp"."frame*.gif; ";
		     system($cmd);

		     if(!file_exists($dir_tmp."preview_mm.gif"))
		     {
			$this->eliminar_fitxers_generats();
			return;
		     }

		     $this->durada = $this->obtenir_durada_video($fitxer_tmp, $dir_tmp);

		     //Es crea una inst‡ncia del mÚdul convertidor de vÌdeos...
                     $queue = new video_convert();

		     //No s'accepten vÌdeos mÈs llargs de MAX_VIDEO_LENGTH, per tant es retallen...
		     if($this->durada > MAX_VIDEO_LENGTH)
		     {
			$queue->retallar_durada_video($fitxer_tmp, MAX_VIDEO_LENGTH);
			$this->durada = MAX_VIDEO_LENGTH;
		     }

		     //Ara es posa el v√≠deo a la cua de pendndents
		     $id_user = $_SESSION["sessio_usuari"]->id_usuari; //Cal agafar l'id de l'usuari que est‡ loggejat
		     $queue->encuar_video_per_codificar($dir_tmp,$fitxer_tmp,$this->codi_usuari,$this->durada,$this->ip,5,$id_user, 'MOBITURMIX');

/*
		     //Ara cal crear les corresponents conversions del video per als handsets
		     foreach($this->configuracions as $fitxer_out => $configuracio)
		     {
			$ok = $this->codificar_video($fitxer_tmp, $dir_tmp, $fitxer_out, $configuracio);
			if(!$ok) { $conversio_fallida = true; break; }
		     }
*/
/*			if($conversio_fallida) print "La conversio ha fallat!<br>";
		     if(!$conversio_fallida) $this->proxim_pas++;
		     else $this->eliminar_fitxers_generats();*/

		     $this->proxim_pas++;
		}
        }

        function processar_pas_actual($fitxers, $confirm='false', $name='', $description='', $public='Y', $code='', $categories, $email='')
        {
                switch($this->proxim_pas)
                {
                        case PUIJAR_VIDEO               :       $this->video_esta_processat = true;
								$this->generar_preview_video($fitxers);
								break;
                        case VALIDAR_PREVIEW            :       if($confirm==TRUE) $this->proxim_pas++; break;
                        case ENTRAR_METAINFORMACIO      :       if($confirm==TRUE)
                                                                {
									$id_user = $_SESSION["sessio_usuari"]->id_usuari;
                                                                        if($public!='Y') $public = 'N';
                                                                        $this->metainformacio = array(
										'nom'		=> $name,
                                                                                'descripcio'	=> $description,
										'durada'	=> $this->durada,
										'public'	=> $public,
										'codi_contingut'=> $code,
										'email'		=> $email,
										'categories'	=> $categories,
										'id_user'	=> $id_user,
										'id_source'	=> 'MOBITURMIX');

									$codi_usuari = $this->codi_usuari;
									$path = "/var/www/html/tmp/$codi_usuari/";
							                $queue = new video_convert();
							                if($queue->video_esta_processat($codi_usuari))
									{
										if(DEBUG) print "Video processat<br>";
										if(DEBUG) print "Guardant video a BD<br>";
	                                                                        $this->guardar_contingut_a_bd();

										if($email!='')
										{
											$enviar = new enviador_emails();
											$enviar->enviar_email_a_usuari($email, $code, '', false, $path."final_preview.gif", VIDEO);
										}
										system("cp $path"."final_preview.gif /var/www/html/tmp/$codi_usuari.gif");
                                                                                if(DEBUG) print "Eliminant fitxers<br>";
                                                                                $queue->eliminar_fitxers_generats($path);

										$this->proxim_pas++;
										$this->video_esta_processat = true;
									}
									else if($email!='')
									{
										if(DEBUG) print "Afegint vÌdeo a cua<br>";
										$queue->insersio_automatica_a_bd($codi_usuari, $this->metainformacio);
										if(DEBUG) print "Proxim pas puijar<br>"; 
										$this->proxim_pas = PUIJAR_VIDEO;
										$this->video_esta_processat = true;
									}
									else
									{
										if(DEBUG) print "Pendent, cal esperar<br>";
										$this->video_esta_processat = false;
									}
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

//inici de la sessiÔ


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


session_start();

if(!isset($_SESSION["usuari_video"])) $_SESSION["usuari_video"] = & new usuari();

?>
