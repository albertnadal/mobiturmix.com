<?php
require_once("classe_comentaris.php");
require('constants_smsturmix.php');
include("classe_control_inputs.php");
require_once("conexio_bd.php");

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
else $codi_contingut = '';

if (isset($_GET["pag"])) $pag = $_GET["pag"];
else $pag = 1;

if (isset($_GET["do"])) $do = $_GET["do"];
else $do = '';


switch($do)
{
	case 'get_comments_html':	$comentaris = new comentaris();
					$comentaris->obtenir_panell_comentaris_contingut($codi_contingut, $pag);

					break;
}



	function obtenir_info_contingut($codi_contingut)
	{
		$con_bd = new conexio_bd();
		$sql = "select	nom, descripcio, durada, visites, id_categoria_contingut, puntuacio, vots, data_insert
			from	mm
			where	codi_contingut=\"$codi_contingut\"";
		$res = $con_bd->sql_query($sql);
		$row = $res->fetchRow();

		if($res!=null)	return $row;
		else		die();
	}

        function get_info_content_html($codi_contingut)
        {
		$contingut = obtenir_info_contingut($codi_contingut);
		$id_categoria_contingut = $contingut['id_categoria_contingut'];
		switch($id_categoria_contingut)
		{
			case VIDEO:	$classe_preview = 'vimg120';
					$classe_inner = 'videoIconWrapperInner';
					$classe_outer = 'videoIconWrapperOuter';
					$ample_desc = 260;
					$ample_preview = 130;
					$sub_domini = "video";
					break;
			default:	$classe_preview = 'vimg85';
					$classe_inner = 'imageIconWrapperInner';
					$classe_outer = 'imageIconWrapperOuter';
					$ample_desc = 256;
					$ample_preview = 95;
					if($id_categoria_contingut == ANIMATION) $sub_domini = "anima";
					else $sub_domini = "photo";
					break;
		}

                print "<div id=\"hpVideoList\">\n";
                print "\t<div id=\"hpFeatured\">\n";

                        $altra_categoria = ''; //$this->obtenir_altra_categoria_contingut($codi_contingut, $categoria);
			$altra_categoria['id_mm_categoria']= 1;
			$altra_categoria['nom'] = 'dfsfd';
                        $nom = $contingut['nom'];
                        $data_insert = $contingut['data_insert'];
                        $descripcio = $contingut['descripcio'];
			$puntuacio = $contingut['puntuacio'];
			$vots = $contingut['vots'];
                        $visites = $contingut['visites'];
                        $durada = $contingut['durada'];
                        $minuts = sprintf("%02s", floor($durada / 60));
                        $segons = sprintf("%02s", ($durada % 60));

			if(strlen($descripcio)>=150) $descripcio = substr($descripcio,0,150)."...";

                        print "\t\t<div class=\"vEntry\" style=\"border-bottom:0px\">\n";
                        print "\t\t\t<table width=\"99%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
                        print "\t\t\t\t<tbody>\n";

                        //La columna de la imatge
                        print "\t\t\t\t\t<tr>\n";
                        print "\t\t\t\t\t\t<td width=\"$ample_preview\" valign=\"top\" rowspan=\"2\">\n";
                        print "\t\t\t\t\t\t\t<div class=\"QLContainer\">\n";
                        print "\t\t\t\t\t\t\t\t<div class=\"$classe_outer\">\n";
                        print "\t\t\t\t\t\t\t\t\t<div class=\"$classe_inner\">\n";

			$link_contingut = "http://".$sub_domini.".mobiturmix.com/ft.php?c=".$codi_contingut."&ih=$ih&ca=0#top";

                        print "\t\t\t\t\t\t\t\t\t\t<a target=\"_top\" href=\"$link_contingut\">\n";
                        print "\t\t\t\t\t\t\t\t\t\t\t<img class=\"$classe_preview\" src=\"http://".$sub_domini.".mobiturmix.com/pr.php?c=$codi_contingut\" border=2></br>\n";
                        print "\t\t\t\t\t\t\t\t\t\t</a>\n";

                        print "\t\t\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t\t\t</div>\n";
                        print "\t\t\t\t\t\t\t</div>\n";
                        print "\t\t\t\t\t\t</td>\n";

                        //La columna de la descripció i el codi de contingut
                        print "\t\t\t\t\t\t<td width=\"$ample_desc\" valign=\"top\">\n";
                        print "\t\t\t\t\t\t\t<div class=\"vtitle\">\n"; //El títol (codi de contingut)
                        print "\t\t\t\t\t\t\t\t<a class=\"vtitlelink\" target=\"_top\" href=\"$link_contingut\">\n";
                        print "\t\t\t\t\t\t\t\t\t$nom\n";
                        print "\t\t\t\t\t\t\t\t</a>\n";
                        print "\t\t\t\t\t\t\t\t<br/>\n";
                        print "\t\t\t\t\t\t\t</div>\n";
                        print "\t\t\t\t\t\t\t<div class=\"vdesc\">\n"; //La descripció
                        print "\t\t\t\t\t\t\t\t<span> $descripcio </span>\n";
                        print "\t\t\t\t\t\t\t</div>\n";
                        print "\t\t\t\t\t\t</td>\n";

                        //La columna d'informació addicional
                        print "\t\t\t\t\t\t<td class=\"vInfo\" width=\"275\" valign=\"top\">\n";


//			$this->inserir_panell_download_to_your_mobile($codi_contingut, $ih, $marca_model);
//if($codi_contingut=='coju') $this->inserir_grafica_opinions($codi_contingut);

                        print "\t\t\t\t\t\t</td>\n";


                        print "\t\t\t\t\t</tr>\n";
                         //Filera amb la durada i altra secció
                        print "\t\t\t\t\t<tr>\n";

                        print "\t\t\t\t\t\t<td class=\"vInfo\" valign=\"top\" style=\"padding-left:0px\">\n";
			if($id_categoria_contingut==VIDEO)
			{
	                        print "\t\t\t\t\t\t\t<div>\n";
        	                print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Length:</span> <span class=\"runtime\">$minuts:$segons</span>\n";
                	        print "\t\t\t\t\t\t\t</div>\n";
			}

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Rate:</span>\n";
			for($i=1; $i<=5; $i++)
			{
				if($i<=$puntuacio) print "\t\t\t\t\t\t\t\t<img src=\"/star.gif\">\n";
				else print "\t\t\t\t\t\t\t\t<img src=\"/stare.gif\">\n";
			}
			print "\t\t\t\t\t\t\t\t&nbsp;$vots ratings";
                        print "\t\t\t\t\t\t\t</div>\n";

//                        if($id_categoria_contingut==VIDEO)
//                        {
                                print "\t\t\t\t\t\t\t<div>\n";
                                print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">User:</span> <span class=\"runtime\">mobiturmix</span>\n";
                                print "\t\t\t\t\t\t\t</div>\n";
//                        }

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Source:</span> <span class=\"runtime\">mobiturmix</span>\n";
                        print "\t\t\t\t\t\t\t</div>\n";

/*			if($id_categoria_contingut==WALLPAPER)
			{
                	        print "\t\t\t\t\t\t\t<div>\n";
	                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Views:</span> $visites\n";
        	                print "\t\t\t\t\t\t\t</div>\n";
			}*/

                        print "\t\t\t\t\t\t</td>\n";

                        print "\t\t\t\t\t\t<td class=\"vInfo\" width=\"200\" valign=\"top\">\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Added:</span> ".date("F j, Y",strtotime($data_insert))."\n";
                        print "\t\t\t\t\t\t\t</div>\n";

/*                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Category:</span> <a href=\"ftc.php?c=".$altra_categoria['id_mm_categoria']."\">".$altra_categoria['nom']."</a>\n";
                        print "\t\t\t\t\t\t\t</div>\n";
*/

//			if($id_categoria_contingut!=WALLPAPER)
//			{
	                        print "\t\t\t\t\t\t\t<div>\n";
        	                print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Views:</span> $visites\n";
                	        print "\t\t\t\t\t\t\t</div>\n";
//			}

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Comments:</span> 0\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t</td>\n";

                        print "\t\t\t\t\t</tr>\n";

                        print "\t\t\t\t</tbody>\n";
                        print "\t\t\t</table>\n";
                        print "\t\t</div>\n";

                print "\t</div\">\n";
                print "</div\">\n";
        }







