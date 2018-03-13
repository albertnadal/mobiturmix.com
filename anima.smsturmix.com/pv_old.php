<?php
require('../DB/conexio_bd.php');
require('../include/crop_canvas/class.cropinterface.php');

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else $codi_contingut = '';

if (isset($_GET["ih"])) $id_handset = $_GET["ih"];
elseif (isset($_POST["ih"])) $id_handset = $_POST["ih"];
else $id_handset = 441; //Nokia 6680

// control buffering with output control functions
ob_start();

// anti-cache headers
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// send content headers
header("Content-Type: image/gif");
//header('Content-Disposition: attachment; filename="phone.gif"');

        $con_bd = new conexio_bd();
        $res = $con_bd->sql_query("select * from imatge_handset where id_handset = $id_handset");
        if($res==null) print ""; //Error...
        else
        {
                $row = $res->fetchRow();
                $imatge_handset = $row['imatge_jpg'];
                $format = $orientacio = strtolower($row['orientacio_pantalla']);
                $x_ini = $row['x_ini'];
                $y_ini = $row['y_ini'];
                $x_fi = $row['x_fi'];
                $y_fi = $row['y_fi'];

		$sql = "select mm.id_mm as id_mm, mco.width as width, mco.height as height, co.contingut as contingut
			from mm_contingut_original mco, contingut_original co, mm mm
			where mm.codi_contingut = '$codi_contingut'
				and mco.id_mm = mm.id_mm
				and mco.format = '".($row['orientacio_pantalla'])."'
				and mco.id_contingut_original = co.id_contingut_original
				and mm.id_categoria_contingut=2";

		$res = $con_bd->sql_query($sql);
                $row = $res->fetchRow();

                $string_image = $row['contingut'];
                $x = $row['width'];
                $y = $row['height'];
		$handle = fopen("/var/www/html/tmp/handset_".($codi_contingut).".gif", "w");
                fwrite($handle, $string_image);
                fclose($handle);

                $width = $x_fi - $x_ini;
                $height = $y_fi - $y_ini;
                $dir_tmp = "/var/www/html/tmp/";
		$cmd = "/usr/bin/gifsicle -l -d 100 -O2 --colors 256 --color-method median-cut --resize "."$width"."x"."$height ".($dir_tmp)."handset_".($codi_contingut).".gif ";
		$cmd .= " -w -o ".($dir_tmp)."out_handset_".($codi_contingut).".gif ";

                system($cmd); //Executa la comanda i crea el gif animat :)
                $this->animacions['handset_preview'] =


                //Ara s'ha creat el preview en el tamany de la pantalla de la imatge del handset indicat
                //Ara cal superposar el preview sobre de la pantalla
                $im = imagecreatefromstring(obtenir_contingut_imatge_fitxer($dir_tmp."$format.gif"));
                imagegif($im, $dir_tmp."preview.gif");
                $cmd = "/usr/bin/gifsicle -l -d 100 -O2 --colors 256 --color-method median-cut ".$dir_tmp."handset.gif -p $x_ini,$y_ini ".$dir_tmp."$format.gif -o ".$dir_tmp."final_preview.gif";
                system($cmd);

        }

// flush content with ordered headers
ob_end_flush();

?>

