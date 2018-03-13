<?php
require('constants_smsturmix.php');
require_once("conexio_bd.php");

define("SERVER_NAME",           $_SERVER["SERVER_NAME"]);

define('MAX_ITEMS_CATEGORIA', 30); //Nombre màxim de fotos a mostrar a la graella de la fototeca

define('MAX_LONG_DESCRIPCIO', 75);

define('MES_DESCARREGAT',1);
define('MES_RECENT',0);

define('CATEGORIA_AMOR',        1);
define('CATEGORIA_DEPORTE',     2);
define('CATEGORIA_MUSICA',      3);
define('CATEGORIA_AMIGOS',      4);
define('CATEGORIA_FAMILIA',     5);
define('CATEGORIA_PAISAJES',    6);
define('CATEGORIA_VIAJES',      7);
define('CATEGORIA_DIVERTIDA',   8);
define('CATEGORIA_EROTICA',     9);

class interficie_llistar_continguts
{
	var $photo_link;
	var $video_link;
	var $audio_link;
	var $anima_link;
	var $www_link;

        function interficie_llistar_continguts ()
        {
		$host = $_SERVER["HTTP_HOST"];
		$root = substr($host, strrpos($host, '.') + 1);
		$this->photo_link = "http://photo.mobiturmix.".$root;
		$this->video_link = "http://video.mobiturmix.".$root;
		$this->audio_link = "http://audio.mobiturmix.".$root;
		$this->anima_link = "http://anima.mobiturmix.".$root;
		$this->www_link   = "http://www.mobiturmix.".$root;
        }

        function obtenir_info_categoria($categoria, $id_categoria_contingut)
        {
                $con_bd = new conexio_bd();
                $sql = "select  nom, descripcio
                        from    mm_categoria
                        where   id_mm_categoria=$categoria";
//                print "SQL: $sql";
		$res = $con_bd->sql_query($sql);
                $info = array();
		if(!$res->numRows()) return array('nom'=>'', 'descripcio'=>'');
		switch($id_categoria_contingut)
		{
			case WALLPAPER: $tipus='photos'; break;
                        case VIDEO: $tipus='videos'; break;
                        case AUDIO: $tipus='recordings'; break;
                        case ANIMATION: $tipus='animations'; break;
		}
                $row=$res->fetchRow();
		return array('nom' => $row['nom']." ".$tipus,'descripcio' => $row['descripcio']);
        }

        function obtenir_nom_handset($ih)
        {
                $con_bd = new conexio_bd();
                $sql = "select  mh.marca, h.model
                        from    handset h,
                                marca_handset mh
                        where   h.id_handset = $ih
                                and mh.id_marca_handset = h.id_marca_handset";
                $res = $con_bd->sql_query($sql);
                if(!$res->numRows()) die();
                $row = $res->fetchRow();
                return array('marca' => $row['marca'], 'model' => $row['model']);
        }

	function get_more_user_contents($id_user, $id_mm)
	{
                $con_bd = new conexio_bd();
                $sql = "select  mm.codi_contingut, mm.id_categoria_contingut
                        from    mm mm
                        where   mm.id_user = $id_user
				and mm.estat='APROVAT'
				and mm.public='Y'
				and mm.id_mm <> $id_mm
			order by data_insert desc
			limit 13";

                $res = $con_bd->sql_query($sql);
                if(!$res->numRows()) die();
		$continguts = array();
                while($row = $res->fetchRow())
		{
			$contingut = array();
			$contingut['codi_contingut'] = $row['codi_contingut'];
			$contingut['id_categoria_contingut'] = $row['id_categoria_contingut'];
			array_push($continguts, $contingut);
		}
		return $continguts;
	}

	function obtenir_continguts_categoria($id_categoria_contingut=WALLPAPER, $id_mm_categoria, $cerca='',$max=16, $pag=1)
	{
		switch($id_categoria_contingut)
		{
			case WALLPAPER:	return $this->obtenir_continguts_phototeca($id_mm_categoria, $cerca, $max, $pag); break;
			case ANIMATION:	return $this->obtenir_continguts_animateca($id_mm_categoria, $cerca, $max, $pag); break;
			case VIDEO:	return $this->obtenir_continguts_videoteca($id_mm_categoria, $cerca, $max, $pag); break;
			default:	die();
		}

	}

