<?php
require_once("conexio_bd.php");
require('crop_canvas/class.cropinterface.php');
require_once('wurfl/wurfl_config.php');
require_once('constants_smsturmix.php');
require_once(WURFL_CLASS_FILE);

define('UNKNOWN_HANDSET_WIDTH',         176);
define('UNKNOWN_HANDSET_HEIGHT',        176);
define('UNKNOWN_HANDSET_SUPPORTS_JPG',  true);
define('UNKNOWN_HANDSET_SUPPORTS_GIF',  true);

class wap_wallpaper_delivery_class
{
        var $user_agent = '';
        var $unique_id = '';
	var $codi_contingut = '';

        function wap_wallpaper_delivery_class()
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

	function get_wurfl_device_from_handset($idh=88)
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("     select  id_wurfl_device
                                                from    handset
                                                where   id_handset = $idh");

                if($res==null) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                else $row = $res->fetchRow();
                return $row['id_wurfl_device'];
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

                        $es_wap         = $myDevice->browser_is_wap;
                        $soporta_jpg    = $myDevice->getDeviceCapability('jpg');
                        $soporta_gif    = $myDevice->getDeviceCapability('gif');
                        $width          = $myDevice->getDeviceCapability('resolution_width');
                        $height         = $myDevice->getDeviceCapability('resolution_height');

                        $es_handset_desconegut = (($myDevice->capabilities) == array());
                        if($es_handset_desconegut)
                        {
                                $width          = UNKNOWN_HANDSET_WIDTH;
                                $height         = UNKNOWN_HANDSET_HEIGHT;
                                $soporta_jpg    = UNKNOWN_HANDSET_SUPPORTS_JPG;
                                $soporta_gif    = UNKNOWN_HANDSET_SUPPORTS_GIF;
                        }

                        if((!$soporta_jpg)&&(!$soporta_gif)) $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_WALLPAPERS);
                        else
                        {
                                $ratio = $width / $height;
                                if($ratio==1)           $format = 'NORMAL';
                                else if($ratio<1)       $format = 'VERTICAL';
                                else                    $format = 'APAISAT';

                                $con_bd = new conexio_bd();
                                $res = $con_bd->sql_query("     select
                                                                        co.contingut, mco.width, mco.height, mco.tipus_contingut
                                                                from
                                                                        mm_contingut_original mco,
                                                                        contingut_original co
                                                                where
                                                                        mco.id_mm = $id_mm
                                                                        and mco.format = '$format'
                                                                        and co.id_contingut_original = mco.id_contingut_original");

                                if(($res==null)||(!$res->numRows())) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                                else
                                {
                                        $row = $res->fetchRow();
                                        $original_image_string = $row['contingut'];
                                        $original_width = $row['width'];
                                        $original_height = $row['height'];
                                        $original_tipus_contingut = $row['tipus_contingut'];

                                        $ci =& new CropInterface(true);
                                        $ci->resizeImage($original_image_string, $original_width, $original_height, $width, $height);
                                        if($soporta_jpg)	$ci->deliverImage('jpg', 100, $codi_contingut);
					else if($soporta_gif)	$ci->deliverImage('gif', 100, $codi_contingut);
					else $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_WALLPAPERS);
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

//                        if(!$es_un_movil) die(); //Nomes s'accepten descarreges desde movils!

                        $es_wap         = $myDevice->browser_is_wap;
                        $soporta_jpg    = $myDevice->getDeviceCapability('jpg');
                        $soporta_gif    = $myDevice->getDeviceCapability('gif');
                        $width          = $myDevice->getDeviceCapability('resolution_width');
                        $height         = $myDevice->getDeviceCapability('resolution_height');

                        $es_handset_desconegut = (($myDevice->capabilities) == array());
                        if($es_handset_desconegut)
                        {
                                $width          = UNKNOWN_HANDSET_WIDTH;
                                $height         = UNKNOWN_HANDSET_HEIGHT;
                                $soporta_jpg    = UNKNOWN_HANDSET_SUPPORTS_JPG;
                                $soporta_gif    = UNKNOWN_HANDSET_SUPPORTS_GIF;
                        }

                        if((!$soporta_jpg)&&(!$soporta_gif)) $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_WALLPAPERS);
                        else
                        {
                                $ratio = $width / $height;
                                if($ratio==1)           $format = 'NORMAL';
                                else if($ratio<1)       $format = 'VERTICAL';
                                else                    $format = 'APAISAT';

                                $con_bd = new conexio_bd();
                                $res = $con_bd->sql_query("     select
                                                                        co.contingut, mco.width, mco.height, mco.tipus_contingut
                                                                from
                                                                        mm_contingut_original mco,
                                                                        contingut_original co
                                                                where
                                                                        mco.id_mm = $id_mm
                                                                        and mco.format = '$format'
                                                                        and co.id_contingut_original = mco.id_contingut_original");

                                if(($res==null)||(!$res->numRows())) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                                else
                                {
                                        $row = $res->fetchRow();
                                        $original_image_string = $row['contingut'];
                                        $original_width = $row['width'];
                                        $original_height = $row['height'];
                                        $original_tipus_contingut = $row['tipus_contingut'];

                                        $ci =& new CropInterface(true);
                                        $ci->resizeImage($original_image_string, $original_width, $original_height, $width, $height);
                                        if($soporta_jpg)        $ci->deliverImage('jpg', 100, $codi_contingut);
                                        else if($soporta_gif)   $ci->deliverImage('gif', 100, $codi_contingut);
                                        else $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_WALLPAPERS);
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
/*			print "IDH: $idh<br>";
			die();*/
			$this->id_wurfl_device = $this->get_wurfl_device_from_handset($idh);
//			print "IDWD: ".($this->id_wurfl_device)."<br>";
//			die();

                        //Es procedeix a la descarga del contingut
                        $myDevice = new wurfl_class();
                        $myDevice->GetDeviceCapabilitiesFromAgent($this->user_agent);
/*			print "<pre>";
			print_r($myDevice->capabilities);
			print "</pre>";*/
//			die();

                        $es_un_movil    = $myDevice->capabilities['product_info']['is_wireless_device'];
                        if(!$es_un_movil)
                        {
                                header("Location: http://www.mobiturmix.com/faq/descargar_mediante_wap.php?c=$codi_contingut");
                                return;
                                //die(); //Nomes s'accepten descarreges desde movils!
                        }

//                        if(!$es_un_movil) die(); //Nomes s'accepten descarreges desde movils!

                        $es_wap         = $myDevice->browser_is_wap;
                        $soporta_jpg    = $myDevice->getDeviceCapability('jpg');
                        $soporta_gif    = $myDevice->getDeviceCapability('gif');
                        $width          = $myDevice->getDeviceCapability('resolution_width');
                        $height         = $myDevice->getDeviceCapability('resolution_height');

                        $es_handset_desconegut = (($myDevice->capabilities) == array());
                        if($es_handset_desconegut)
                        {
                                $width          = UNKNOWN_HANDSET_WIDTH;
                                $height         = UNKNOWN_HANDSET_HEIGHT;
                                $soporta_jpg    = UNKNOWN_HANDSET_SUPPORTS_JPG;
                                $soporta_gif    = UNKNOWN_HANDSET_SUPPORTS_GIF;
                        }

                        if((!$soporta_jpg)&&(!$soporta_gif)) $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_WALLPAPERS);
                        else
                        {
                                $ratio = $width / $height;
                                if($ratio==1)           $format = 'NORMAL';
                                else if($ratio<1)       $format = 'VERTICAL';
                                else                    $format = 'APAISAT';

//				print "$format";

                                $con_bd = new conexio_bd();
                                $res = $con_bd->sql_query("     select
                                                                        co.contingut, mco.width, mco.height, mco.tipus_contingut
                                                                from
                                                                        mm_contingut_original mco,
                                                                        contingut_original co
                                                                where
                                                                        mco.id_mm = $id_mm
                                                                        and mco.format = '$format'
                                                                        and co.id_contingut_original = mco.id_contingut_original");

                                if(($res==null)||(!$res->numRows())) $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                                else
                                {
                                        $row = $res->fetchRow();
                                        $original_image_string = $row['contingut'];
                                        $original_width = $row['width'];
                                        $original_height = $row['height'];
                                        $original_tipus_contingut = $row['tipus_contingut'];

                                        $ci =& new CropInterface(true);
//					print "WO: $original_width | HO: $original_height | W: $width | H:$height ";
//					die();
                                        $ci->resizeImage($original_image_string, $original_width, $original_height, $width, $height);
                                        if($soporta_jpg)        $ci->deliverImage('jpg', 100, $codi_contingut);
                                        else if($soporta_gif)   $ci->deliverImage('gif', 100, $codi_contingut);
                                        else $this->deliver_error_message(ERROR_HANDSET_NOT_SUPPORTS_WALLPAPERS);
                                }
                        }
                }
	}
}
?>
