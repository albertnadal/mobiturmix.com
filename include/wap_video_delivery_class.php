<?php
require_once("conexio_bd.php");
require_once('wurfl/wurfl_config.php');
require_once('constants_smsturmix.php');
require_once(WURFL_CLASS_FILE);

define('MAX_VIDEO_SIZE_FOR_UNKNOWN_HANDSET', 250000);

define ('UNKNOWN_HANDSET_SUPPORTS_VIDEO', true);
define ('UNKNOWN_HANDSET_SUPPORTS_3GPP', true);
define ('UNKNOWN_HANDSET_SUPPORTS_QCIF', true);
define ('UNKNOWN_HANDSET_SUPPORTS_VCODEC_H263', true);
define ('UNKNOWN_HANDSET_SUPPORTS_ACODEC_AMR_NB', true);

class wap_video_delivery_class
{
        var $user_agent = '';
        var $unique_id = '';

        function wap_video_delivery_class()
        {

        }

        function get_info_codi_contingut($codi_contingut)
        {
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("     select  *
                                                from    mm
                                                where   codi_contingut = '$codi_contingut'");

                if($res==null) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                else return $res;
        }

        function get_requested_download($unique_id)
        {
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("     select
                                                        pd.*,
							mm.codi_contingut as codi_contingut,
							mm.id_categoria_contingut as id_categoria_contingut
                                                from
                                                        peticio_descarrega pd,
                                                        mm mm
                                                where
                                                        pd.unique_id = '$unique_id'
                                                        and mm.id_mm = pd.id_mm");

                if($res==null) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                else return $res;
        }

        function get_user_agent_handset($idh=88)
        {
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("     select  user_agent
                                                from    handset_user_agent
                                                where   id_handset = $idh");

                if($res==null) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                else $row = $res->fetchRow();
                return $row['user_agent'];
        }

        function content_is_suspended($id_mm)
        {
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("     select
                                                        estat
                                                from
                                                        mm
                                                where
                                                        id_mm = $id_mm");
                if($res==null) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                else $row = $res->fetchRow();
                return ($row['estat']=='SUSPES');
        }

	function video_existeix($id_mm, $camp, $valor)
	{
		$con_bd = new conexio_bd();
                $res = $con_bd->sql_query("     select
                                                        co.tamany as tamany
                                                from
                                                        contingut_original co,
							mm_contingut_original mco
                                                where
                                                        mco.id_mm = $id_mm
							and co.id_contingut_original = mco.id_contingut_original
							and mco.$camp = '$valor'
					");

                if($res==null) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                else $row = $res->fetchRow();
                return ($row['tamany'] > 0);
	}

        function deliver_error_message($message)
        {
                // control buffering with output control functions
                ob_start();

                // send content headers
                header("Content-type: text/plain; charset=utf-8");
                print $message;

                // flush content with ordered headers
                ob_end_flush();
                die();
        }

        function obtenir_max_tamany_permes_id_handset($id_handset, $tipus_contingut)
        {
                switch($tipus_contingut)
                {
                        case '3GP':     $camp = 'max_3gp_size'; break;
                        case 'MP4':     $camp = 'max_mp4_size'; break;
                        default:        return MAX_VIDEO_SIZE_FOR_UNKNOWN_HANDSET;
                }

                $sql = "select  hc.$camp
                        from    handset_capability hc
                        where	hc.id_handset = $id_handset";

                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query($sql);
                if($res==null) return MAX_VIDEO_SIZE_FOR_UNKNOWN_HANDSET;
                else $row = $res->fetchRow();
                return $row[0];
        }

	function obtenir_max_tamany_permes($wurfl_fall_back, $tipus_contingut)
	{
		switch($tipus_contingut)
		{
			case '3GP':	$camp = 'max_3gp_size'; break;
			case 'MP4':	$camp = 'max_mp4_size'; break;
			default:	return MAX_VIDEO_SIZE_FOR_UNKNOWN_HANDSET;
		}

		$sql = "select	hc.$camp
			from	handset_capability hc,
				handset h
			where	h.id_wurfl_device = '$wurfl_fall_back'
				and hc.id_handset = h.id_handset";

                $con_bd = new conexio_bd();
		$res = $con_bd->sql_query($sql);
                if($res==null) return MAX_VIDEO_SIZE_FOR_UNKNOWN_HANDSET;
                else $row = $res->fetchRow();
                return $row[0];
	}

        function deliver_content()
        {
                $info_requested_download = $this->get_requested_download($this->unique_id);

                //Si no existeix la petici�de desc�rega aleshores no es retorna res
                if($info_requested_download->numRows()<=0) $this->deliver_error_message(ERROR_CONTENT_NOT_EXISTS);

                $row = $info_requested_download->fetchRow();
                $id_mm			= $row['id_mm'];
                $estat			= $row['estat'];
                $desc_disp		= $row['descarregues_disponibles'];
                $codi_contingut		= $row['codi_contingut'];
		$id_categoria_contingut	= $row['id_categoria_contingut'];

                if($estat == 'CADUCAT') $this->deliver_error_message(ERROR_CONTENT_DOWNLOAD_EXPIRED);
                else if($desc_disp < 0) $this->deliver_error_message(ERROR_CONTENT_NO_MORE_AVAILABLE_DOWNLOADS);
                else if($this->content_is_suspended($id_mm)) $this->deliver_error_message(ERROR_CONTENT_SUSPENDED);
                else
                {
                        //Es procedeix a la descarga del contingut
                        $myDevice = new wurfl_class();
			$myDevice->GetDeviceCapabilitiesFromAgent($this->user_agent);

			$es_un_movil	= $myDevice->capabilities['product_info']['is_wireless_device'];
                        if(!$es_un_movil) 
                        {
                                header("Location: http://www.mobiturmix.com/faq/descargar_mediante_wap.php?c=$codi_contingut");
                                return;
                                //die(); //Nomes s'accepten descarreges desde movils!
			}

                        //if(!$es_un_movil) die(); //Nomes s'accepten descarreges desde movils!

			$$_id = $myDevice->_GetDeviceCapabilitiesFromId($myDevice->id);
			$_curr_device = $$_id;
			$wurfl_fall_back = $_curr_device['fall_back'];

                        $es_wap         = $myDevice->browser_is_wap;
                        $soporta_video  = $myDevice->getDeviceCapability('video');
                        $soporta_3gpp   = $myDevice->getDeviceCapability('video_3gpp');
                        $soporta_3gpp2  = $myDevice->getDeviceCapability('video_3gpp2');
                        $soporta_mp4    = $myDevice->getDeviceCapability('video_mp4');
                        $soporta_wmv    = $myDevice->getDeviceCapability('video_wmv');
			$soporta_mov    = $myDevice->getDeviceCapability('video_mov');
			$soporta_qcif   = $myDevice->getDeviceCapability('video_qcif');
                        $soporta_sqcif  = $myDevice->getDeviceCapability('video_sqcif');
                        $soporta_h263   = $myDevice->getDeviceCapability('video_vcodec_h263_0');
                        $soporta_mpeg4  = $myDevice->getDeviceCapability('video_vcodec_mpeg4');
                        $soporta_amr_nb = $myDevice->getDeviceCapability('video_acodec_amr');
                        $soporta_amr_wb = $myDevice->getDeviceCapability('video_acodec_awb');
                        $soporta_aac    = $myDevice->getDeviceCapability('video_acodec_aac');

/*			print "<pre>";
			print_r($myDevice->capabilities);
			print "</pre>";*/

                        $es_handset_desconegut = (($myDevice->capabilities) == array());
                        if($es_handset_desconegut)
                        {
				$soporta_video  = UNKNOWN_HANDSET_SUPPORTS_VIDEO;
                                $soporta_3gpp   = UNKNOWN_HANDSET_SUPPORTS_3GPP;
                                $soporta_qcif   = UNKNOWN_HANDSET_SUPPORTS_QCIF;
                                $soporta_h263   = UNKNOWN_HANDSET_SUPPORTS_VCODEC_H263;
                                $soporta_amr_nb = UNKNOWN_HANDSET_SUPPORTS_ACODEC_AMR_NB;
                        }

                        if(!$soporta_video) $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_VIDEOS);
                        else
                        {
				if(($soporta_3gpp)&&($this->video_existeix($id_mm, 'tipus_contingut', '3GP'))) $tipus_contingut = '3GP';
				else if(($soporta_mp4)&&($this->video_existeix($id_mm, 'tipus_contingut', 'MP4'))) $tipus_contingut = 'MP4';
				else $tipus_contingut = '3GP'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_3GPP_MP4);

				if(($soporta_qcif)&&($this->video_existeix($id_mm, 'video_size', 'qcif'))) $video_size = 'qcif';
				else if(($soporta_sqcif)&&($this->video_existeix($id_mm, 'video_size', 'sqcif'))) $video_size = 'sqcif';
				else $video_size = 'qcif'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_QCIF_SQCIF);

				if(($soporta_h263)&&($this->video_existeix($id_mm, 'video_codec', 'h263'))) $video_codec = 'h263';
				else if(($soporta_mpeg4)&&($this->video_existeix($id_mm, 'video_size', 'mp4'))) $video_codec = 'mp4';
				else $video_codec = 'h263'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_H263_MP4);

				if(($soporta_amr_nb)&&($this->video_existeix($id_mm, 'audio_codec', 'amr_nb'))) $audio_codec = 'amr_nb';
				else if(($soporta_amr_wb)&&($this->video_existeix($id_mm, 'audio_codec', 'amr_wb'))) $audio_codec = 'amr_wb';
				else if(($soporta_aac)&&($this->video_existeix($id_mm, 'audio_codec', 'aac'))) $audio_codec = 'aac';
				else $audio_codec = 'amr_nb'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_AMRNB_AMRWB_AAC);

                                $con_bd = new conexio_bd();
				$sql = "     select
                                                                        co.contingut as contingut,
                                                                        mt.mime_type as mime_type,
                                                                        mt.extensio as extensio,
                                                                        mco.tamany as tamany
                                                                from
                                                                        mm_contingut_original mco,
                                                                        contingut_original co,
                                                                        mime_type mt
                                                                where
                                                                        mco.id_mm = $id_mm
                                                                        and mco.tipus_contingut = '$tipus_contingut'
                                                                        and mco.video_codec = '$video_codec'
                                                                        and mco.video_size = '$video_size'
                                                                        and mco.audio_codec = '$audio_codec'
                                                                        and co.id_contingut_original = mco.id_contingut_original
                                                                        and mt.id_mime_type = co.id_mime_type
                                                                order by tamany desc";

                                $res = $con_bd->sql_query($sql);

                                if(($res==null)||(!$res->numRows())) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                                else
                                {
					$max_size = $this->obtenir_max_tamany_permes($wurfl_fall_back, $tipus_contingut);
//					print "MAX TAMANY PEL $wurfl_fall_back EN $tipus_contingut ES $max_size<br>";
                                        while($row = $res->fetchRow())
					{
						$tamany = $row['tamany'];
                                                $contingut = $row['contingut'];
                                                $mime_type = $row['mime_type'];
                                                $extensio = $row['extensio'];

						if($tamany<=$max_size) break;
					}

//					print "TAMANY FINAL: $tamany | VIDEO SIZE: $video_size | AUDIO: $audio_codec<br>";

					ob_start();
					header("Content-Type: $mime_type");
					header("Content-Disposition: attachment; filename=$codi_contingut.$extensio");
					print $contingut;
					ob_end_flush();
                                }
                        }
                }
        }






