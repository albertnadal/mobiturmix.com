<?php
//require_once("class.phpmailer.php");
require_once("classe_usuari_video.php");
require_once("constants_smsturmix.php");
require_once("conexio_bd.php");
require_once("classe_enviament_emails.php");

define(DEBUG, false);
define(DEBUG_FFMPEG, false);

class video_convert
{
	var $enviador = null;

        var $configuracions = array('3gpp_h263_sqcif_amr_nb.3gp'=> ' -vcodec h263 -b %d -r %d -s sqcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
                                    '3gpp_h263_qcif_amr_nb.3gp' => ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
                                    '3gpp_h263_qcif_aac.3gp'    => ' -vcodec h263 -b %d -r %d -s qcif -acodec aac -ar 8000 -ab %d -ac 1 ',
                                    '3gpp_h263_qcif_amr_wb.3gp' => ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_wb -ar 16000 -ab %d -ac 1 ',
                                    'mp4_aac_qcif.mp4'          => ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
                                    'mp4_aac_sqcif.mp4'         => ' -vcodec h263 -b %d -r %d -s qcif -acodec amr_nb -ar 8000 -ab %d -ac 1 ',
                                    'flv_mp3_cif.flv'           => ' -b %d -f flv -r %d -s cif -acodec mp3 -ar 11025 -ab 24 -ac 1 ');