	function obtenir_continguts_phototeca($id_mm_categoria, $cerca='', $max=16, $pag=1)
	{
                $ini = ($pag-1)*$max;
                if(($max==null)||($pag==null)) $limit = ""; else $limit = " limit $ini, $max";

                $con_bd = new conexio_bd();
                switch($id_mm_categoria)
                {
                        case MES_RECENT:        $tokens = explode(" ", $cerca);
                                                if(count($tokens))
                                                {
                                                        $sql = "select	mm.id_mm as id_mm,
									mm.codi_contingut as codi_contingut,
									mm.data_insert as data_insert,
									mm.descripcio as descripcio,
									mm.nom as nom,
									mm.durada as durada,
									mm.visites as visites,
									mm.puntuacio as puntuacio,
									mm.vots as vots,
									u.login as login,
									u.id_user as id_user,
									s.name as source
                                                                from	mm mm, user u, source s
                                                                where   id_categoria_contingut = ".WALLPAPER."
									and u.id_user = mm.id_user
									and s.id_source = mm.id_source
                                                                        and mm.estat = 'APROVAT'
                                                                        and mm.public = 'Y'
                                                                        and (";
                                                        $or = "";
                                                        foreach($tokens as $token)
                                                        {
                                                                $sql .= " $or mm.descripcio like '% $token %'
									   or mm.descripcio like '$token %'
									   or mm.descripcio like '% $token' ";
                                                                $or = "or";
								$sql .= " $or mm.nom like '%$token%'
									   or mm.nom like '$token %'
									   or mm.nom like '% $token' ";
                                                        }
                                                        $sql .= ")  order by mm.data_insert desc
                                                                $limit";
                                                }
                                                else
                                                {

                                                        $sql = "select	id_mm,
									codi_contingut,
									data_insert,
									descripcio,
									nom,
									durada,
									visites,
									puntuacio,
									vots
                                                                from mm
                                                                where id_categoria_contingut = ".WALLPAPER."
                                                                        and mm.estat = 'APROVAT'
                                                                        and mm.public = 'Y'
                                                                order by data_insert desc
                                                                $limit";
                                                }
                                                break;

                        default:                $sql = "select	mm.id_mm as id_mm,
								mm.codi_contingut as codi_contingut,
								mm.data_insert as data_insert,
								mm.descripcio as descripcio,
								mm.nom as nom,
								mm.durada as durada,
								mm.visites as visites,
								mm.puntuacio as puntuacio,
								mm.vots as vots,
								u.login as login,
								u.id_user as id_user,
								s.name as source
                                                        from	mm mm, mm_categoria_mm mcm, user u, source s
                                                        where	mm.id_mm = mcm.id_mm
								and u.id_user = mm.id_user
								and s.id_source = mm.id_source
                                                                and mcm.id_mm_categoria = $id_mm_categoria
                                                                and mm.id_categoria_contingut = ".WALLPAPER."
                                                                and mm.estat = 'APROVAT'
                                                                and mm.public = 'Y'
                                                        order by mm.data_insert desc, mcm.data_insert desc
                                                        $limit";
                                                break;
                }

                $res = $con_bd->sql_query($sql);
                return $res;	
	}

        function obtenir_continguts_animateca($id_mm_categoria, $cerca='', $max=16, $pag=1)
        {
                $ini = ($pag-1)*$max;
                if(($max==null)||($pag==null)) $limit = ""; else $limit = " limit $ini, $max";

                $con_bd = new conexio_bd();
                switch($id_mm_categoria)
                {
                        case MES_RECENT:        $tokens = explode(" ", $cerca);
                                                if(count($tokens))
                                                {
                                                        $sql = "select  mm.id_mm as id_mm,
                                                                        mm.codi_contingut as codi_contingut,
                                                                        mm.data_insert as data_insert,
                                                                        mm.descripcio as descripcio,
                                                                        mm.nom as nom,
                                                                        mm.durada as durada,
                                                                        mm.visites as visites,
                                                                        mm.puntuacio as puntuacio,
                                                                        mm.vots as vots,
									u.id_user as id_user,
									u.login as login,
									s.name as source
                                                                from	mm mm, source s, user u
                                                                where   id_categoria_contingut = ".ANIMATION."
                                                                        and u.id_user = mm.id_user
                                                                        and s.id_source = mm.id_source
                                                                        and mm.estat = 'APROVAT'
                                                                        and mm.public = 'Y'
                                                                        and (";
                                                        $or = "";
                                                        foreach($tokens as $token)
                                                        {
                                                                $sql .= " $or mm.descripcio like '% $token %'
                                                                           or mm.descripcio like '$token %'
                                                                           or mm.descripcio like '% $token' ";
                                                                $or = "or";
                                                                $sql .= " $or mm.nom like '%$token%'
                                                                           or mm.nom like '$token %'
                                                                           or mm.nom like '% $token' ";
                                                        }
                                                        $sql .= ")  order by mm.data_insert desc
                                                                $limit";
                                                }
                                                else
                                                {

                                                        $sql = "select  id_mm,
                                                                        codi_contingut,
                                                                        data_insert,
                                                                        descripcio,
                                                                        nom,
                                                                        durada,
                                                                        visites,
									puntuacio,
									vots
                                                                from mm
                                                                where id_categoria_contingut = ".ANIMATION."
                                                                        and mm.estat = 'APROVAT'
                                                                        and mm.public = 'Y'
                                                                order by data_insert desc
                                                                $limit";
                                                }
                                                break;

                        default:                $sql = "select  mm.id_mm as id_mm,
                                                                mm.codi_contingut as codi_contingut,
                                                                mm.data_insert as data_insert,
                                                                mm.descripcio as descripcio,
                                                                mm.nom as nom,
                                                                mm.durada as durada,
                                                                mm.visites as visites,
								mm.puntuacio as puntuacio,
								mm.vots as vots,
								u.login as login,
								u.id_user as id_user,
								s.name as source
                                                        from	mm mm, mm_categoria_mm mcm, source s, user u
                                                        where	mm.id_mm = mcm.id_mm
                                                                and u.id_user = mm.id_user
                                                                and s.id_source = mm.id_source
                                                                and mcm.id_mm_categoria = $id_mm_categoria
                                                                and mm.id_categoria_contingut = ".ANIMATION."
                                                                and mm.estat = 'APROVAT'
                                                                and mm.public = 'Y'
                                                        order by mm.data_insert desc, mcm.data_insert desc
                                                        $limit";
                                                break;
                }

                $res = $con_bd->sql_query($sql);
//		echo "SQL: $sql | NE:".($res->numRows());
                return $res;
        }

        function obtenir_continguts_videoteca($id_mm_categoria, $cerca='', $max=16, $pag=1)
        {
                $ini = ($pag-1)*$max;
                if(($max==null)||($pag==null)) $limit = ""; else $limit = " limit $ini, $max";

                $con_bd = new conexio_bd();
                switch($id_mm_categoria)
                {
                        case MES_RECENT:        $tokens = explode(" ", $cerca);
                                                if((count($tokens))&&($cerca!=''))
                                                {
							$string_stop_words = str_replace(', ', ',', STOPWORDS_ES);
							$stop_words = explode(",", $string_stop_words);
							$tokens = array_diff($tokens, $stop_words);

                                                        $sql = "select  distinct(mm.id_mm),
                                                                        mm.codi_contingut,
                                                                        mm.data_insert,
                                                                        mm.descripcio,
                                                                        mm.durada,
                                                                        mm.nom,
                                                                        mm.visites,
									mm.puntuacio,
									mm.vots,
									s.name as source,
									u.login as login,
									u.id_user as id_user
                                                                from    mm mm,
                                                                        mm_contingut_original mmco,
                                                                        contingut_original co,
									user u,
									source s
                                                                where   mm.id_mm = mmco.id_mm
									and u.id_user = mm.id_user
									and s.id_source = mm.id_source
                                                                        and mmco.video_codec = 'flv'
                                                                        and co.id_contingut_original = mmco.id_contingut_original
                                                                        and co.tamany > 0
                                                                        and mm.id_categoria_contingut = ".VIDEO."
                                                                        and mm.public = 'Y'
                                                                        and mm.estat = 'APROVAT' and ";
							if(count($tokens))
							{
								$sql .= " ( ";
        	                                                $or = "";
                	                                        foreach($tokens as $token)
                        	                                {
                                	                                $sql .= " $or mm.descripcio like '% $token %'
                                        	                                   or mm.descripcio like '$token %'
                                                	                           or mm.descripcio like '% $token' ";
                                                        	        $or = "or";
                                                                	$sql .= " $or mm.nom like '% $token %'
                                                                        	   or mm.nom like '$token %'
	                                                                           or mm.nom like '% $token' ";
        	                                                }
                	                                        $sql .= ") ";
							}else	$sql .= " 1 = 2 ";

							$sql .= " order by mm.data_insert desc
                                                                $limit";
                                                }
                                                else
                                                {
                                                        $sql = "select  distinct(mm.id_mm),
                                                                        mm.codi_contingut,
                                                                        mm.data_insert,
                                                                        mm.descripcio,
                                                                        mm.durada,
                                                                        mm.nom,
                                                                        mm.visites,
									mm.puntuacio,
									mm.vots,
									u.login as login,
									s.name as source,
									u.id_user as id_user
                                                                from    mm,
                                                                        mm_contingut_original mmco,
                                                                        contingut_original co,
									user u,
									source s
                                                                where   mm.id_mm = mmco.id_mm
        	                                                        and u.id_user = mm.id_user
	                                                                and s.id_source = mm.id_source
                                                                        and mmco.video_codec = 'flv'
                                                                        and co.id_contingut_original = mmco.id_contingut_original
                                                                        and co.tamany > 0
                                                                        and mm.estat = 'APROVAT'
                                                                        and mm.public = 'Y'
                                                                        and mm.id_categoria_contingut = ".VIDEO."
                                                                order by mm.data_insert desc
                                                                $limit";
                                                }
                                                break;

                        default:                $sql = "select  distinct(mm.id_mm),
                                                                mm.codi_contingut,
                                                                mm.data_insert,
                                                                mm.descripcio,
                                                                mm.durada,
                                                                mm.nom,
                                                                mm.visites,
								mm.puntuacio,
								mm.vots,
								u.login as login,
								s.name as source,
								u.id_user as id_user
                                                        from    mm mm,
                                                                mm_categoria_mm mcm,
                                                                mm_contingut_original mmco,
                                                                contingut_original co,
								user u,
								source s
                                                        where   mm.id_mm = mmco.id_mm
                                                                and u.id_user = mm.id_user
                                                                and s.id_source = mm.id_source
                                                                and mmco.video_codec = 'flv'
                                                                and co.id_contingut_original = mmco.id_contingut_original
                                                                and co.tamany > 0
                                                                and mm.id_mm = mcm.id_mm
                                                                and mcm.id_mm_categoria = $id_mm_categoria
                                                                and mm.id_categoria_contingut = ".VIDEO."
                                                                and mm.estat = 'APROVAT'
                                                                and mm.public = 'Y'
                                                        order by mm.data_insert desc, mcm.data_insert desc
                                                        $limit";
                                                break;
                }
//		echo "$sql";
                $res = $con_bd->sql_query($sql);
                return $res;
	}

        function obtenir_altra_categoria_contingut($codi_contingut, $categoria)
        {
                $con_bd = new conexio_bd();
                $sql = "select  mc.id_mm_categoria, mc.nom
                        from    mm_categoria_mm mcm, mm_categoria mc, mm mm
                        where   mm.codi_contingut = '$codi_contingut'
                                and mcm.id_mm = mm.id_mm
                                and mc.id_mm_categoria = mcm.id_mm_categoria
                                and mc.id_mm_categoria != $categoria";
                $res = $con_bd->sql_query($sql);
                $array_categories = array();
                while($row=$res->fetchRow())
                        array_push($array_categories, array('id_mm_categoria'=>$row[0], 'nom'=>utf8_encode($row[1])));

                if(!count($array_categories)) return array('id_mm_categoria'=>'0', 'nom'=>'The + recent');
                else return $array_categories[rand(0, count($array_categories) - 1)];
        }

	function inserir_panell_download_to_your_mobile($codi_contingut, $ih, $marca_model)
	{
                        print "\t\t\t\t\t\t<div style=\"border:1px solid #CCCCCC; padding:0px 5pt 0pt; background-image:url(http://www.mobiturmix.com/dtmb.jpg); background-position:left bottom; background-repeat:repeat-x;margin-bottom:5px;\">\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<table border=0 cellpadding=0 cellspacing=0 align=\"center\"><tr><td valign=\"middle\"><a href=\"http://www.mobiturmix.com/faq/formas_de_descargar_el_contenido.php\" target=\"_top\"><img src=\"http://www.mobiturmix.com/ask.gif\" alt=\"How to...\" title=\"How to...\" style=\"width:10px;height:16px;\"></a></td><td valign=\"middle\"> &nbsp;<span class=\"label\" style=\"cursor:pointer;\" onclick=\"d=gebi('".$codi_contingut."_box'); if(d.style.display=='block') d.style.display='none'; else d.style.display='block';\" onmouseover=\"this.style.color='#333333';\" onmouseout=\"this.style.color='#ababab';\">Download to your mobile!</span></td></tr></table>\n";
                        print "\t\t\t\t\t\t\t</div>\n";


                        print "\t\t\t\t<div id=\"".$codi_contingut."_box\" style=\"display:none;\">";

                        print "\t\t\t\t\t<div class=\"Areas\">\n";
                        print "\t\t\t\t\t\t<div id=\"".$codi_contingut."_File\" class=\"In\" onclick=\"sd('$codi_contingut', 'File');\">File</div>\n";
                        print "\t\t\t\t\t\t<div id=\"".$codi_contingut."_WAP\" class=\"Out\" onclick=\"sd('$codi_contingut', 'WAP');\">WAP</div>\n";
                        print "\t\t\t\t\t\t<div id=\"".$codi_contingut."_SMS\" class=\"Out\" onclick=\"sd('$codi_contingut', 'SMS');\">SMS</div>\n";
                        print "\t\t\t\t\t</div>\n";


                        print "\t\t\t\t\t\t\t<div id=\"".$codi_contingut."_File_box\">\n";
                        if($ih=='') $onclick=" onclick=\"return upz();\"; "; else $onclick="";
                        print "\t\t\t\t\t\t\t\t&nbsp;<a href=\"http://get.mobiturmix.com/$codi_contingut/$ih\" $onclick><b>Download now!</b></a>\n";
                        if($ih!='') print "for <b>$marca_model</b>\n";
                        print "\t\t\t\t\t\t\t<br/>&nbsp;Download and copy the file to your mobile<br/>\n";
                        print "\t\t\t\t\t\t\t&nbsp;device via <b>Bluetooth</b>, <b>cable</b> or <b>infrared</b>\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t\t<div id=\"".$codi_contingut."_WAP_box\" style=\"display:none;\">\n";
                        print "\t\t\t\t\t\t\t\t&nbsp;<a href=\"http://wap.mobiturmix.com/$codi_contingut/\" target=\"_top\"><b>wap.mobiturmix.com/$codi_contingut/</b></a><br/>&nbsp;Connect to Internet using your mobile device,<br/>\n";
                        print "\t\t\t\t\t\t\t\t&nbsp;type the <b>WAP</b> link and download the content<br/>\n";
                        print "\t\t\t\t\t\t\t\t&nbsp;* Download 100% free of charge\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t\t<div id=\"".$codi_contingut."_SMS_box\" style=\"display:none;\">\n";
                        print "\t\t\t\t\t\t\t\t&nbsp;Send the text <b>etm $codi_contingut</b> to <b>5767</b> via SMS<br/>\n";

                        print "\t\t\t\t\t\t\t\t&nbsp;* Only for <b>spain</b> resident users<br/>&nbsp;* Only 1 SMS is necessary <small>(1.2e + i.v.a)</small>\n";
/*                        print "\t\t\t\t\t\t\t\t<select style=\"height:15px;width:106px;font-family:Verdana;font-size:9px\">";
                        print "\t\t\t\t\t\t\t\t\t<option value\"1\">Movistar (spain)</option>";
                        print "\t\t\t\t\t\t\t\t\t<option value\"1\">Vodafone (spain)</option>";
                        print "\t\t\t\t\t\t\t\t\t<option value\"1\">Orange (spain)</option>";
                        print "\t\t\t\t\t\t\t\t</select>";*/
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t</div>\n"; //Tanca el box sencer
                        print "\t\t\t\t\t\t</div>\n";
	}

	function inserir_grafica_opinions($codi_contingut)
	{
		print "\t\t\t\t\t\t<center><div style=\"margin-bottom:5px;\" width=\"100%\">\n";
		print "<div class=\"demo\" id=\"canvaspie\" width=\"100%\" height=\"110\"></div>\n";
		//print "	<canvas id=\"".$codi_contingut."_pie\" height=\"110\" width=\"100%\"></canvas>\n";
		print "</div></center>\n";

/*		print '
  <script>
function demo()
{
   var hasCanvas = CanvasRenderer.isSupported();

   var opts = {
   "pieRadius": 0.4
   };

   var data1 = [[0, 5], [1, 4], [2, 3], [3, 5], [4, 6], [5, 7]];


   if (hasCanvas) {
       var pie = new PlotKit.EasyPlot("pie", opts, $("canvaspie"), [data1]);
   }
}
addLoadEvent(demo);
   </script>
';*/


/*
		print "<script type=\"text/javascript\">\n";
//		print "<!--\n";

		print "var options = {\n";
//		print "   \"colorScheme\": PlotKit.Base.palette(PlotKit.Base.baseColors()[0]), \n";
		print "   \"padding\": {left: 0, right: 0, top: 0, bottom: 0},\n";
		print "   \"xTicks\": [{v:0, label:\"A masterpiece!\"}, {v:1, label:\"A shit!\"}, {v:2, label:\"Freak\"}, {v:3, label:\"Boring\"}, {v:4, label:\"Interesting\"}],\n";
//		print "   \"drawYAxis\": false,\n";
		print "   \"pieRadius\": 0.35\n";
		print "};\n";

		print "function drawPieGraph()\n";
		print "{\n";
		print "   var layout = new Layout(\"pie\", options);\n";
		print "   layout.addDataset(\"sqrt\", [ [0, 2.5],  [1, 0.625],  [2, 0.625],  [3, 0.625],  [4, 0.625]]);\n";
		print "   layout.evaluate();\n";
//		print "   var canvas = document.getElementById(\"".$codi_contingut."_pie\");\n";

		print "   var plotter = new SweetCanvasRenderer($(\"".($codi_contingut)."_pie\"), layout, options);\n";
		print "   plotter.render();\n";
		print "}\n";
		print "addLoadEvent(drawPieGraph);\n";
//		print "-->\n";
		print "</script>\n";
*/
	}

	function inserir_panell_related_videos($codi_contingut, $ih)
	{
		print "\t\t\t\t\t\t<div style=\"border:1px solid #CCCCCC; padding:3px 5pt 1pt; background-image:url(http://www.mobiturmix.com/dtmb.jpg); background-position:left bottom; background-repeat:repeat-x;margin-bottom:5px;\">\n";

		print "\t\t\t\t\t\t\t<div>\n";
		print "\t\t\t\t\t\t\t\t<table border=0><tr><td valign=\"middle\"><a href=\"http://www.mobiturmix.com/faq/formas_de_descargar_el_contenido.php\" target=\"_top\"><img src=\"http://www.mobiturmix.com/rv.gif\" alt=\"How to...\" title=\"How to...\"></a></td><td valign=\"middle\"> &nbsp;<span class=\"label\" style=\"cursor:pointer;\" onclick=\"d=gebi('".$codi_contingut."_box'); if(d.style.display=='block') d.style.display='none'; else d.style.display='block';\">Related videos</span></td></tr></table>\n";
		print "\t\t\t\t\t\t\t</div>\n";


		print "\t\t\t\t<div id=\"".$codi_contingut."_box\" style=\"display:none;\">";

		print "\t\t\t\t\t<div class=\"Areas\">\n";
		print "\t\t\t\t\t\t<div id=\"".$codi_contingut."_File\" class=\"In\" onclick=\"sd('$codi_contingut', 'File');\">File</div>\n";
		print "\t\t\t\t\t\t<div id=\"".$codi_contingut."_WAP\" class=\"Out\" onclick=\"sd('$codi_contingut', 'WAP');\">WAP</div>\n";
		print "\t\t\t\t\t\t<div id=\"".$codi_contingut."_SMS\" class=\"Out\" onclick=\"sd('$codi_contingut', 'SMS');\">SMS</div>\n";
		print "\t\t\t\t\t</div>\n";


		print "\t\t\t\t\t\t\t<div id=\"".$codi_contingut."_File_box\">\n";
		if($ih=='') $onclick=" onclick=\"return upz();\"; "; else $onclick="";
		print "\t\t\t\t\t\t\t\t&nbsp;<a href=\"http://get.mobiturmix.com/$codi_contingut/$ih\" $onclick><b>Download now!</b></a>\n";
		if($ih!='') print "for <b>$marca_model</b>\n";
		print "\t\t\t\t\t\t\t<br/>&nbsp;Download and copy the file to your mobile<br/>\n";
		print "\t\t\t\t\t\t\t&nbsp;device via <b>Bluetooth</b>, <b>cable</b> or <b>infrared</b>\n";
		print "\t\t\t\t\t\t\t</div>\n";

		print "\t\t\t\t\t\t\t<div id=\"".$codi_contingut."_WAP_box\" style=\"display:none;\">\n";
		print "\t\t\t\t\t\t\t\t&nbsp;<a href=\"http://wap.mobiturmix.com/$codi_contingut/\" target=\"_top\"><b>wap.mobiturmix.com/$codi_contingut/</b></a><br/>&nbsp;Connect to Internet using your mobile device,<br/>\n";
		print "\t\t\t\t\t\t\t\t&nbsp;type the <b>WAP</b> link and download the content<br/>\n";
		print "\t\t\t\t\t\t\t\t&nbsp;* Download 100% free of charge\n";
		print "\t\t\t\t\t\t\t</div>\n";

		print "\t\t\t\t\t\t\t<div id=\"".$codi_contingut."_SMS_box\" style=\"display:none;\">\n";
		print "\t\t\t\t\t\t\t\t&nbsp;Send the text <b>etm $codi_contingut</b> to <b>5767</b> via SMS<br/>\n";

		print "\t\t\t\t\t\t\t\t&nbsp;* Only for <b>spain</b> resident users<br/>&nbsp;* Only 1 SMS is necessary <small>(1.2e + i.v.a)</small>\n";
		print "\t\t\t\t\t\t\t</div>\n";

		print "\t\t\t\t\t\t</div>\n"; //Tanca el box sencer
                print "\t\t\t\t\t\t</div>\n";
	}

        function inserir_panell_related_photos($codi_contingut, $ih)
        {
                print "\t\t\t\t\t\t<div align=\"left\" style=\"border:1px solid #CCCCCC; padding:0px 5pt 0pt 7pt; background-image:url(http://www.mobiturmix.com/dtmb.jpg); background-position:left bottom; background-repeat:repeat-x;\">\n";

		print "<small><small>See also:</small></small><br/>";

                print "<img src=\"http://video.smsturmix.com/pr.php?c=eoaq&f=2\"> ";

		print "<img src=\"http://video.smsturmix.com/pr.php?c=befa&f=2\"> ";

                print "<img src=\"http://video.smsturmix.com/pr.php?c=lasole&f=2\"> ";

                print "<img src=\"http://video.smsturmix.com/pr.php?c=uajw&f=2\"> ";

                print "<img src=\"http://video.smsturmix.com/pr.php?c=aaou&f=2\">";

                print "\t\t\t\t\t\t</div>\n";
        }

        function mostrar_llista_continguts($continguts, $categoria, $id_categoria_contingut=WALLPAPER, $ih='')
        {
		switch($id_categoria_contingut)
		{
			case VIDEO:	$classe_preview = 'vimg120';
					$classe_inner = 'videoIconWrapperInner';
					$classe_outer = 'videoIconWrapperOuter';
					$ample_desc = 260;
					$ample_preview = 130;
					break;
			default:	$classe_preview = 'vimg85';
					$classe_inner = 'imageIconWrapperInner';
					$classe_outer = 'imageIconWrapperOuter';
					$ample_desc = 256;
					$ample_preview = 95;
					break;
		}

		if($ih=='')
		{
			print "<script>\n";
			print "\tfunction upz()\n";
			print "\t{\n";
			print "\t\talert('Please, first of all choose your mobile device and retry.');";
			print "\t\treturn false;\n";
			print "\t}\n";
			print "</script>\n";
		}
		else
		{
                        $info = $this->obtenir_nom_handset($ih);
                        $marca = $info['marca'];
                        $model = $info['model'];
			$marca_model = $marca." ".$model;
			if(strlen($marca_model)>=18) $marca_model = substr($marca_model, 0, 18)."...";
		}


			print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/Base.js\"></script>\n";
			print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/Async.js\"></script>\n";
			print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/Iter.js\"></script>\n";
			print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/DOM.js\"></script>\n";

                        print "<script>\n";
                        print "function gebi(id){return document.getElementById(id);}\n";
                        print "function sd(codi,t)\n";
                        print "{\n";
                        print "\tvar a = new Array();\n";
                        print "\ta[0] = 'File';\n";
                        print "\ta[1] = 'WAP';\n";
                        print "\ta[2] = 'SMS';\n";
                        print "\tfor(i=0;i<a.length;i++) {\n";
                        print "\t\td=gebi(a[i]);\n";
                        print "\t\tn = DIV({'id': codi+'_'+a[i],'class': 'Out','onclick':'sd(\''+codi+'\',\''+a[i]+'\')'}, a[i]);\n";
                        print "\t\tswapDOM(gebi(codi+'_'+a[i]),n);\n";
                        print "\t\tb=gebi(codi+'_'+a[i]+'_box');\n";
                        print "\t\tb.style.display='none';\n";
                        print "\t}\n";

                        print "\t\tb=gebi(codi+'_'+t+'_box');\n";
                        print "\t\tb.style.display='block';\n";

                        print "\td = gebi(codi+'_'+t);\n";
                        print "\tn = DIV({'id': codi+'_'+t,'class': 'In'}, t);";
                        print "\tswapDOM(d,n);\n";
                        print "}\n";
                        print "</script>\n";

                print "<div id=\"hpVideoList\">\n";
                print "\t<div id=\"hpFeatured\">\n";
                foreach($continguts as $contingut)
                {
			$id_mm = $contingut['id_mm'];
                        $codi_contingut = $contingut['codi_contingut'];
                        $altra_categoria = $this->obtenir_altra_categoria_contingut($codi_contingut, $categoria);
                        $nom = $contingut['nom'];
			$id_user = $contingut['id_user'];
			$login = $contingut['login'];
			$source = $contingut['source'];
                        $data_insert = $contingut['data_insert'];
                        $descripcio = $contingut['descripcio'];
			$puntuacio = $contingut['puntuacio'];
			$vots = $contingut['vots'];
                        $visites = $contingut['visites'];
                        $durada = $contingut['durada'];
                        $minuts = sprintf("%02s", floor($durada / 60));
                        $segons = sprintf("%02s", ($durada % 60));

			if(strlen($descripcio)>=MAX_LONG_DESCRIPCIO) $descripcio = substr($descripcio,0,MAX_LONG_DESCRIPCIO)."...";

                        print "\t\t<div class=\"vEntry\">\n";
                        print "\t\t\t<table width=\"99%\" cellspacing=0 cellpadding=0 border=0>\n";
                        print "\t\t\t\t<tbody>\n";

                        //La columna de la imatge
                        print "\t\t\t\t\t<tr>\n";
                        print "\t\t\t\t\t\t<td width=\"$ample_preview\" valign=\"top\" rowspan=\"2\">\n";
                        print "\t\t\t\t\t\t\t<div class=\"QLContainer\">\n";
                        print "\t\t\t\t\t\t\t\t<div class=\"$classe_outer\">\n";
                        print "\t\t\t\t\t\t\t\t\t<div class=\"$classe_inner\">\n";

                        print "\t\t\t\t\t\t\t\t\t\t<a target=\"_top\" href=\"index.php?c=$codi_contingut"."&ih=$ih"."&ca=$categoria#top\">\n";
                        print "\t\t\t\t\t\t\t\t\t\t\t<img class=\"$classe_preview\" src=\"pr.php?c=$codi_contingut\" border=2></br>\n";
                        print "\t\t\t\t\t\t\t\t\t\t</a>\n";

                        print "\t\t\t\t\t\t\t\t\t</div>\n";
/*                        print "\t\t\t\t\t\t\t\t\t<div class=\"QLIcon QLIconHomepage\">\n";
                        print "\t\t\t\t\t\t\t\t\t\t<a target=\"_top\" href=\"index.php?c=$codi_contingut"."&ca=$categoria"."&ih=$ih#top\"><img border=\"0\" src=\"play.gif\"/>\n";
                        print "\t\t\t\t\t\t\t\t\t</div>\n";*/

                        print "\t\t\t\t\t\t\t\t</div>\n";
//			$this->incrustar_barra_eines_contingut("http://".$_SERVER['SERVER_NAME']."/index.php?c=$codi_contingut", $nom);
                        print "\t\t\t\t\t\t\t</div>\n";
                        print "\t\t\t\t\t\t</td>\n";

                        //La columna de la descripció i el codi de contingut
                        print "\t\t\t\t\t\t<td width=\"$ample_desc\" valign=\"top\">\n";
                        print "\t\t\t\t\t\t\t<div class=\"vtitle\">\n"; //El títol (codi de contingut)
                        print "\t\t\t\t\t\t\t\t<a class=\"vtitlelink\" target=\"_top\" href=\"index.php?c=$codi_contingut"."&ca=$categoria"."&ih=$ih#top\">\n";
                        print "\t\t\t\t\t\t\t\t\t$nom\n";
                        print "\t\t\t\t\t\t\t\t</a>\n";
                        print "\t\t\t\t\t\t\t\t<br/>\n";
                        print "\t\t\t\t\t\t\t</div>\n";
                        print "\t\t\t\t\t\t\t<div class=\"vdesc\">\n"; //La descripció
                        print "\t\t\t\t\t\t\t\t<span> $descripcio </span>\n";
                        print "\t\t\t\t\t\t\t</div>\n";
                        print "\t\t\t\t\t\t</td>\n";

                        //La columna d'informació addicional
                        print "\t\t\t\t\t\t<td class=\"vInfo\" width=\"275\" valign=\"top\" rowspan=2>\n";

			$this->incrustar_barra_eines_contingut("http://".$_SERVER['SERVER_NAME']."/index.php?c=$codi_contingut", $nom);
			$this->inserir_panell_download_to_your_mobile($codi_contingut, $ih, $marca_model);
if($codi_contingut=='coju') $this->inserir_grafica_opinions($codi_contingut);
//			$this->inserir_panell_related_videos($codi_contingut, $ih);
//			$this->inserir_panell_related_photos($codi_contingut, $ih);

//                        print "\t\t\t\t\t\t<br/>\n";

                        print "\t\t\t\t\t\t<table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse:collapse;\">\n";
                        print "\t\t\t\t\t\t<tr>\n";
                        print "\t\t\t\t\t\t\t<td class=\"vInfo\">\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Added:</span> ".date("F j, Y",strtotime($data_insert))."\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Category:</span> <a href=\"ftc.php?c=".$altra_categoria['id_mm_categoria']."\">".$altra_categoria['nom']."</a>\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        if($id_categoria_contingut!=WALLPAPER)
                        {
                                print "\t\t\t\t\t\t\t<div>\n";
                                print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Views:</span> $visites\n";
                                print "\t\t\t\t\t\t\t</div>\n";
                        }

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t<table border=0 cellspacing=0 cellpadding=0 style=\"border-collapse:collapse;\"><tr><td>";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Source:&nbsp;</span></td><td valign=\"bottom\"><img src=\"http://www.mobiturmix.com/sources/$source.gif\" title=\"$source\" alt=\"$source\">\n";
                        print "\t\t\t\t\t\t\t</td></tr></table>";
                        print "\t\t\t\t\t\t\t</div>\n";

			print "\t\t\t\t\t\t\t</td>\n";
			print "\t\t\t\t\t\t\t<td width=\"10%\" valign=\"top\">\n";


print "<div class=\"QLContainer\">\n";
print "	<div class=\"videoIconWrapperOuter\" style=\"width:40px;\">\n";
print "		<div class=\"videoIconWrapperInner\" style=\"width:38px;height:38px;\">\n";
//print "			<a href=\"index.php?c=niip&ih=&ca=0#top\" target=\"_top\">\n";
print "					<img style=\"margin-top:0px\" height=\"38\" width=\"38\" src=\"http://www.mobiturmix.com/users.php?op=get_mini_avatar&user=$login\">\n";
//print "			</a>\n";
print "		</div>\n";
print "	</div>\n";
print "</div>\n";

			print "\t\t\t\t\t\t\t</td>\n";
                        print "\t\t\t\t\t\t</tr>\n";
                        print "\t\t\t\t\t</table>\n";

                        print "\t\t\t\t\t\t</td>\n";


                        print "\t\t\t\t\t</tr>\n";
                         //Filera amb la durada i altra secció
                        print "\t\t\t\t\t<tr>\n";

                        print "\t\t\t\t\t\t<td class=\"vInfo\" valign=\"bottom\" style=\"padding-left:0px\">\n";
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
				if($i<=$puntuacio) print "\t\t\t\t\t\t\t\t<img src=\"star.gif\">\n";
				else print "\t\t\t\t\t\t\t\t<img src=\"stare.gif\">\n";
			}
			print "\t\t\t\t\t\t\t\t&nbsp;$vots ratings";
                        print "\t\t\t\t\t\t\t</div>\n";

			if($id_categoria_contingut==WALLPAPER)
			{
                	        print "\t\t\t\t\t\t\t<div>\n";
	                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">Views:</span> $visites\n";
        	                print "\t\t\t\t\t\t\t</div>\n";
			}

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">User:</span> <b>$login</b>\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t</td>\n";

                        print "\t\t\t\t\t</tr>\n";


			/* Filera amb altres continguts de l'usuari */
			//$this->incrustar_altres_continguts_usuari($id_user, $id_mm);

                        print "\t\t\t\t</tbody>\n";
                        print "\t\t\t</table>\n";
                        print "\t\t</div>\n";
                }
                print "\t</div\">\n";
                print "</div\">\n";
//              print "</tr>\n";
//              print "</table>\n";
        }

        function mostrar_graella_continguts($continguts, $categoria, $ih='')
        {
		$columnes = 5;
                $i=0;
                print "<table align=\"left\" width=\"100%\" border=0 cellspacing=\"10%\">\n";
                foreach($continguts as $contingut)
                {
                        if($i%$columnes==0) print "<tr>\n";
                        $codi_contingut = $contingut['codi_contingut'];
                        $data_insert = $contingut['data_insert'];

                        print "\t<td align=\"center\"><div id=\"foto\"><a target=\"_top\" href=\"index.php?c=$codi_contingut"."&ca=$categoria&ih=$ih#top\"><img src=\"pr.php?c=$codi_contingut\" border=2 style=\"width:85;height:85;\"></br></br><b>$codi_contingut</b> <small>($data_insert)</small></a></div></td>\n";
                        if($i%$columnes==$columnes-1) print "</tr>\n";
                        $i++;
                }
                if($i%$columnes!=$columnes) print "</tr>\n";
                print "</table>&nbsp;<hr/>&nbsp;\n";
        }

        function microtime_float()
        {
                list($usec, $sec) = explode(" ", microtime());
                return ((float)$usec + (float)$sec);
        }

	function acolorir_text($text)
	{
		$color = "#158BD4";
		$caracters = array('+','-','*','(',')','?','!', '&');
		foreach($caracters as $caracter)
			$text = str_replace($caracter, "<font color=$color>$caracter</font>", $text);
		return $text;
	}

        function mostrar_header_categoria($categoria, $pag, $total_time, $num_resultats, $total_continguts, $cerca='', $vista='l', $id_categoria_contingut=WALLPAPER)
        {
		switch($id_categoria_contingut)
		{
			case WALLPAPER:		$nom_tipus = 'Photos'; $lletra='a'; break;
			case VIDEO:		$nom_tipus = 'Videos';  $lletra = 'o'; break;
			case AUDIO:		$nom_tipus = 'Recordings'; $lletra = 'a'; break;
			case ANIMATION:		$nom_tipus = 'Animations'; $lletra = 'a'; break;
			default:		die();
		}

		$fitxer=$_SERVER['SCRIPT_NAME'];
		$param_valor="";
		foreach($_GET as $param => $valor)
			if($param!='v') $param_valor.="&$param=$valor";

		if($num_resultats)
		{
			$html_vistes = "\t<div class=\"smallText\" style=\"float: right; padding-top: 6px;\">";
			if($vista=='m') $html_vistes .= "\t\t<a href=\"$fitxer"."?v=l$param_valor\">\n";
			$html_vistes .= "<img border=0 style=\"width:41;height:15;\" src=\"".$this->www_link."/v_$vista"."_llista.png\" title=\"Detailed view\">";
			if($vista=='m') $html_vistes .= "</a>&nbsp;";
			if($vista=='l') $html_vistes .= "&nbsp;<a href=\"$fitxer"."?v=m$param_valor\">";
			$html_vistes .= "<img border=0 style=\"width:41;height:15;\" src=\"".$this->www_link."/v_$vista"."_matriu.png\" title=\"Thumbnail view\">";
			if($vista=='l') $html_vistes .= "</a>";
			$html_vistes .= "\t</div>\n";
		}

		if($cerca!='')
		{
	                print "<div id=\"sectionHeader\" class=\"searchColor\">\n";
			print "\t<div class=\"nameSearch\">Search</div>\n";
			print $html_vistes;
			print "\t</div>\n";

			print "<div id=\"sectionHeader\" class=\"searchColor\" style=\"background: #EEE;\">\n";
			print "\t\t<div class=\"name\" style=\"width:400px\"><span class=\"title\">$nom_tipus <span class=\"normalText\">found for </span>'$cerca'</span></div>\n";
			if($num_resultats) print "\t<div class=\"smallText\" style=\"float: right;\"> Results <b>".(($pag-1)*CONTENTS_PER_PAGE + 1)."-".((($pag-1)*CONTENTS_PER_PAGE) + $num_resultats)."</b> of <b>$total_continguts</b> (".round($total_time, 4)." s) </div>\n";
	                print "\t</div>\n";
		}
		else
		{
                        print "<div id=\"sectionHeader\" class=\"searchColor\">\n";

			$info = $this->obtenir_info_categoria($categoria, $id_categoria_contingut);
			$nom = utf8_encode($info['nom']);
			$nom = $this->acolorir_text($nom);
			$descripcio = utf8_encode($info['descripcio']);

			print "\t<div class=\"name\">$nom</div>\n";
			print $html_vistes;

//			print "\t\t<span class=\"title\">$nom_tipus <span class=\"normalText\">encontrad".$lletra."s para </span>'cerca'</span>\n";
			print "\t</div>\n";

                        print "<div id=\"sectionHeader\" class=\"searchColor\" style=\"background: #EEE;\">\n";
			print "\t<div class=\"name\" style=\"width:400px\">&nbsp;</div>\n";
                        if($num_resultats) print "\t<div class=\"smallText\" style=\"float: right;\"> Results <b>".(($pag-1)*CONTENTS_PER_PAGE + 1)."-".((($pag-1)*CONTENTS_PER_PAGE) + $num_resultats)."</b> of <b>$total_continguts</b> (".round($total_time, 4)." s) </div>\n";
			print "\t</div>\n";
		}
	}

	function incrustar_altres_continguts_usuari($id_user, $id_mm)
	{
		$more_contents = $this->get_more_user_contents($id_user, $id_mm);
		if(count($more_contents))
		{
			print "<tr>\n";
			print "\t<td colspan=3>\n";

        	        print "\t\t\t\t\t\t<div style=\"border:1px solid #CCCCCC; padding:0px 0pt 0pt; background-image:url(http://www.mobiturmix.com/dtmb.jpg); background-position:left bottom; background-repeat:repeat-x; margin-top:4px; height:60px;\">\n";

	                print "<table border=0 style=\"border-collapse:collapse;margin-top:2px;\" cellspacing=0 cellpadding=0>\n";
                	print "<tr>\n";

			foreach($more_contents as $content)
			{
				$codi_contingut = $content['codi_contingut'];
				$id_categoria_contingut = $content['id_categoria_contingut'];
				switch($id_categoria_contingut)
				{
					case VIDEO:	$domini = "video.mobiturmix.com";
							$classe = "pmini";
							$tipus_preview = 2;
							break;
					case WALLPAPER:	$domini = "photo.mobiturmix.com";
							$classe = "pmini";
							$tipus_preview = 2;
							break;
					case AUDIO:	$domini = "audio.mobiturmix.com"; break;
					case ANIMATION:	$domini = "anima.mobiturmix.com";
							$classe = "pmini";
							$tipus_preview = 2;
							break;
				}
	        	        print "<td valign=\"middle\" align=\"center\"><a id=\"$classe\" href=\"http://$domini/index.php?c=$codi_contingut&ih=&ca=0#top\" target=\"_top\"><img src=\"http://www.mobiturmix.com/pr.php?c=$codi_contingut&f=$tipus_preview\"></a></td>\n";
			}

                	print "</tr>\n";
        	        print "</table>";

	                print "\t\t\t\t\t\t</div>\n";

			print "\t</td>\n";
			print "</tr>\n";
		}
	}

        function mostrar_categoria($id_categoria_contingut, $categoria=MES_RECENT, $cerca='', $pag=1, $vista='l', $ih='')
        {
                print "<html>\n";
                print "<head>\n";

print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_VIDEO_ES."\n";
print META_DESCRIPTION_VIDEO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_VIDEOTURMIX."\n";

                print "<link rel=\"stylesheet\" href=\"".$this->www_link."/css/cat_style.css\" type=\"text/css\" />\n";
                print "<link rel=\"stylesheet\" href=\"".$this->www_link."/css/cat_base.css\" type=\"text/css\" />\n";
                print "<link rel=\"stylesheet\" href=\"".$this->www_link."/css/cat_panells.css\" type=\"text/css\" />\n";

                print "</head>\n";
                print "<body background=\"".BACKGROUND_CATEGORIES."\" bgproperties=\"fixed\" style=\"background-attachment: fixed; background-repeat: no-repeat;\">\n";

                $init_time = $this->microtime_float();
                $resultats = $this->obtenir_continguts_categoria($id_categoria_contingut, $categoria, $cerca, CONTENTS_PER_PAGE, $pag);
                $total_resultats = $this->obtenir_continguts_categoria($id_categoria_contingut, $categoria, $cerca, null, null);
                $total_continguts = $total_resultats->numRows();
                $continguts = array();

                $end_time = $this->microtime_float();
                $total_time = $end_time - $init_time;

                if($resultats==null) die();
                else
                {
                        $num_resultats = $resultats->numRows();
			$this->mostrar_header_categoria($categoria, $pag, $total_time, $num_resultats, $total_continguts, $cerca, $vista, $id_categoria_contingut);

                        if($num_resultats)
                        {
                                while ($foto = $resultats->fetchRow())
                                {
                                        $data_insert = $foto['data_insert'];
                                        $any = substr($data_insert, 0, 4);
                                        $mes = substr($data_insert, 5, 2);
                                        $dia = substr($data_insert, 8, 2);
                                        $contingut['data_insert'] = "$dia-$mes-$any";
                                        $contingut['id_mm'] = $foto['id_mm'];
                                        $contingut['codi_contingut'] = $foto['codi_contingut'];
                                        $contingut['descripcio'] = $foto['descripcio'];
					$nom = $foto['nom'];
					if(strlen($nom)>50) $nom = substr($nom, 0, 50)."...";
                                        $contingut['nom'] = $nom;
					$contingut['id_user'] = $foto['id_user'];
					$contingut['login'] = $foto['login'];
					$contingut['source'] = $foto['source'];
                                        $contingut['durada'] = $foto['durada'];
                                        $contingut['visites'] = $foto['visites'];
					$contingut['puntuacio'] = round($foto['puntuacio']);
					$contingut['vots'] = $foto['vots'];
                                        array_push($continguts, $contingut);

                                }

                                switch($vista)
                                {
                                        case 'l':	$this->mostrar_llista_continguts($continguts, $categoria, $id_categoria_contingut, $ih); break;
                                        default:	$this->mostrar_graella_continguts($continguts, $categoria, $ih); break;
                                }
                        }
                        else if($cerca=='') print "</br></br><img src=\"".MSG_CATEGORIA_BUIDA."\">";
                        else print "</br></br><img src=\"".MSG_BUSQUEDA_SENSE_RESULTATS."\">";
                        $this->inserir_paginador(CONTENTS_PER_PAGE, PAGES_ON_PAGINATOR, $pag, $total_continguts, $cerca, $categoria);
                }
                print "</body>\n";
                print "</html>\n";
        }

        function inserir_paginador($contents_per_page, $pages_on_paginator, $pag, $total_continguts, $cerca='', $categoria=MES_RECENT)
        {
                $total_pagines = ceil($total_continguts / $contents_per_page);
                $pag_ini = ((ceil($pag / $pages_on_paginator)-1) * $pages_on_paginator) + 1;
                $pag_fi = $pag_ini + $pages_on_paginator - 1;
                if($pag_fi>$total_pagines) $pag_fi = $total_pagines;

/*                print "<style type=\"text/css\">\n";
                print "<!--\n";
                print "a:link {\n";
                print "color: #8D8D8D;\n";
                print "text-decoration: none;\n";
                print "}\n";
                print "a:visited {\n";
                print "text-decoration: none;\n";
                print "color: #8D8D8D;\n";
                print "}\n";
                print "a:hover {\n";
                print "text-decoration: underline;\n";
                print "color: #8D8D8D;\n";
                print "}\n";
                print "a:active {\n";
                print "text-decoration: none;\n";
                print "color: #8D8D8D;\n";
                print "}\n";
                print "-->\n";
                print "</style>\n";*/

                if($pag_ini<$pag_fi)
                {
                        print "<script>\n";
                        print "\tfunction go(p)\n";
                        print "{\n";
	                $param_valor="";
        	        foreach($_GET as $param => $valor)
                	        if($param!='p') $param_valor.="&$param=$valor";

                        print "\twindow.location='ftc.php?p='+p+'$param_valor';\n";
                        print "}\n";
                        print "</script>\n";

                        print "<table border=0 align=\"center\" style=\"font-family:verdana,sans-serif;font-size:10pt;color:#18A0F6;\" cellpadding=\"3\" >\n";                        print "\t<tr>\n";
                        print "\t</tr>\n";
                        print "\t<td><img src=\"t.gif\"></td>\n";
                        for($p=$pag_ini; $p<=$pag_fi; $p++)
                                if($p==$pag) print "\t<td><img src=\"n.gif\"></td>\n";
                                else print "\t<td><a href=\"javascript:go($p);\"><img style=\"width:11;height:26\" border=0 src=\"u.gif\"></a></td>\n";

                        print "\t<td><img src=\"rmix.gif\"></td>\n";
                        print "\t</tr>\n";
                        print "\t<tr>\n";
                        print "\t<td></td>\n";
                        for($p=$pag_ini; $p<=$pag_fi; $p++)
                                if($p==$pag) print "\t<td><b>$p</b></td>\n";
                                else print "\t<td><a href=\"javascript:go($p);\">$p</a></td>\n";

                        print "\t<td></td>\n";
                        print "\t</tr>\n";
                        print "</table>\n";
                }
        }

        function incrementar_visita_contingut($codi_contingut)
        {
                $con_bd = new conexio_bd();
                $sql = "UPDATE mm SET visites = visites + 1, data_insert = data_insert WHERE codi_contingut = '$codi_contingut' LIMIT 1;";
                $res = $con_bd->sql_query($sql);
        }

        function mostrar_llista_continguts_frontpage($id_categoria_contingut=WALLPAPER)
	{
		$categoria = 0; // Los + recientes

		switch($id_categoria_contingut)
		{
			case VIDEO:     $nom_tipus = 'Los vídeos'; $tipus = "Sube tu vídeo"; $domini="video"; $class_in="video"; $class="vimg88"; $total=15; $files=3; break;
			case AUDIO:	$nom_tipus = 'Los audios'; $tipus = "Sube tu mp3 ya!"; $domini="audio"; $class_in="image"; $class="vimg66"; $total=16; $files=3; break;
			case ANIMATION:	$nom_tipus = 'Las animaciones'; $tipus = "Crea tu animación ya!"; $class_in="image"; $domini="anima"; $class="vimg66"; $total=16; $files=3; break;
			default:        $nom_tipus = 'Los fondos'; $tipus = "Sube tu foto"; $class_in="image"; $domini="photo"; $class="vimg66"; $total=32; $files=4; $destacats = array('aqae','ouei','auue','aewa'); break;
		}

                $resultats = $this->obtenir_continguts_categoria($id_categoria_contingut, $categoria, '', $total, 1);
                $continguts = array();

                if($resultats==null) return;
                else
                {
                        if($num_resultats = $resultats->numRows())
                        {
                                while ($item = $resultats->fetchRow())
                                {
                                        $data_insert = $item['data_insert'];
                                        $any = substr($data_insert, 0, 4);
                                        $mes = substr($data_insert, 5, 2);
                                        $dia = substr($data_insert, 8, 2);
                                        $contingut['data_insert'] = "$dia-$mes-$any";
                                        $contingut['id_mm'] = $item['id_mm'];
                                        $contingut['codi_contingut'] = $item['codi_contingut'];
                                        $contingut['descripcio'] = $item['descripcio'];
                                        $nom = $item['nom'];
                                        if(strlen($nom)>50) $nom = substr($nom, 0, 50)."...";
                                        $contingut['nom'] = $nom;
                                        $contingut['durada'] = $item['durada'];
                                        $contingut['visites'] = $item['visites'];
                                        $contingut['puntuacio'] = round($item['puntuacio']);
                                        $contingut['vots'] = $item['vots'];
                                        array_push($continguts, $contingut);
                                }
			}

			print "<table border=0 width=\"100%\">\n";
			print "<tr>\n";
			if($id_categoria_contingut==WALLPAPER) print " <td colspan=".(($total/$files)+1).">\n";
			else if($id_categoria_contingut==VIDEO) print " <td colspan=".(($total/$files)+3).">\n";
			else print " <td colspan=".($total/$files).">\n";
			print "  <div id=\"sectionHeader\" class=\"searchColor\" style=\"background-color:#eeeeee;\">\n";
			print "    <div class=\"name\"> ".$nom_tipus." <font color=#158BD4>+</font> recent</div>\n";
			print "    <div class=\"name\" style=\"float: right;width:300px;\" align=\"right\">\n";
			print "      <a href=\"http://$domini.mobiturmix.com/upload.php\">".$tipus."</a> \n";
			print "    </div>\n";
			print "  </div>\n";
			print "</td>\n";
			print "</tr>\n";

			$f=0;
			$espai_phone = true;
			$espai_player = true;
			foreach($continguts as $contingut)
			{
				if(!$f)	print "<tr>\n";
				if($espai_phone && $id_categoria_contingut == WALLPAPER)
				{
					$espai_phone = false;
					print "<td rowspan=\"$files\">\n";
					print " <a href=\"http://$domini.mobiturmix.com/ft.php?c=aqae\">\n";
					print "  <img src=\"http://photo.mobiturmix.com/pv.php?c=aqae&ih=581\">\n";
					print " </a>\n";
					print "</td>\n";
				}
				$codi_contingut = $contingut['codi_contingut'];
				print "<td align=\"center\">\n";
				print " <div class=\"QLContainer\">\n";
				print "  <div class=\"".$class_in."FrontIconWrapperOuter\">\n";
                                print "   <div class=\"".$class_in."FrontIconWrapperInner\">\n";
                                print "    <a href=\"http://$domini.mobiturmix.com/ft.php?c=".$codi_contingut."\">\n";
                                print "     <img class=\"$class\" src=\"http://".$domini.".mobiturmix.com/pr.php?c=".$codi_contingut."&f=1\" border=2>\n";
				print "     </br>\n";
                                print "    </a>\n";
                                print "   </div>\n";
                                print "  </div>\n";
                                print " </div>\n";
				print "</td>\n";
				$f++;
				if($espai_player && $id_categoria_contingut==VIDEO && ($f>=($total/$files)))
				{
					$espai_player = false;
					print "<td rowspan=\"$files\" align=\"right\">\n";
					print "<script src=\"js/swfobject.js\" type=\"text/javascript\"></script>\n";
					print "<div id=\"player1\">\n";
					print " <a href=\"http://www.macromedia.com/go/getflashplayer\">Get the Flash Player</a> to see this player.</div>\n";
					print "<script type=\"text/javascript\">\n";
					print "	var s1 = new SWFObject(\"flvplayer.swf\",\"single\",\"270\",\"200\",\"7\");\n";
					print "	s1.addParam(\"allowfullscreen\",\"true\");\n";
					print "	s1.addVariable(\"file\",\"http://video.mobiturmix.com/tata.flv\");\n";
					print "	s1.addVariable(\"image\",\"tata.jpg\");\n";
					print "	s1.write(\"player1\");\n";
					print "	</script>\n";
					print "</td>\n";
				}
				if($f>=($total/$files)) { print "</tr>\n"; $f=0; }
			}
			print "</table><br/>\n";

/*			if($id_categoria_contingut==WALLPAPER)
			{
				
				$handsets = array('N70'=>581, 'Nokia 6600'=>88, 'Sony Ericsson K300'=>506);
				print "<table border=0 width=\"100%\">\n";
				print "<tr>\n";
				foreach($destacats as $destacat)
				{
					$ih = array_pop($handsets);
					print "<td>\n";
					print "<img src=\"http://photo.mobiturmix.com/pv.php?c=$destacat&ih=$ih\">\n";
					print "</td>\n";
				}
				print "</tr>\n";
				print "</table>";
			}*/
		}
	}

	function incrustar_barra_eines_contingut($link, $title='')
	{
		print "\t\t\t\t\t\t<div style=\"border:1px solid #CCCCCC; padding:0px 0pt 0pt; background-image:url(http://www.mobiturmix.com/dtmb.jpg); background-position:left bottom; background-repeat:repeat-x;margin-bottom:2px;height:18px;\">\n";

		print "<table border=0 width=\"100%\" style=\"border-collapse:collapse;\" cellspacing=0 cellpadding=0>\n";
		print "<tr>\n";
		print "<td align=\"center\"><a id=\"meneame\" title=\"Share this content with 'meneame'\" target=\"_blank\" href=\"http://meneame.net/submit.php?url=$link\"><img src=\"/pix.gif\"></a></td>\n";
                print "<td align=\"center\"><a id=\"digg\" title=\"Share this content with 'Digg'\" target=\"_blank\" href=\"http://digg.com/submit?phase=2&url=$link&title=".(urlencode($title))."\"><img src=\"/pix.gif\"></a></td>\n";
                print "<td align=\"center\"><a id=\"blinklist\" title=\"Share this content with 'Blinklist'\" target=\"_blank\" href=\"http://www.blinklist.com/index.php?Action=Blink/addblink.php&Url=$link&Title=".(urlencode($title))."\"><img src=\"/pix.gif\"></a></td>\n";
                print "<td align=\"center\"><a id=\"technorati\" title=\"Share this content with 'Technorati'\" target=\"_blank\" href=\"http://technorati.com/faves?add=$link\"><img src=\"/pix.gif\"></a></td>\n";
                print "<td align=\"center\"><a id=\"delicious\" title=\"Share this content with 'Delicious'\" target=\"_blank\" href=\"http://del.icio.us/post?v=4&noui&jump=close&url=$link&title=".(urlencode($title))."\"><img src=\"/pix.gif\"></a></td>\n";
		print "</tr>\n";
		print "</table>";

		print "\t\t\t\t\t\t</div>\n";
	}
}
?>
