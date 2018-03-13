<?php
require_once("conexio_bd.php");
require_once('wurfl/wurfl_config.php');
require_once('constants_smsturmix.php');
require_once('wap_wallpaper_delivery_class.php');
require_once('wap_animacio_delivery_class.php');
require_once('wap_video_delivery_class.php');
require_once(WURFL_CLASS_FILE);

class wap_content_delivery_class
{
        var $user_agent = '';
        var $unique_id = '';
	var $codi_contingut = '';

        function wap_content_delivery_class()
        {
        }

	function get_info_codi_contingut($codi_contingut)
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("     select	*
                                                from	mm
                                                where	codi_contingut = '$codi_contingut'");

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
                print utf8_encode($message);

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
			switch($id_categoria_contingut)
			{
				case WALLPAPER:	$deliver = new wap_wallpaper_delivery_class(); break;
				case VIDEO:	$deliver = new wap_video_delivery_class(); break;
				case AUDIO:	$deliver = new wap_wallpaper_delivery_class(); break;
				case ANIMATION:	$deliver = new wap_animacio_delivery_class(); break;
				default:	$this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
			}

			$deliver->user_agent = $this->user_agent;
			$deliver->unique_id = $this->unique_id;
			$deliver->deliver_content();
                }
        }

	function deliver_content_codi_contingut($codi_contingut)
        {
		$info_requested_download = $this->get_info_codi_contingut($codi_contingut);

                //Si no existeix la petici�de desc�rega aleshores no es retorna res
                if($info_requested_download->numRows()<=0) $this->deliver_error_message(ERROR_CONTENT_NOT_EXISTS);

                $row = $info_requested_download->fetchRow();
                $id_mm                  = $row['id_mm'];
                $id_categoria_contingut = $row['id_categoria_contingut'];

                if($this->content_is_suspended($id_mm)) $this->deliver_error_message(ERROR_CONTENT_SUSPENDED);
                else
                {
                        switch($id_categoria_contingut)
                        {
                                case WALLPAPER: $deliver = new wap_wallpaper_delivery_class(); break;
                                case VIDEO:     $deliver = new wap_video_delivery_class(); break;
                                case AUDIO:     $deliver = new wap_wallpaper_delivery_class(); break;
                                case ANIMATION: $deliver = new wap_animacio_delivery_class(); break;
                                default:        $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                        }

                        $deliver->user_agent = $this->user_agent;
                        $deliver->deliver_content_codi_contingut($codi_contingut);
                }
        }

	function deliver_content_codi_contingut_id_handset($codi_contingut='', $idh=88)
	{
                $info_requested_download = $this->get_info_codi_contingut($codi_contingut);

                //Si no existeix la petici�de desc�rega aleshores no es retorna res
                if($info_requested_download->numRows()<=0) $this->deliver_error_message(ERROR_CONTENT_NOT_EXISTS);

                $row = $info_requested_download->fetchRow();
                $id_mm                  = $row['id_mm'];
                $id_categoria_contingut = $row['id_categoria_contingut'];

                if($this->content_is_suspended($id_mm)) $this->deliver_error_message(ERROR_CONTENT_SUSPENDED);
                else
                {
                        switch($id_categoria_contingut)
                        {
                                case WALLPAPER: $deliver = new wap_wallpaper_delivery_class(); break;
                                case VIDEO:     $deliver = new wap_video_delivery_class(); break;
                                case AUDIO:     $deliver = new wap_wallpaper_delivery_class(); break;
                                case ANIMATION: $deliver = new wap_animacio_delivery_class(); break;
                                default:        $this->deliver_error_message(ERROR_TECNICAL_PROBLEM);
                        }

                        $deliver->user_agent = $this->user_agent;
                        $deliver->deliver_content_codi_contingut_id_handset($codi_contingut, $idh);
                }
	}
}
?>