        var $tamanys = array(102400, 122880, 153600, 248000 ,1024000); //Aquests sï¿½n els tamanys amb que es codificaran els vï¿½deos

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
                                    'flv_mp3_cif.flv'           => array('video_codec' => 'flv',
                                                                         'video_size'  => 'cif',
                                                                         'audio_codec' => 'mp3',
                                                                         'id_mime_type' => 163,
                                                                         'tipus_contingut' => 'FLV'));

	function video_convert()
	{
		$this->enviador = new enviador_emails();
	}

	function insersio_automatica_a_bd($codi_usuari, $metainformacio)
	{
		//Es guarda l'adreça email de l'usuari, de manera que al finalitzar la codificació s'inserirà
		//la informació a la base de dades automaticament i se li enviarà un mail...
		$email = $metainformacio['email'];
		$nom = $metainformacio['nom'];
		$id_user = $metainformacio['id_user'];
		$id_source = $metainformacio['id_source'];
		$descripcio = $metainformacio['descripcio'];
		$public = $metainformacio['public'];
		$durada = $metainformacio['durada'];
		$categories = $metainformacio['categories'];
		$llista = "";
		foreach($categories as $id_mm_categoria => $escollida)
			if($escollida) $llista.="$id_mm_categoria,";
		$llista = trim($llista, ",");

                $con_bd = new conexio_bd();
                $sql = "update video_queue
			set email='$email', nom='$nom', id_user='$id_user', id_source='$id_source', descripcio='$descripcio', public='$public', categories='$llista'
			where id_usuari='$codi_usuari' ";
                $res = $con_bd->sql_query($sql);
	}

	function retallar_durada_video($file, $length=MAX_VIDEO_LENGTH)
	{
		return true;
		//Video bitrate 1152kpbs (VCD)
		//Video frame rate 25fps (estandar)

		$config = " -vcodec mpeg1video -b 1152 -r 25 -s cif -acodec mp3 -ar 22050 -ab 128 -ac 1 -t $length ";
		$cmd = "/usr/local/bin/ffmpeg -y -i $file $config $file".".mpg &> /dev/null";
		system($cmd);

		if(DEBUG_FFMPEG) print "$cmd\n\n";

		if((!file_exists($file.".mpg"))||(!filesize($file.".mpg"))) return false;
		system("rm $file"); //Elimina el fitxer original
		system("mv $file".".mpg $file"); //Mou el fitxer generat al fitxer original
		return true;
	}

	function posar_videos_en_proces_com_a_pendents()
	{
                $con_bd = new conexio_bd();
                $sql = "update video_queue set estat='PENDENT' where estat='CONVERTINT' ";
                $res = $con_bd->sql_query($sql);
	}

	function guardar_videos_generats_a_bd($id_mm, $codi_usuari)
	{
                foreach($this->parametres as $fitxer_video => $parametre)
                        $this->guardar_video_a_bd($id_mm, $fitxer_video, $parametre, $codi_usuari);
	}

        function guardar_video_a_bd($id_mm, $fitxer_video, $parametre, $codi_usuari)
        {
                if(strpos($fitxer_video, ".flv")) $es_flv = true; else $es_flv = false;

                foreach($this->tamanys as $tamany_codificacio)
                {
                        if($es_flv) $tamany_codificacio = 1024000;
                        else $tamany_codificacio = $tamany_codificacio;

                        $con_bd = new conexio_bd();

                        $dir_tmp = "/var/www/html/tmp/".($codi_usuari)."/";
                        $ruta_fitxer_video = $dir_tmp.$tamany_codificacio.$fitxer_video;
                        if(!file_exists($ruta_fitxer_video)) return;

                        $contingut = addslashes(file_get_contents($ruta_fitxer_video));
                        $tamany = filesize($ruta_fitxer_video);
                        $id_mime_type = $parametre['id_mime_type'];
                        $tipus_contingut = $parametre['tipus_contingut'];
                        $video_codec = $parametre['video_codec'];
                        $video_size = $parametre['video_size'];
                        $audio_codec = $parametre['audio_codec'];

//                      print "TAMANY: $tamany MIME_TYPE: $id_mime_type<br>";

                        $sql = "        insert into contingut_original(contingut, tamany, id_mime_type, data_insert)
                                        values ( '$contingut', $tamany, $id_mime_type, NOW())";

                        $res = $con_bd->sql_query($sql); //Guarda la informaciï¿½del contingut a la BD
                        $res = $con_bd->sql_query("select max(id_contingut_original) from contingut_original");
                        $row = $res->fetchRow();
                        $id_contingut_original = $row[0];

                        $sql = "        insert into mm_contingut_original(id_mm, id_contingut_original, tipus_contingut, width, height, format, video_codec, video_size, audio_codec, tamany, data_insert)
                                values ( $id_mm, $id_contingut_original, '$tipus_contingut', 0, 0, 'NORMAL', '$video_codec', '$video_size', '$audio_codec', $tamany, NOW())";
                        $res = $con_bd->sql_query($sql); //Guarda la informaciï¿½de la relaciï¿½entre l'mm i el contingut

                        if($es_flv) return;
                }
        }

	function encuar_video_per_codificar($dir_tmp, $video_file, $codi_usuari, $durada, $ip, $prioritat=5, $id_user=0, $id_source='MOBITURMIX')
	{
		$con_bd = new conexio_bd();
		$sql = "        insert into video_queue(output_path, file, prioritat, estat, ip, durada, id_usuari, id_user, id_source, data_inici_codificacio, data_fi_codificacio, data_insert)
                                values ( '$dir_tmp', '$video_file', $prioritat, 'PENDENT', '$ip', $durada, '$codi_usuari', $id_user, '$id_source','', '', NOW())";
                $res = $con_bd->sql_query($sql); //Guarda la informaciï¿½del contingut a la BD
	}

	function hi_ha_videos_en_codificacio()
	{
                $con_bd = new conexio_bd();
                $sql = "select count(*) from video_queue where estat='CONVERTINT'";
                $res = $con_bd->sql_query($sql);
                $row = $res->fetchRow();
                return ($row[0] > 0);
	}

	function obtenir_info_video($codi_usuari)
	{
                $con_bd = new conexio_bd();
                $sql = "select  output_path,
                                file,
                                prioritat,
				estat,
                                ip,
                                durada,
                                id_usuari,
				id_user,
				id_source,
                                email,
                                nom,
                                descripcio,
                                public,
                                categories,
                                data_insert
                        from video_queue
                        where id_usuari='$codi_usuari'
                        order by data_insert asc
                        limit 1";

                $res = $con_bd->sql_query($sql);
                if($res==null) return false;
                else if(! $res->numRows()) return false;

                $row = $res->fetchRow();
                $info = array(  'path' => $row[0],
                                'file' => $row[1],
                                'prioritat' => $row[2],
				'estat' => $row[3],
                                'ip' => $row[4],
                                'durada' => $row[5],
                                'id_usuari' => $row[6],
				'id_user' => $row[7],
				'id_source' => $row[8],
                                'email' => $row[9],
                                'nom' => $row[10],
                                'descripcio' => $row[11],
                                'public' => $row[12],
                                'categories' => $row[13],
                                'data_insert' => $row[14] );
                return $info;
	}

	function obtenir_info_seguent_pendent()
	{
                $con_bd = new conexio_bd();
                $sql = "select	output_path,
				file,
				prioritat,
				ip,
				durada,
				id_usuari,
				id_user,
				id_source,
				email,
				nom,
				descripcio,
				public,
				categories,
				data_insert
			from video_queue
			where estat='PENDENT'
			order by data_insert asc
			limit 1";

                $res = $con_bd->sql_query($sql);
		if($res==null) return false;
		else if(! $res->numRows()) return false;

                $row = $res->fetchRow();
                $info = array(	'path' => $row[0],
				'file' => $row[1],
				'prioritat' => $row[2],
				'ip' => $row[3],
				'durada' => $row[4],
				'id_usuari' => $row[5],
				'id_user' => $row[6],
				'id_source' => $row[7],
				'email' => $row[8],
				'nom' => $row[9],
				'descripcio' => $row[10],
				'public' => $row[11],
				'categories' => $row[12],
				'data_insert' => $row[13] );
		return $info;
	}

	function iniciar_codificacio_reiterada()
	{
		while(true)
		{
			$this->iniciar_codificacio();
			sleep(1);
		}
	}

	function iniciar_codificacio()
	{
		$esta_codificant = $this->hi_ha_videos_en_codificacio();

		if(!$esta_codificant)
		{
			$info = $this->obtenir_info_seguent_pendent();
			if(DEBUG) print_r($info);

			while($info)
			{
				$video_file = $info['file'];
				$output_path = $info['path'];
				$id_usuari = $info['id_usuari'];

				$this->guardar_estat_conversio_video($id_usuari, 'CONVERTINT');

				$ok = false;
				foreach($this->configuracions as $fitxer_out => $configuracio)
				{
					$ok=$this->codificar_video($video_file, $output_path, $fitxer_out, $configuracio);
					if(!$ok)
					{
						if(DEBUG) print "La conversio del video ha fallat<br>";
						$this->guardar_estat_conversio_video($id_usuari, 'ERROR');
						break;
					}
				}

				//En aquest punt cal obtenir info de l'usuari, ja que pot haver donat el seu email...
				$info = $this->obtenir_info_video($id_usuari);
				$email = $info['email'];

				if($ok)
				{
					if(DEBUG) print "La conversio ha anat bé. ($email)<br>";
					if($email)
					{
						if(DEBUG) print "Hi ha email. Guardo a la BD i lenvio a $email<br>";
						$this->guardar_contingut_a_bd($info, true); //Després envia un email...
						$this->eliminar_fitxers_generats($output_path);
					}
				 	$this->guardar_estat_conversio_video($id_usuari, 'PROCESSAT');
				}

				$info = $this->obtenir_info_seguent_pendent();
			}
		}
	}

	function guardar_contingut_a_bd($info, $enviar_mail=false)
	{
		if(DEBUG) print "Guardant contingut a BD<br>";
		$durada = $info['durada'];
		$codi_usuari = $info['id_usuari'];
		$id_user = $info['id_user'];
		$id_source = $info['id_source'];
		$email = $info['email'];
		$nom = $info['nom'];
		$descripcio = $info['descripcio'];
		$public = $info['public'];
		$ip = $info['ip'];
		$data_insert = $info['data_insert'];
		$categories = array();
		$llista = explode(",", $info['categories']);
		foreach($llista as $id_mm_categoria)
			$categories[$id_mm_categoria] = true;

		$codi_contingut = $this->generar_codi_contingut_disponible();
                $fitxer_preview = "/var/www/html/tmp/".($codi_usuari)."/preview_mm.gif";
                $con_bd = new conexio_bd();
                $sql="  insert into mm(codi_contingut, id_categoria_contingut, id_user, id_source, nom, descripcio, durada, preview_jpg, estat, public, ip_autor, data_insert)
                        values
                        (
                                '$codi_contingut',
                                3,
                                $id_user,
                                '$id_source',
                                '$nom',
                                '$descripcio',
                                '$durada',
                                '".(addslashes(file_get_contents($fitxer_preview)))."',
                                'DESAPROVAT',
                                '$public',
                                '$ip',
                                NOW()
                        )";

		if(DEBUG) print "SQL: $sql<br>";
                $res = $con_bd->sql_query($sql); //Guarda la informaciï¿½de l'mm a la BD
		if(DEBUG) print "Inserint registre a MM<br>";
                $res = $con_bd->sql_query("select max(id_mm) from mm");
                $row = $res->fetchRow();
                $id_mm = $row[0];
                foreach($categories as $id_mm_categoria => $escollida)
                        if($escollida) $this->afegir_contingut_a_categoria($id_mm, $id_mm_categoria);

		if(DEBUG) print "Guardant videos generats a BD<br>";
                $this->guardar_videos_generats_a_bd($id_mm, $codi_usuari);
		if(DEBUG) print "Si ha posat email l'envio<br>";
		if($enviar_mail)
			$this->enviar_email_a_usuari($email, $codi_contingut, $data_insert, false, "/var/www/html/tmp/".($codi_usuari)."/final_preview.gif", VIDEO);
	}

	function enviar_email_a_usuari($email_to, $codi_contingut, $data_insert, $a_amic=false, $path_preview, $tipus=PHOTO)
	{
		$this->enviador->enviar_email_a_usuari($email_to, $codi_contingut, $data_insert, $a_amic, $path_preview, $tipus);
	}

	function IsEMail($e)
	{
		return $this->enviador->IsEmail($e);
	}

	function enviar_email_a_amics($email_to, $codi_contingut, $path_preview='', $tipus=PHOTO)
	{
		$this->enviador->enviar_email_a_amics($email_to, $codi_contingut, $path_preview, $tipus);
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

	function guardar_estat_conversio_video($id_usuari, $estat)
	{
		if(($estat=='ERROR')||($estat=='PROCESSAT'))
		{
			$sql = " UPDATE video_queue
				 SET estat = '$estat', data_fi_codificacio = NOW()
				 WHERE id_usuari = '$id_usuari'
				 LIMIT 1";
		}
		else if($estat=='CONVERTINT')
                {
                        $sql = " UPDATE video_queue
                                 SET estat = '$estat', data_inici_codificacio = NOW()
                                 WHERE id_usuari = '$id_usuari'
                                 LIMIT 1";
                }
		else
                {
                        $sql = " UPDATE video_queue
                                 SET	estat = 'PENDENT',
					data_inici_codificacio = '0000-00-00 00:00:00',
					data_fi_codificacio = '0000-00-00 00:00:00'
                                 WHERE id_usuari = '$id_usuari'
                                 LIMIT 1";
                }

                $con_bd = new conexio_bd();
		$res = $con_bd->sql_query($sql);
	}

	function video_esta_processat($codi_usuari)
	{
                $con_bd = new conexio_bd();
                $sql = "select estat
			from video_queue
			where id_usuari = '$codi_usuari'
			order by data_insert desc
			limit 1";

                $res = $con_bd->sql_query($sql);
                if($res==null) return false;
                $row = $res->fetchRow();
                return ($row[0] == "PROCESSAT");
	}

	function eliminar_fitxers_generats($output_path)
	{
		if(DEBUG) print "Eliminant fitxers de $output_path<br>";
                system("rm -rf $output_path"); //Elimina els fitxers generats durant la conversiÃ³
		if(DEBUG) print "Fitxers eliminats<br>";
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
                                if($reduir_video_length)
				{
					//Quan s'arriba a l'extrem de retallar el video aleshores es treu el sò...
//                                        $set_conf = substr($set_conf, 0, strpos($set_conf, "-acodec"))." -an ";
					$set_conf .= " -t $length ";
				}

                                if($es_flv) $tamany = 1024000; //Pels FLV nomÃ©s es vol el amany maxim d'1MB

                                $output = $dir_tmp.$tamany.$file_output;

                                $cmd = "/usr/local/bin/ffmpeg -y -i $input $set_conf $output  &> /dev/null";
                                system($cmd);

				if(DEBUG_FFMPEG) print "$cmd\n\n";

                                $tamany_fitxer_generat = $this->get_file_size("$output");

				if(DEBUG_FFMPEG) print "Vull $tamany pero genero $tamany_fitxer_generat\n\n";

//                              print "IMPOSO $tamany i obtinc $tamany_fitxer_generat MIN: $min_video_bitrate MAX: $max_video_bitrate MINFPS: $min_video_fps MAXFPS: $max_video_fps MAXABRATE: $max_audio_bitrate MINABRATE: $min_audio_bitrate<br>$cmd\n";

                                if($tamany_fitxer_generat <= 0)
                                {
                                        //No s'ha pogut codificar el vÃ­deo, es possible que l'ffmpeg no reconegui el cÃ²ddec d'audio
                                        $set_conf = substr($set_conf, 0, strpos($set_conf, "-acodec"))." -an ";
					if($reduir_video_length) $set_conf .= " -t $length ";

                                        $cmd = "/usr/local/bin/ffmpeg -y -i \"$input\" $set_conf \"$output\"  &> /dev/null";
                                        system($cmd);
					if(DEBUG_FFMPEG) print "ffmpeg amb audio-none: $cmd\n\n";

                                        $tamany_fitxer_generat = $this->get_file_size("$output");

	                                if(DEBUG_FFMPEG) print "Vull $tamany pero genero $tamany_fitxer_generat\n\n";

                                        if((!file_exists("$output")) || ($tamany_fitxer_generat<=0))
					{
						if(DEBUG) print "ERROR: Ha intentat fer $cmd\npero no ha generat sortida.<br>";
						return false;
					}
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

/*                              $handle = fopen("/var/www/html/tmp/resultats.txt", "w");
                                fwrite($handle, "MIN: $min_video_bitrate  MAX: $max_video_bitrate T:$tamany_fitxer_generat T2:$tamany BITRATE: $bitrate DIF: $diferencia_tamany MINFPS: $min_video_fps MAXFPS: $max_video_fps MINABRATE: $min_audio_bitrate MAXABRATE: $max_audio_bitrate\n$cmd");
                                fclose($handle);*/


                                if($es_flv && $tamany_assolit) return true;
//                              if($tamany_assolit) return true; //S'ha assolit el tamany mï¿½xim del vï¿½deo
                        }
                }
                return true;
        }


}


?>
