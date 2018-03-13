<?php
define("TRUE", 'true');
define("FALSE", 'false');

require_once("conexio_bd.php");
require('../include/crop_canvas/class.cropinterface.php');

class preview_generator
{
	var $conn = null;
	function preview_generator()
	{
		$this->conn = new conexio_bd();
	}

	function SQL_obtenir_info_contingut($codi_contingut)
	{
                $sql = "select *
			from mm
			where codi_contingut = '$codi_contingut'";
                $res = $this->conn->sql_query($sql);
		$info = array();
                if($res->numRows())
                {
                        $row = $res->fetchRow(DB_FETCHMODE_ASSOC);
                        $info['id_mm'] = $row['id_mm'];
			$info['id_categoria_contingut'] = $row['id_categoria_contingut'];
			$info['nom'] = $row['nom'];
			$info['descripcio'] = $row['descripcio'];
			$info['preview'] = $row['preview_jpg'];
			switch($row['id_categoria_contingut'])
			{
				case 1:		$info['header'] = "image/jpeg"; break;
				case 2:		$info['header'] = "image/gif"; break;
				case 3:		$info['header'] = "image/gif"; break;
			}
			return $info;
		}else	return null;
	}

	function get_wallpaper_preview($info)
	{
		$codi_contingut = $info['codi_contingut'];
		$id_mm = $info['id_mm'];
		ob_start();
		header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
		header("Content-Type: ".($info['header']));

		$sql = "        select mco.id_mm as id_mm, mco.width as width, mco.height as height, co.contingut as contingut                from mm_contingut_original mco, contingut_original co
                		where  	mco.id_mm = $id_mm
                		        and mco.format = 'NORMAL'
		                        and mco.id_contingut_original = co.id_contingut_original";

		$res = $this->conn->sql_query($sql);
		$row = $res->fetchRow();

		$string_image = $row['contingut'];
		$image_width = $row['width'];
		$image_height = $row['height'];
		$ci =& new CropInterface(true);
		$ci->resizeImage($string_image, $image_width, $image_height, 200, 200);
		$wm = $ci->loadStringFromFile("wm.png");

		$ci->_imgOrig = $ci->_imgFinal;
		$ci->combineImage($wm, 200, 200, 0, 0, 200, 200, 25); //25% de transparencia
		print $ci->loadStringFromImage("imatge_preview_".($row['id_mm']));

		ob_end_flush();
	}

	function get_animation_preview($info)
	{
		$codi_contingut = $info['codi_contingut'];
		$id_mm = $info['id_mm'];
		ob_start();
		header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
		header("Content-Type: image/gif");
		$sql = "        select mco.width as width, mco.height as height, co.contingut as contingut
                		from mm_contingut_original mco, contingut_original co
		                where	mco.id_mm = $id_mm
		                        and mco.format = 'NORMAL'
                	        	and mco.id_contingut_original = co.id_contingut_original";

		$res = $this->conn->sql_query($sql);
		$row = $res->fetchRow();

		$string_image = $row['contingut'];
		$width = $row['width'];
		$height = $row['height'];

		$handle = fopen("/var/www/html/tmp/".($codi_contingut).".gif", "w");
		fwrite($handle, $string_image);
		fclose($handle);

		$cmd = "/usr/bin/gifsicle -l -d 150 -O2 --colors 128 --color-method median-cut --resize 175x175";
		$cmd .= " /var/www/html/tmp/".($codi_contingut).".gif -o /var/www/html/tmp/out_".($codi_contingut).".gif";
		system($cmd);
		$handle = fopen("/var/www/html/tmp/out_".($codi_contingut).".gif", "r");
		$content = fread($handle, filesize("/var/www/html/tmp/out_".($codi_contingut).".gif"));
		fclose($handle);
		system("rm /var/www/tml/tmp/out_".($codi_contingut).".gif -f");
		system("rm /var/www/tml/tmp/".($codi_contingut).".gif -f");
		print $content;
		ob_end_flush();
	}

	function get_video_preview($info)
	{

	}

	function get_preview($codi_contingut)
	{
		$info = $this->SQL_obtenir_info_contingut($codi_contingut);

		if($info==null) { print "ERROR"; die(); }
		else
		{
			switch($info['id_categoria_contingut'])
			{
				case 1:		$this->get_wallpaper_preview($info); break;
				case 2:		$this->get_animation_preview($info); break;
				case 3:		$this->get_video_preview($info); break;
			}
		}

	}
}
?>