die();





$con_bd = new conexio_bd();
$sql = "select preview_jpg, id_mm from mm where codi_contingut=\"$codi_contingut\"";
$res = $con_bd->sql_query($sql);
if($res!=null)
{
  $row = $res->fetchRow();
  switch($es_front)
  {
	case 3:	$imatge_polaroid = file_get_contents('/var/www/html/www.smsturmix.com/polaroid_red.jpg');
	case 2:	if($es_front==2) $imatge_polaroid = file_get_contents('/var/www/html/www.smsturmix.com/polaroid.jpg');

                $x_ini = 10;
                $y_ini = 10;
                $x_fi = 80;
                $y_fi = 80;

                $string_image = $row['preview_jpg'];
                $image_width = 70;
                $image_height = 70;

                $ci =& new CropInterface(true);
                $ci->loadImageFromString($imatge_polaroid);
                $ci->combineImage($string_image, $image_width, $image_height, $x_ini, $y_ini, $x_fi, $y_fi);

                $ample_maxim = 175;
                $image = $ci->_imgOrig;
                if(imagesx($image)>$ample_maxim)
                {
                        $width = $ample_maxim;
                        $height = (imagesy($image)*$width)/imagesx($image);
                        $ci->resizeImageFromImage($ci->_imgOrig, $width, $height);
                }
                print $ci->loadStringFromImage("polaroid_preview_".($row['id_mm']));	
		break;
	case 1:	$canvas = new CropCanvas();
		$im = $canvas->imagecopyresized($row['preview_jpg'], 50, 50);
		imagejpeg($im, '', 50);
		break;

	case 0:	$im = imagecreatefromstring($row['preview_jpg']);
		imagejpeg($im, '', 70);
		break;
  }
//  print $row['preview_jpg'];
}
// flush content with ordered headers
ob_end_flush();

?>