        function deliver_content_codi_contingut($codi_contingut)
        {
                $info_requested_download = $this->get_info_codi_contingut($codi_contingut);

                //Si no existeix la petici�de desc�rega aleshores no es retorna res
                if($info_requested_download->numRows()<=0) $this->deliver_error_message(ERROR_CONTENT_NOT_EXISTS);

                $row = $info_requested_download->fetchRow();
                $id_mm                  = $row['id_mm'];
                $codi_contingut         = $row['codi_contingut'];
                $id_categoria_contingut = $row['id_categoria_contingut'];



                if($this->content_is_suspended($id_mm)) $this->deliver_error_message(ERROR_CONTENT_SUSPENDED);
                else
                {
                        //Es procedeix a la descarga del contingut
                        $myDevice = new wurfl_class();
                        $myDevice->GetDeviceCapabilitiesFromAgent($this->user_agent);

                        $es_un_movil    = $myDevice->capabilities['product_info']['is_wireless_device'];
                        if(!$es_un_movil) 
                        {
                                header("Location: http://www.mobiturmix.com/faq/descargar_mediante_wap.php?c=$codi_contingut");
                                return;
                                //die(); //Nomes s'accepten descarreges desde movils!
			}

                        //if(!$es_un_movil) die(); //Nomes s'accepten descarreges desde movils!

                        $$_id = $myDevice->_GetDeviceCapabilitiesFromId($myDevice->id);
                        $_curr_device = $$_id;
                        $wurfl_fall_back = $_curr_device['fall_back'];

                        $es_wap         = $myDevice->browser_is_wap;
                        $soporta_video  = $myDevice->getDeviceCapability('video');
                        $soporta_3gpp   = $myDevice->getDeviceCapability('video_3gpp');
                        $soporta_3gpp2  = $myDevice->getDeviceCapability('video_3gpp2');
                        $soporta_mp4    = $myDevice->getDeviceCapability('video_mp4');
                        $soporta_wmv    = $myDevice->getDeviceCapability('video_wmv');
                        $soporta_mov    = $myDevice->getDeviceCapability('video_mov');
                        $soporta_qcif   = $myDevice->getDeviceCapability('video_qcif');
                        $soporta_sqcif  = $myDevice->getDeviceCapability('video_sqcif');
                        $soporta_h263   = $myDevice->getDeviceCapability('video_vcodec_h263_0');
                        $soporta_mpeg4  = $myDevice->getDeviceCapability('video_vcodec_mpeg4');
                        $soporta_amr_nb = $myDevice->getDeviceCapability('video_acodec_amr');
                        $soporta_amr_wb = $myDevice->getDeviceCapability('video_acodec_awb');
                        $soporta_aac    = $myDevice->getDeviceCapability('video_acodec_aac');

/*                      print "<pre>";
                        print_r($myDevice->capabilities);
                        print "</pre>";*/

                        $es_handset_desconegut = (($myDevice->capabilities) == array());
                        if($es_handset_desconegut)
                        {
                                $soporta_video  = UNKNOWN_HANDSET_SUPPORTS_VIDEO;
                                $soporta_3gpp   = UNKNOWN_HANDSET_SUPPORTS_3GPP;
                                $soporta_qcif   = UNKNOWN_HANDSET_SUPPORTS_QCIF;
                                $soporta_h263   = UNKNOWN_HANDSET_SUPPORTS_VCODEC_H263;
                                $soporta_amr_nb = UNKNOWN_HANDSET_SUPPORTS_ACODEC_AMR_NB;
                        }

                        if(!$soporta_video) $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_VIDEOS);
                        else
                        {
                                if(($soporta_3gpp)&&($this->video_existeix($id_mm, 'tipus_contingut', '3GP'))) $tipus_contingut = '3GP';
                                else if(($soporta_mp4)&&($this->video_existeix($id_mm, 'tipus_contingut', 'MP4'))) $tipus_contingut = 'MP4';
                                else $tipus_contingut = '3GP'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_3GPP_MP4);

                                if(($soporta_qcif)&&($this->video_existeix($id_mm, 'video_size', 'qcif'))) $video_size = 'qcif';
                                else if(($soporta_sqcif)&&($this->video_existeix($id_mm, 'video_size', 'sqcif'))) $video_size = 'sqcif';
                                else $video_size = 'qcif'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_QCIF_SQCIF);

                                if(($soporta_h263)&&($this->video_existeix($id_mm, 'video_codec', 'h263'))) $video_codec = 'h263';
                                else if(($soporta_mpeg4)&&($this->video_existeix($id_mm, 'video_size', 'mp4'))) $video_codec = 'mp4';
                                else $video_codec = 'h263'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_H263_MP4);

                                if(($soporta_amr_nb)&&($this->video_existeix($id_mm, 'audio_codec', 'amr_nb'))) $audio_codec = 'amr_nb';
                                else if(($soporta_amr_wb)&&($this->video_existeix($id_mm, 'audio_codec', 'amr_wb'))) $audio_codec = 'amr_wb';
                                else if(($soporta_aac)&&($this->video_existeix($id_mm, 'audio_codec', 'aac'))) $audio_codec = 'aac';
                                else $audio_codec = 'amr_nb'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_AMRNB_AMRWB_AAC);

                                $con_bd = new conexio_bd();
                                $sql = "     select
                                                                        co.contingut as contingut,
                                                                        mt.mime_type as mime_type,
                                                                        mt.extensio as extensio,
                                                                        mco.tamany as tamany
                                                                from
                                                                        mm_contingut_original mco,
                                                                        contingut_original co,
                                                                        mime_type mt
                                                                where
                                                                        mco.id_mm = $id_mm
                                                                        and mco.tipus_contingut = '$tipus_contingut'
                                                                        and mco.video_codec = '$video_codec'
                                                                        and mco.video_size = '$video_size'
                                                                        and mco.audio_codec = '$audio_codec'
                                                                        and co.id_contingut_original = mco.id_contingut_original
                                                                        and mt.id_mime_type = co.id_mime_type
                                                                order by tamany desc";

                                $res = $con_bd->sql_query($sql);

                                if(($res==null)||(!$res->numRows())) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                                else
                                {
                                        $max_size = $this->obtenir_max_tamany_permes($wurfl_fall_back, $tipus_contingut);
//                                      print "MAX TAMANY PEL $wurfl_fall_back EN $tipus_contingut ES $max_size<br>";
                                        while($row = $res->fetchRow())
                                        {
                                                $tamany = $row['tamany'];
                                                $contingut = $row['contingut'];
                                                $mime_type = $row['mime_type'];
                                                $extensio = $row['extensio'];

                                                if($tamany<=$max_size) break;
                                        }

//                                      print "TAMANY FINAL: $tamany | VIDEO SIZE: $video_size | AUDIO: $audio_codec<br>";

                                        ob_start();
                                        header("Content-Type: $mime_type");
                                        header("Content-Disposition: attachment; filename=$codi_contingut.$extensio");
                                        print $contingut;
                                        ob_end_flush();
                                }
                        }
                }
        }

        function deliver_content_codi_contingut_id_handset($codi_contingut='', $idh=88)
        {
                $info_requested_download = $this->get_info_codi_contingut($codi_contingut);

                //Si no existeix la petici�de desc�rega aleshores no es retorna res
                if($info_requested_download->numRows()<=0) $this->deliver_error_message(ERROR_CONTENT_NOT_EXISTS);

                $row = $info_requested_download->fetchRow();
                $id_mm                  = $row['id_mm'];
                $codi_contingut         = $row['codi_contingut'];
                $id_categoria_contingut = $row['id_categoria_contingut'];



                if($this->content_is_suspended($id_mm)) $this->deliver_error_message(ERROR_CONTENT_SUSPENDED);
                else
                {
                        $this->user_agent = $this->get_user_agent_handset($idh);

//			print "UA: ".($this->user_agent);
//			die();

                        //Es procedeix a la descarga del contingut
                        $myDevice = new wurfl_class();
                        $myDevice->GetDeviceCapabilitiesFromAgent($this->user_agent);

/*			print "<pre>";
			print_r($myDevice->capabilities);
			print "</pre>";*/

                        $es_un_movil    = $myDevice->capabilities['product_info']['is_wireless_device'];
                        if(!$es_un_movil)
                        {
                                header("Location: http://www.mobiturmix.com/faq/descargar_mediante_wap.php?c=$codi_contingut");
                                return;
                                //die(); //Nomes s'accepten descarreges desde movils!
                        }

                        //if(!$es_un_movil) die(); //Nomes s'accepten descarreges desde movils!

                        $$_id = $myDevice->_GetDeviceCapabilitiesFromId($myDevice->id);
                        $_curr_device = $$_id;
                        $wurfl_fall_back = $_curr_device['fall_back'];

                        $es_wap         = $myDevice->browser_is_wap;
                        $soporta_video  = $myDevice->getDeviceCapability('video');
                        $soporta_3gpp   = $myDevice->getDeviceCapability('video_3gpp');
                        $soporta_3gpp2  = $myDevice->getDeviceCapability('video_3gpp2');
                        $soporta_mp4    = $myDevice->getDeviceCapability('video_mp4');
                        $soporta_wmv    = $myDevice->getDeviceCapability('video_wmv');
                        $soporta_mov    = $myDevice->getDeviceCapability('video_mov');
                        $soporta_qcif   = $myDevice->getDeviceCapability('video_qcif');
                        $soporta_sqcif  = $myDevice->getDeviceCapability('video_sqcif');
                        $soporta_h263   = $myDevice->getDeviceCapability('video_vcodec_h263_0');
                        $soporta_mpeg4  = $myDevice->getDeviceCapability('video_vcodec_mpeg4');
                        $soporta_amr_nb = $myDevice->getDeviceCapability('video_acodec_amr');
                        $soporta_amr_wb = $myDevice->getDeviceCapability('video_acodec_awb');
                        $soporta_aac    = $myDevice->getDeviceCapability('video_acodec_aac');

/*                      print "<pre>";
                        print_r($myDevice->capabilities);
                        print "</pre>";*/

                        $es_handset_desconegut = (($myDevice->capabilities) == array());
                        if($es_handset_desconegut)
                        {
                                $soporta_video  = UNKNOWN_HANDSET_SUPPORTS_VIDEO;
                                $soporta_3gpp   = UNKNOWN_HANDSET_SUPPORTS_3GPP;
                                $soporta_qcif   = UNKNOWN_HANDSET_SUPPORTS_QCIF;
                                $soporta_h263   = UNKNOWN_HANDSET_SUPPORTS_VCODEC_H263;
                                $soporta_amr_nb = UNKNOWN_HANDSET_SUPPORTS_ACODEC_AMR_NB;
                        }

                        if(!$soporta_video) $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_VIDEOS);
                        else
                        {
                                if(($soporta_3gpp)&&($this->video_existeix($id_mm, 'tipus_contingut', '3GP'))) $tipus_contingut = '3GP';
                                else if(($soporta_mp4)&&($this->video_existeix($id_mm, 'tipus_contingut', 'MP4'))) $tipus_contingut = 'MP4';
                                else $tipus_contingut = '3GP'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_3GPP_MP4);

                                if(($soporta_qcif)&&($this->video_existeix($id_mm, 'video_size', 'qcif'))) $video_size = 'qcif';
                                else if(($soporta_sqcif)&&($this->video_existeix($id_mm, 'video_size', 'sqcif'))) $video_size = 'sqcif';
                                else $video_size = 'qcif'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_QCIF_SQCIF);

                                if(($soporta_h263)&&($this->video_existeix($id_mm, 'video_codec', 'h263'))) $video_codec = 'h263';
                                else if(($soporta_mpeg4)&&($this->video_existeix($id_mm, 'video_size', 'mp4'))) $video_codec = 'mp4';
                                else $video_codec = 'h263'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_H263_MP4);

                                if(($soporta_amr_nb)&&($this->video_existeix($id_mm, 'audio_codec', 'amr_nb'))) $audio_codec = 'amr_nb';
                                else if(($soporta_amr_wb)&&($this->video_existeix($id_mm, 'audio_codec', 'amr_wb'))) $audio_codec = 'amr_wb';
                                else if(($soporta_aac)&&($this->video_existeix($id_mm, 'audio_codec', 'aac'))) $audio_codec = 'aac';
                                else $audio_codec = 'amr_nb'; //$this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_AMRNB_AMRWB_AAC);

                                $con_bd = new conexio_bd();
                                $sql = "     select
                                                                        co.contingut as contingut,
                                                                        mt.mime_type as mime_type,
                                                                        mt.extensio as extensio,
                                                                        mco.tamany as tamany
                                                                from
                                                                        mm_contingut_original mco,
                                                                        contingut_original co,
                                                                        mime_type mt
                                                                where
                                                                        mco.id_mm = $id_mm
                                                                        and mco.tipus_contingut = '$tipus_contingut'
                                                                        and mco.video_codec = '$video_codec'
                                                                        and mco.video_size = '$video_size'
                                                                        and mco.audio_codec = '$audio_codec'
                                                                        and co.id_contingut_original = mco.id_contingut_original
                                                                        and mt.id_mime_type = co.id_mime_type
                                                                order by tamany desc";

                                $res = $con_bd->sql_query($sql);

                                if(($res==null)||(!$res->numRows())) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                                else
                                {
                                        $max_size = $this->obtenir_max_tamany_permes_id_handset($idh, $tipus_contingut);
//                                      print "MAX TAMANY PEL $wurfl_fall_back EN $tipus_contingut ES $max_size<br>";
                                        while($row = $res->fetchRow())
                                        {
                                                $tamany = $row['tamany'];
                                                $contingut = $row['contingut'];
                                                $mime_type = $row['mime_type'];
                                                $extensio = $row['extensio'];

                                                if($tamany<=$max_size) break;
                                        }

//                                      print "TAMANY FINAL: $tamany | VIDEO SIZE: $video_size | AUDIO: $audio_codec<br>";

                                        ob_start();
                                        header("Content-Type: $mime_type");
                                        header("Content-Disposition: attachment; filename=$codi_contingut.$extensio");
                                        print $contingut;
                                        ob_end_flush();
                                }
                        }
                }
        }

}
?>
