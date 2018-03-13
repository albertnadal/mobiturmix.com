<?
require('../include/crop_canvas/class.cropinterface.php');
require('classe_interficie_smsturmix.php');
require('constants_smsturmix.php');
require_once("../DB/conexio_bd.php");
require_once("classe_sessio_usuari.php");
require_once("classe_comentaris.php");

define("NO_JAVASCRIPT",		0);
define("SCRIPTACULOUS",		1);
define("DOJO",			2);
define("SCRIPTACULOUS_DOJO",	3);

define("SERVER_NAME",		$_SERVER["SERVER_NAME"]);


class interficie_wallpaper_studio extends interficie_smsturmix
{
        function interficie_wallpaper_studio ()
        {

        }

	function obtenir_nom_handset($ih)
	{
                $con_bd = new conexio_bd();
		$sql = "select	mh.marca, h.model
			from	handset h,
				marca_handset mh
			where	h.id_handset = $ih
				and mh.id_marca_handset = h.id_marca_handset";
                $res = $con_bd->sql_query($sql);
		if(!$res->numRows()) die();
		$row = $res->fetchRow();
		return array('marca' => $row['marca'], 'model' => $row['model']);
	}

	function obtenir_continguts_recomanats($max=20)
	{
                $con_bd = new conexio_bd();
                $sql = "select	codi_contingut
                        from	mm
                        where	id_categoria_contingut=".WALLPAPER."
				and public='Y'
				and estat='APROVAT' ";
	//		group by puntuacio, vots, visites
$sql .= "			order by puntuacio desc, vots desc, visites desc, data_insert asc ";
$sql .= "			limit 0, ".($max-1);
//print $sql;

                $res = $con_bd->sql_query($sql);
                $row = $res->fetchRow();
                if(!$res->numRows()) die();
                return $res;
	}

	function obtenir_id_marca_handset($ih)
	{
                $con_bd = new conexio_bd();
		$sql = "select id_marca_handset
			from handset
			where id_handset = $ih";
                $res = $con_bd->sql_query($sql);
		$row = $res->fetchRow();
		if(!$res->numRows()) die();
		return $row['id_marca_handset'];
	}

	function inserir_javascript_urlencode()
	{
?>
function URLEncode(plaintext)
{
        // The Javascript escape and unescape functions do not correspond
        // with what browsers actually do...
        var SAFECHARS = "0123456789" +                                  // Numeric
                                        "ABCDEFGHIJKLMNOPQRSTUVWXYZ" +  // Alphabetic
                                        "abcdefghijklmnopqrstuvwxyz" +
                                        "-_.!~*'()";                                    // RFC2396 Mark characters
        var HEX = "0123456789ABCDEF";
        var encoded = "";
        for (var i = 0; i < plaintext.length; i++ ) {
                var ch = plaintext.charAt(i);
            if (ch == " ") {
                    encoded += "+";                             // x-www-urlencoded, rather than %20
                } else if (SAFECHARS.indexOf(ch) != -1) {
                    encoded += ch;
                } else {
                    var charCode = ch.charCodeAt(0);
                        if (charCode > 255) {
                            alert( "Unicode Character '"
                        + ch
                        + "' cannot be encoded using standard URL encoding.\n" +
                                          "(URL encoding only supports 8-bit characters.)\n" +
                                                  "A space (+) will be substituted." );
                                encoded += "+";
                        } else {
                                encoded += "%";
                                encoded += HEX.charAt((charCode >> 4) & 0xF);
                                encoded += HEX.charAt(charCode & 0xF);
                        }
                }
        }
        return encoded;
}
<?php
	}

	function obtenir_continguts_categoria($id_mm_categoria, $cerca, $max=16, $pag=1)
	{
		$ini = ($pag-1)*$max;
		if(($max==null)||($pag==null)) $limit = ""; else $limit = " limit $ini, $max"; 

                $con_bd = new conexio_bd();
		switch($id_mm_categoria)
		{
			case MES_RECENT:	$tokens = explode(" ", $cerca);
                                                if(count($tokens))
                                                {
                                                        $sql = "select mm.id_mm, mm.codi_contingut, mm.data_insert
                                                                from mm mm
                                                                where	id_categoria_contingut = ".WALLPAPER."
									and mm.estat = 'APROVAT'
									and mm.public = 'Y'
									and (";
                                                        $or = "";
                                                        foreach($tokens as $token)
                                                        {
                                                                $sql .= " $or mm.descripcio like '%$token%' ";
                                                                $or = "or";
                                                        }
                                                        $sql .= ") order by mm.data_insert desc
                                                                $limit";
                                                }
                                                else
                                                {
                                                 
							$sql = "select id_mm, codi_contingut, data_insert
								from mm
								where id_categoria_contingut = ".WALLPAPER."
									and mm.estat = 'APROVAT'
									and mm.public = 'Y'
								order by data_insert desc
								$limit";
						}
						break;

			default:		$sql = "select mm.id_mm, mm.codi_contingut, mm.data_insert
							from mm mm, mm_categoria_mm mcm
							where mm.id_mm = mcm.id_mm
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

	function mostrar_categoria_fototeca($categoria=MES_RECENT, $cerca='', $pag=1)
	{
		print "<html>\n";
		print "<head></head>\n";
		print "<body background=\"f.gif\" bgproperties=\"fixed\" style=\"background-attachment: fixed; background-repeat: no-repeat;\">\n";

		print "<STYLE TYPE=\"text/css\" MEDIA=screen>\n";
		//print "<!--\n";
		print '

#foto {
    min-width: 0px;
    padding: 0px;
    margin: 10px 5px 10px 5px;
    font-size: 12px;
    line-height: 11px;
    color: #19a0f6 !important;
}

#foto a {
    color: inherit !important;
    text-decoration: none;
}

#foto small {
    color: inherit !important;
    text-decoration: none;
    color: #3d3c3a !important;
}

#foto a:hover {
    color: #026fb4 !important;
    text-decoration: none;
}
';

		print "</STYLE>\n";

		print "<table border=\"0\">\n";
		print "<tr>\n";
		print "<td width=\"15%\"></td>\n";
		print "<td>\n";
		if($cerca!='') print "<img src=\"".MSG_RESULTATS_BUSQUEDA."\">";
		else
		{
			switch($categoria)
			{
				case CATEGORIA_AMOR:		print "<img src=\"".CAT_AMOR_IMAGE."\">"; break;
				case CATEGORIA_DEPORTE:		print "<img src=\"".CAT_DEPORTES_IMAGE."\">"; break;
				case CATEGORIA_MUSICA:		print "<img src=\"".CAT_MUSICA_IMAGE."\">"; break;
				case CATEGORIA_AMIGOS:		print "<img src=\"".CAT_AMIGOS_IMAGE."\">"; break;
				case CATEGORIA_FAMILIA:		print "<img src=\"".CAT_FAMILIA_IMAGE."\">"; break;
				case CATEGORIA_PAISAJES:	print "<img src=\"".CAT_PAISAJES_IMAGE."\">"; break;
				case CATEGORIA_VIAJES:		print "<img src=\"".CAT_VIAJES_IMAGE."\">"; break;
				case CATEGORIA_DIVERTIDA:	print "<img src=\"".CAT_DIVERTIDAS_IMAGE."\">"; break;
				case CATEGORIA_EROTICA:		print "<img src=\"".CAT_EROTICAS_IMAGE."\">"; break;
				case MES_DESCARREGAT:		print "<img src=\"".CAT_MES_DESCARREGAT_IMAGE."\">"; break;
				case MES_RECENT:		print "<img src=\"".CAT_MES_RECENT_IMAGE."\">"; break;
			}
		}
		print "</td>\n";
		print "</table>\n";

		$columnes = 5;
		$resultats = $this->obtenir_continguts_categoria($categoria, $cerca, CONTENTS_PER_PAGE, $pag);
		$total_resultats = $this->obtenir_continguts_categoria($categoria, $cerca, null, null);
		$total_continguts = $total_resultats->numRows();
		$continguts = array();

		if($resultats==null) die('Error');
		else
		{
			if($num_continguts = $resultats->numRows())
			{
				while ($foto = $resultats->fetchRow())
				{
					$contingut['id_mm'] = $foto['id_mm'];
					$contingut['codi_contingut'] = $foto['codi_contingut'];
					$data_insert = $foto['data_insert'];
					$any = substr($data_insert, 0, 4);
					$mes = substr($data_insert, 5, 2);
					$dia = substr($data_insert, 8, 2);
					$contingut['data_insert'] = "$dia-$mes-$any";
					array_push($continguts, $contingut);
				}
				$i=0;
				print "<table align=\"center\" border=0 cellspacing=\"10%\">\n";
				foreach($continguts as $contingut)
				{
					if($i%$columnes==0) print "<tr>\n";
					$codi_contingut = $contingut['codi_contingut'];
					$data_insert = $contingut['data_insert'];

					print "\t<td align=\"center\"><div id=\"foto\"><a href=\"info.php?c=$codi_contingut"."&ca=$categoria&p=$pag&s=$cerca\"><img src=\"pr.php?c=$codi_contingut\" border=2></br></br><b>$codi_contingut</b> <small>($data_insert)</small></a></div></td>\n";
					if($i%$columnes==$columnes-1) print "</tr>\n<!--<tr><td colspan=4><hr size=1px></td></tr>-->\n";
					$i++;
				}
				if($i%$columnes!=$columnes) print "</tr>\n";
				print "</table>\n";
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

		print "<style type=\"text/css\">\n";
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
		print "</style>\n";

		if($pag_ini<$pag_fi)
		{
			print "<script>\n";
			print "\tfunction go(p)\n";
			print "{\n";
			print "\twindow.location='ftc.php?p='+p+'&s=$cerca&c=$categoria';\n";
			print "}\n";
			print "</script>\n";
	
			print "<table border=0 align=\"center\" style=\"font-family:verdana,sans-serif;font-size:10pt;color:#18A0F6;\" cellpadding=\"3\" >\n";
			print "\t<tr>\n";
			print "\t</tr>\n";
			print "\t<td><img src=\"t.gif\"></td>\n";
			for($p=$pag_ini; $p<=$pag_fi; $p++)
				if($p==$pag) print "\t<td><img src=\"n.gif\"></td>\n";
				else print "\t<td><a href=\"javascript:go($p);\"><img border=0 src=\"u.gif\"></a></td>\n";
	
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

	function obtenir_info_foto($codi_contingut)
	{
                $con_bd = new conexio_bd();
		$sql = "select id_mm, codi_contingut, descripcio, vots, puntuacio, visites, nom
			from mm
			where codi_contingut='$codi_contingut'";
                $res = $con_bd->sql_query($sql);
		return $res;
	}

	function usuari_ha_votat_contingut($codi_contingut)
	{
		$ip = (getenv(HTTP_X_FORWARDED_FOR)) ?  getenv(HTTP_X_FORWARDED_FOR) :  getenv(REMOTE_ADDR);
                $con_bd = new conexio_bd();
                $sql = "select	count(uv.id_usuari_vot)
                        from	usuari_vot uv, mm mm
                        where	mm.codi_contingut = '$codi_contingut'
				and uv.id_mm = mm.id_mm
				and uv.ip = '$ip'";
                $res = $con_bd->sql_query($sql);
                if(!$res->numRows()) die();
                $row = $res->fetchRow();
		return $row['count(uv.id_usuari_vot)']>0;
	}

	function inserir_javascript_puntuador($codi_contingut)
	{
		$ha_votat = $this->usuari_ha_votat_contingut($codi_contingut);
		print "<script>\n";
		print "var ha_votat=";
		if($ha_votat) print "true;\n"; else print "false;\n";
		print "function set(n)\n";
		print "{\n";
		print " if(ha_votat==false)\n";
		print "	{\n";
		print "		doSimpleXMLHttpRequest(\"async.php\", {c: \"$codi_contingut\", v: n, op: 'vot'});\n";
		print "		alert('Thank you for rating!');\n";
                print "         over(n,'block');";
		print "		ha_votat=true;\n";
		print "	}\n";
		print "	else	alert('You cannot rate the same content one more time');\n";
		print "}\n";
		print "function gebi(id) { return document.getElementById(id); }\n";
		print "function over(n,d)\n";
		print "{\n";
		print "	if(ha_votat==false)\n";
		print "	{\n";
		print "		for(i=1; i<=n; i++)\n";
		print "		{\n";
		print "			e = gebi(''+i);\n";
		print "			e.style.display=d;\n";
		print "		}\n";
		print "		for(i=n+1; i<=5; i++)\n";
		print "		{\n";
		print "			e = gebi('d'+i);\n";
		print "			e.style.display=d;\n";
		print "		}\n";
		print "	}\n";
		print "}\n";
		print "</script>\n";
	}

        function inserir_puntuador($puntuacio, $num_vots)
        {
                print "<table align=\"center\" id=\"t\" height=\"20px\" width=\"250px\" border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse:collapse;\">\n";
                print "<tr>\n";
                for($i=1; $i<=5; $i++)
                {
                        if($i<=$puntuacio) $estrella='sf.png'; else $estrella='se.png';
                        print "<td width=\"19\" style=\"cursor:pointer;\" background=\"http://www.mobiturmix.com/$estrella\" onmouseover=\"over($i,'block')\" onmouseout=\"over($i,'none')\" onclick=\"set($i)\">\n";
                        print " <img id=\"$i\" src=\"http://www.mobiturmix.com/sf.png\" style=\"display:none\">\n";
                        print " <img id=\"d$i\" src=\"http://www.mobiturmix.com/se.png\" style=\"display:none\">\n";
                        print "</td>\n";
                }
                print "<td valign=\"middle\" class=\"Estilo7\">&nbsp;<b>$num_vots</b>&nbsp;ratings</td>";
                print "</tr>\n";
                print "</table>\n";
        }

	function mostrar_info_foto($codi_contingut, $imh='', $ih='', $categoria='', $pag=1, $cerca='', $iih='')
	{
		if($iih=='') $iih = $ih;

		$this->incrementar_visita_contingut($codi_contingut);
		$info = $this->obtenir_info_foto($codi_contingut);
		$foto = $info->fetchRow();
		$id_mm = $foto['id_mm'];
		$num_vots = $foto['vots'];
		$puntuacio = round($foto['puntuacio']);
		$codi_contingut = $foto['codi_contingut'];
		$descripcio = $foto['descripcio'];
                $visites = $foto['visites'];
                $nom = $foto['nom'];
                $data_insert = $foto['data_insert'];
		if($categoria!='') $icona_volver = "&nbsp;<a href=\"ftc.php?c=$categoria&p=$pag&s=$cerca&ih=$iih\"><img style=\"width:90;height:90\" src=\"n8.gif\" border=0 alt=\"Back\" title=\"Back\"></a>\n";
		else $icona_volver = '';

		print "<html>\n";
		print "<head>\n";

		print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/Base.js\"></script>\n";
		print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/Async.js\"></script>\n";
		print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/Iter.js\"></script>\n";
		print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/mochikit/DOM.js\"></script>\n";
		print "<script type=\"text/javascript\" src=\"http://www.mobiturmix.com/js/girafatools/html_loader.js\"></script>\n";


print '
<style type="text/css">
<!--
.Estilo3 {
        font-size: 13px;
        font-family: Geneva, Arial, Helvetica, sans-serif;
        font-weight: bold;
}
.Estilo6 {font-size: 14px; font-family: Geneva, Arial, Helvetica, sans-serif;}
.Estilo7 {
        font-family: Geneva, Arial, Helvetica, sans-serif;
        font-size: 11px;
}
.Estilo8 {
        color: #052B7A;
        font-weight: bold;
        font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo10 {
        font-size: 15px;
        font-family: Geneva, Arial, Helvetica, sans-serif;
        color: #22D82F;
}
-->
</style>
';

                print "<link rel=\"stylesheet\" href=\"http://www.mobiturmix.com/css/cat_style.css\" type=\"text/css\" />\n";
                print "<link rel=\"stylesheet\" href=\"http://www.mobiturmix.com/css/cat_base.css\" type=\"text/css\" />\n";

		print "</head>\n";
		print BODY."\n";

		print "<a name=\"top\" />\n";

		$this->inserir_javascript_puntuador($codi_contingut);
		print "<script>\n";
		print "function show_comments(code, pag, area)\n";
		print "{\n";
		print "        e=document.getElementById(area);\n";
		print "        e.innerHTML='<center><h2><img src=\"http://www.mobiturmix.com/loading.gif\"> Please, wait while loading...</h2></center>';\n";
		print "        e.style.display='block';\n";
		print "        initHtmlLoader('http://photo.mobiturmix.com/ajax.php?do=get_comments_html&c='+code+'&pag='+pag, area, '');\n";
		print "}\n";
		print "</script>\n";

                print "<table align=\"center\" border=0 cellspacing=\"5%\">\n";
		print "<tr>\n";
		print "\t<td align=\"center\" valign=\"top\">\n";
                print "\t\t<img src=\"pv.php?c=$codi_contingut&ih=$ih\"></br></br>\n";
        print " <select name=\"imh\" style=\"width:130px;\" onchange=\"window.location='info.php?ca=$categoria"."&c=$codi_contingut&p=$pag&s=$cerca&imh='+this.options[this.selectedIndex].value;\">\n";
        $marques = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27);

	if(($imh=='')&&($ih!='')&&(is_numeric($ih))) $imh=$this->obtenir_id_marca_handset($ih);

        foreach($marques as $id_marca_handset)
        {
                $marca=$this->obtenir_nom_marca_handset($id_marca_handset);
                if($id_marca_handset==$imh) $selected="selected"; else $selected="";
                print "<option value=\"$id_marca_handset\" $selected>$marca\n";
        }
        print "</select></br>\n";

        print " <select name=\"ih\" style=\"width:130px;\" onchange=\"window.location='info.php?ca=$categoria"."&c=$codi_contingut&p=$pag&s=$cerca&iih=$iih&imh=$imh&ih='+this.options[this.selectedIndex].value;\">\n";
        $handsets = $this->obtenir_handsets_marca_amb_preview($imh, WALLPAPER);
        foreach($handsets as $id_handset)
        {
                $model=$this->obtenir_model_handset($id_handset);
                if($id_handset==$ih) $selected="selected"; else $selected="";
                print "<option value=\"$id_handset\" $selected>$model\n";
        }
        print "</select>\n";

                print "\t</td>\n";
//		print "<td width=\"3%\"></td>\n";
                print "\t<td valign=\"top\" align=\"center\">\n";
		print "\t\t<table border=0><tr><td><img src=\"pvi.php?c=$codi_contingut\" border=1 align=\"center\"></td><td valign=\"bottom\">\n";
		print $icona_volver;
		print "</td></tr>\n";
		print "</table>\n";

/*		print "\t\t<table width=\"397\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
		print "\t\t  <tr>\n";
		print "\t\t    <td width=\"159\" height=\"34\" background=\"n6.gif\"></td>\n";
		print "\t\t    <td colspan=\"2\" align=\"left\" valign=\"middle\" background=\"n7.gif\">   <span class=\"txt1\">etm&nbsp;$codi_contingut</span></td>\n";
		print "\t\t  </tr>\n";
		print "\t\t  <tr>\n";
		print "\t\t    <td width=\"159\" height=\"74\" background=\"n4.gif\"></td>\n";
		print "\t\t    <td colspan=\"2\" valign=\"top\" background=\"n5.gif\"><div align=\"justify\"><span class=\"txt2\">$descripcio</span></div></td>\n";
		print "\t\t  </tr>\n";
		print "\t\t  <tr>\n";
		print "\t\t    <td width=\"159\" height=\"41\" background=\"n1.gif\"></td>\n";
		print "\t\t    <td width=\"140\" height=\"41\" align=\"center\" valign=\"middle\" nowrap=\"nowrap\" background=\"n2.gif\" class=\"txt3\">etm&nbsp;$codi_contingut</td>\n";
		print "\t\t    <td width=\"97\" height=\"41\" nowrap=\"nowrap\" background=\"n3.gif\"></td>\n";
		print "\t\t  </tr>\n";
		print "\t\t  <tr>\n";
		print "\t\t    <td height=\"51\" colspan=\"3\" background=\"n0.gif\"></td>\n";
		print "\t\t  </tr>\n";
		print "\t\t</table>\n";*/


                print "<table width=\"414\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" background=\"pas.png\" style=\"background-repeat: no-repeat;\">\n";
                print "  <tr>\n";
                print "    <td height=\"20\" colspan=\"3\"></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"20\" colspan=\"3\"><span style=\"padding-left:30px\" class=\"Estilo3\">$nom</span></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"65\" colspan=\"3\" valign=\"top\"><span style=\"padding-left:30px\" class=\"Estilo6\">\n";
                if(strlen($descripcio)>170) print (substr($descripcio, 0, 170))."...";
                else print $descripcio;
                print "    </span></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td width=\"150px\" colspan=\"2\" align=\"center\"><span class=\"Estilo7\">&nbsp;\n";
                $this->inserir_puntuador($puntuacio, $num_vots);
                print "    </span></td>\n";
                print "    <td height=\"19\" align=\"center\"><span class=\"Estilo7\">Views: <b>$visites</b> </span></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"14\" colspan=\"3\" nowrap=\"nowrap\"></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"17\" colspan=\"3\">\n";

                        print "\t\t\t\t\t\t<div style=\"background:#F6F6F6 none repeat scroll 0%; border:1px solid #CCCCCC; margin-bottom:3px;  padding:3px 20px 3px;\">\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<table border=0><tr><td valign=\"middle\"><a href=\"http://www.mobiturmix.com/faq/formas_de_descargar_el_contenido.php\" target=\"_top\"><img style=\"width:10;height:16\" src=\"ask.gif\" alt=\"How to...\" title=\"How to...\"></a></td><td valign=\"middle\"> <span class=\"label\">Download to your mobile!</span></td></tr></table>\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        if($ih=='') $onclick=" onclick=\"return upz();\"; "; else $onclick="";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">File:</span>&nbsp;<a href=\"http://get.mobiturmix.com/$codi_contingut/$ih\" $onclick><b>Download now!</b></a>\n";

                        $info = $this->obtenir_nom_handset($ih);
                        $marca = $info['marca'];
                        $model = $info['model'];
                        $marca_model = $marca." ".$model;
                        if(strlen($marca_model)>=18) $marca_model = substr($marca_model, 0, 18)."...";

                        if($ih!='') print "for $marca_model\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">WAP:</span>&nbsp;<a href=\"http://wap.mobiturmix.com/$codi_contingut/\" target=\"_top\"><b>wap.mobiturmix.com/$codi_contingut/</b></a>\n";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t\t<div>\n";
                        print "\t\t\t\t\t\t\t\t<span class=\"smblueText\">SMS:</span> Send the text <b>etm $codi_contingut</b> to 5767\n";
                        print "\t\t\t\t\t\t\t\t<select style=\"height:15px;width:95px;font-family:Verdana;font-size:9px\">";
                        print "\t\t\t\t\t\t\t\t\t<option value\"1\">Movistar (es)</option>";
                        print "\t\t\t\t\t\t\t\t\t<option value\"1\">Vodafone (es)</option>";
                        print "\t\t\t\t\t\t\t\t\t<option value\"1\">Orange (es)</option>";
                        print "\t\t\t\t\t\t\t\t</select>";
                        print "\t\t\t\t\t\t\t</div>\n";

                        print "\t\t\t\t\t\t</div>\n";


		print "   </td>\n";
                print "  </tr>\n";

		print "  <tr>\n";
		print "   <td colspan=3>\n";
		print "    <div id=\"comments\">\n";
//		print "     <center><h2><img src=\"http://photo.mobiturmix.com/loading.gif\"> Please, wait while loading...</h2></center>\n";
		print "    </div>\n";
//		$comentaris = new comentaris();
//		$comentaris->obtenir_panell_comentaris_contingut($id_mm, 1);
		print "   </td>\n";
		print "  </tr>\n";

                print "</table>\n";

		print "<script>\n";
		print " show_comments('$codi_contingut', 1, 'comments');\n";
		print "</script>\n";

		print "\t</td>\n";
		print "</tr>\n";
		print "</table>\n";
		print "</body>\n";
		print "</html>\n";
	}

/*	function incrustar_comentaris_contingut()
	{
		print "<tr>\n";
		print "\t<td colspan=3>\n";
                print "<div class=\"comments\">\n";

print "<div class=\"comments_heading\">\n";
print "	<h3>Comments</h3>\n";
print "	<span class=\"results\">10 comments of about 10</span>\n";
print "</div>\n";

print "<ul class=\"comments_results\" id=\"results-The Apple Store-ul\">\n";
print "	<li class=\"comment\">\n";
print "		<img class=\"thumb\" src=\"http://a248.e.akamai.net/f/248/2041/1d/store.apple.com/Catalog/US/Images/imac_20_45x40_070807.jpg\">\n";
print "		<h4><a href=\"http://store.apple.com/1-800-MY-APPLE/WebObjects/AppleStore?spart=MA876LL%2FA\">iMac 20-inch 2.0GHz Intel Core 2 Duo</a> (2 days ago)</h4>\n";
print "		<p class=\"desc\">The ultimate all-in-one desktop with a 20- or 24-inch glossy widescreen display. Configure yours.</p>\n";
print "	</li>\n";
print "	<li class=\"comment\">\n";
print "		<img class=\"thumb\" src=\"http://a248.e.akamai.net/f/248/2041/1d/store.apple.com/Catalog/US/Images/applecare_box_45x40.jpg\">\n";
print "		<h4><a href=\"http://store.apple.com/1-800-MY-APPLE/WebObjects/AppleStore?spart=\">AppleCare Protection Plan - iMac/eMac</a></h4>\n";
print "		<p class=\"desc\">The AppleCare Protection Plan extends your computer's 90 days of complimentary support and one-year warranty support.</p>\n";
print "	</li>\n";
print "	<li class=\"comment\">\n";
print "		<img class=\"thumb\" src=\"http://a248.e.akamai.net/f/248/2041/1d/store.apple.com/Catalog/US/Images/imac_20_45x40_070807.jpg\">\n";
print "		<h4><a href=\"http://store.apple.com/1-800-MY-APPLE/WebObjects/AppleStore?spart=MA877LL%2FA\">iMac 20-inch 2.4GHz Intel Core 2 Duo</a></h4>\n";
print "		<p class=\"desc\">The ultimate all-in-one desktop with a 20- or 24-inch glossy widescreen display. Configure yours.</p>\n";
print "	</li>\n";
print "</ul>\n";

print "<p class=\"comments_paginator\">\n";
print "	<span>View previous 5 comments |&nbsp;</span>\n";
print "	<a href=\"#\">View next 5 comments</a>\n";
print "</p>\n";

                print "</div>\n";
		print "</td>\n";
		print "</tr>\n";
	}*/

	function mostrar_panell_handsets($imh)
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="stylesheet" type="text/css" href="<?php print WWW_DOMAIN; ?>/css/template.css">
        <link rel="stylesheet" type="text/css" href="<?php print WWW_DOMAIN; ?>/css/color.css">
</head>

<?php
	print BODY;
	$marca = $this->obtenir_nom_marca_handset($imh);
?>
      <div id="compatible">
        <h2>Choose your <span class="bold"><?php print $marca; ?></span> model</h2><br>
        <table id="brands" border="0" cellspacing="0">
          <tbody>
<?php
        $res = $this->obtenir_handsets_marca($imh, WALLPAPER);
        $col=0;
        while($row=$res->fetchRow())
        {
                $id_handset = $row['id_handset'];
		$model = $row['model'];
                if(!$col++) print "<tr>\n";
                print "<td style=\"width:10px\">\n";
                print "<a href=\"index.php?ih=$id_handset\" target=\"_top\"><img src=\"".WWW_DOMAIN."/ph.php?ih=$id_handset\"><span>$model</span></a>\n";
                print "</td>\n";
                if($col>=5)
                {
                        print "</tr>\n";
                        $col = 0;
                }
        }
?>
         </tbody>
       </table>
      </div>
      <div class="clear"></div>
  <hr class="hide">
</body>
</html>

<?php
	}

	function mostrar_panell_marques()
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="stylesheet" type="text/css" href="<?php print WWW_DOMAIN; ?>/css/template.css">
        <link rel="stylesheet" type="text/css" href="<?php print WWW_DOMAIN; ?>/css/color.css">
</head>

<?php print BODY; ?>
      <div id="compatible">
        <h2>Choose your mobile brand</h2><br>
        <table id="brands" border="0" cellspacing="0">
          <tbody>
<?php
	$res = $this->obtenir_marques_handsets();
	$col=0;
	while($row=$res->fetchRow())
	{
		$id_marca_handset = $row['id_marca_handset'];
		$marca_handset = $row['marca'];
		if(!$col++) print "<tr>\n";
		print "<td style=\"width:10px\">\n";
		print "<a href=\"index.php?imh=$id_marca_handset\" target=\"_top\"><img src=\"".WWW_DOMAIN."/pmh.php?imh=$id_marca_handset\"><span>$marca_handset</span></a>\n";
		print "</td>\n";
		if($col>=4)
		{
			print "</tr>\n";
			$col = 0;
		}
	}
?>
	 </tbody>
       </table>
      </div>
      <div class="clear"></div>
  <hr class="hide">
</body>
</html>

<?php	
	}

	function inserir_contingut_recomanat($max=20, $ih='')
	{
		print "<table border=0 width=\"80%\" align=\"center\">\n";
		$recomanats = $this->obtenir_continguts_recomanats($max);

		$f=0;
		while($row = $recomanats->fetchRow())
		{
			$codi_contingut = $row[0];
			if($f==0) print "<tr>\n";
			else if($f==2) {  print "</tr>\n"; $f=0; }

			print "\t<td>\n";
			print "<a href=\"/info.php?c=$codi_contingut&ih=$ih&ca=0#top\" target=\"m\">\n";
			print "\t<img src=\"/pr.php?c=$codi_contingut&f=1\"></td>\n";
			print "</a>\n";
			$f++;
		}
		print "</table>\n";
	}

	function inserir_panell_handset_escollit($ih='')
	{
		print "				<div id=\"handset\">\n";

		if($ih=='')
		{
			print "						<img class=\"handset_img\" alt=\"\" src=\"".WWW_DOMAIN."/uh.png\">\n";
			$text_link = "Choose your mobile";
			$text = "mobile phone";
		}
		else
		{
			print "                                             <img class=\"handset_img\" alt=\"\" src=\"".WWW_DOMAIN."/ph.php?ih=$ih\">\n";
			$info = $this->obtenir_nom_handset($ih);
			$marca = $info['marca'];
			$model = $info['model'];
			$text_link = "Choose another mobile";
			$text = "<b>$marca $model</b>";
		}

		print "					<span class=\"handset_text_big\">\n";
		print "						<a href=\"index.php?imh=0\">$text_link</a></span>\n";
		print "					<span class=\"handset_text_small\">Check which photos are available<br>for your $text\n";
		print "					</span>\n";
		print "				</div>\n";
	}

	function mostrar_panell_fototeca($cerca, $op='', $imh, $ih='', $codi='')
	{
		if($ih!='')	$param_id_handset = "&ih=$ih";
		else		$param_id_handset = "";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?php print WWW_DOMAIN; ?>/css/template.css">
	<link rel="stylesheet" type="text/css" href="<?php print WWW_DOMAIN; ?>/css/color.css">

  <style type="text/css">
  /*<![CDATA[*/

#foot {
    min-width: 0px;
    padding: 0px;
//    margin: 30px 10px 10px -5px;
    font-size: 9px;
    line-height: 11px;
    color: #aaa !important;
}

#foot a {
    color: inherit !important;
    text-decoration: none;
}

#foot a:hover {
    color: #555 !important;
    text-decoration: none;
}

   td.photo {
	background: url(u_photo6.gif);
	cursor: pointer;
	cursor: pointer;
	vertical-align: middle;
	background-repeat: no-repeat;
}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima3.gif); cursor: hand; cursor: pointer;}

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  /*]]>*/
  </style>

  <style type="text/css">
/*
<!--
.Estilo5 {font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 16px;}
.Estilo5 a:link, a:visited{text-decoration:none;}
.Estilo9 {text-decoration:none; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; color: #2ab2ff; }
.Estilo9 a:link, a:visited{text-decoration:none; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 16px; color: #FF0033; }
.Estilo11 {font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; color: #8c8c8c; }
.Estilo11 a:link, a:visited{text-decoration:none; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; color: #8c8c8c; }
.Estilo13 {color: #8c8c8c}
-->
*/

td {
color:#000000;
font-family:tahoma,arial;
font-size:90%;
font-size-adjust:none;
font-style:normal;
font-variant:normal;
font-weight:normal;
line-height:normal;
}

  </style>


</head>

<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
<?php $this->inserir_javascript_urlencode(); ?>

function gebi(id) { return document.getElementById(id); }
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
function s(id)
{
  e=gebi('none');
  e.style.display='none';
  e.style.visibility='hidden';
  e=gebi(id);
  e.style.visibility='visible';
  e.style.display='block';
}
function h(id)
{
  e=gebi(id);
  e.style.visibility='hidden';
  e.style.display='none';
  e=gebi('none');
  e.style.visibility='visible';
  e.style.display='block';
}
//]]>
</script>

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>

<table width="903" height="70" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="300" align="center" valign="middle">
    <?php $this->inserir_panell_handset_escollit($ih); ?>
    </td>
    <td width="247" height="70"></td>
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" valign="top"><img src="a0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print PHOTO_DOMAIN."/index.php?ih=$ih"; ?>';"><img style="display:none" id="pho" src="r_photo6.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print VIDEO_DOMAIN."/index.php?ih=$ih"; ?>';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" <?php /*onclick="document.location.href='*/?><?php /*print AUDIO_DOMAIN."/ft.php?ih=$ih";*/ ?><?php /*';*/?>"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print ANIMA_DOMAIN."/index.php?ih=$ih"; ?>';"><img style="display:none" id="ani" src="r_anima3.gif"/></td>
    <td width="43" background="a1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" value="<?php print $cerca; ?>"/></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search photos" title="Search photos" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?ih=<?php print $ih; ?>&s='+URLEncode(c.value);" /><img src="a2.gif" width="41" height="34" /></td>
  </tr>
</table>
  
  
  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="105" rowspan="3" align="left" valign="top"><img src="a3.gif"></td>
    <td width="170" height="50" align="left" valign="middle">
	<a href="<?php print PHOTO_DOMAIN; ?>/upload.php"><img src="<?php print WWW_DOMAIN; ?>/up.png" />
    </td>
    <td width="631" height="490" colspan="3" rowspan="3" align="right" valign="top">
<?php

	if(is_numeric($imh))
	{
		print "<IFRAME SRC=\"choose_phone.php?imh=$imh&ih=$ih";
                if($cerca) print "&s=".(urlencode($cerca));
                print "\" NAME=\"m\" id=\"m\" HEIGHT=\"2500\" WIDTH=\"625\" FRAMEBORDER=0>Sorry, your browser doesn't support iframes.</IFRAME>\n";
	}
	else if($codi!='')
	{
                print "<IFRAME SRC=\"info.php?c=$codi&ih=&ca=0#top\" NAME=\"m\" id=\"m\" HEIGHT=\"800\" WIDTH=\"625\" FRAMEBORDER=0>Sorry, your browser doesn't support iframes.</IFRAME>\n";
	}
	else
	{
		print "<IFRAME SRC=\"ftc.php?ih=$ih";
		if($cerca) print "&s=".(urlencode($cerca));
		print "\" NAME=\"m\" id=\"m\" HEIGHT=\"3500\" WIDTH=\"625\" FRAMEBORDER=0>Sorry, your browser doesn't support iframes.</IFRAME>\n";
	}
?>
    </td>
  </tr>

  <tr>
    <td height="30" align="left" valign="top">


<table border="0">
<tbody>
<tr>
        <td valign="top">

        <b>Destacated</b><br/>

        &nbsp; &#187; <a href="ftc.php?c=<?php print MES_RECENT.$param_id_handset; ?>" target="m">The <b>+</b> recent</a><br/>

        <br/><b>Classified</b><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_AMOR.$param_id_handset; ?>" target="m" />Love & Peace</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_DEPORTE.$param_id_handset; ?>" target="m" />Sport & Extreme</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_MUSICA.$param_id_handset; ?>" target="m" />Music & Clips</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_AMIGOS.$param_id_handset; ?>" target="m" />Friends</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_FAMILIA.$param_id_handset; ?>" target="m" />Family & People</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_PAISAJES.$param_id_handset; ?>" target="m" />Places & Lands</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_VIAJES.$param_id_handset; ?>" target="m" />Voyages & Trips</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_DIVERTIDA.$param_id_handset; ?>" target="m" />Funny & Crazy</a><br/>
        &nbsp; &#187; <a href="ftc.php?c=<?php print CATEGORIA_EROTICA.$param_id_handset; ?>" target="m" />Erotic & Hot</a><br/>

        <p>
        <b>Recommended</b>
        </td>
</tr>
</tbody>
</table>

  </td>
 </tr>











<!-- INICI RECOMENDED CONTENT -->

  <tr>
    <td width="170" align="right" valign="top" height="2900">
	<?php $this->inserir_contingut_recomanat(78,$ih); ?>
    </td>
  </tr>

<!-- FI RECOMENDED CONTENT -->

  <tr>
    <td colspan="6"><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1595535-2";
urchinTracker();
</script>
</body>
</html>
<?php
	}

        function mostrar_panell_puijada_foto_original()
        {
            $uploader = new file_uploader('es', MAX_FILE_SIZE);
        ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
	<link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
  /*<![CDATA[*/
   td.photo { background: url(u_photo.gif); cursor: hand; cursor: pointer;}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima.gif); cursor: hand; cursor: pointer;}

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  /*]]>*/
  </style>
</head>

<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
<?php $this->inserir_javascript_urlencode(); ?>

function gebi(id) { return document.getElementById(id); }
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
//]]>
</script>

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>

<form action="#" method="post" name="upload_form" enctype="multipart/form-data">
<input type="hidden" name="a" value="s">

<table width="903" height="60" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr" style="display:none;" /></td>
    <td width="427" height="60"><img style="display:none;" id="mup" src="m_sube.gif" /><img style="display:none;" id="mfot" src="m_fototeca.gif" /></td>
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>

  </tr>
  <tr valign="top">
    <td align="top"><img src="c0.gif" width="105" height="34" /></td>
    <td class="photo" width="150" height="34" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print PHOTO_DOMAIN; ?>';"><img style="display:none" id="pho" src="r_photo.gif"/></td>
    <td class="video" width="117" height="34" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print VIDEO_DOMAIN; ?>';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td class="audio" width="118" height="34" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print AUDIO_DOMAIN; ?>';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td class="anima" width="153" height="34" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print ANIMA_DOMAIN; ?>';"><img style="display:none" id="ani" src="r_anima.gif"/></td>
    <td><img src="c1.gif" width="43" height="34" /></td>
    <td width="141" align="center" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" /></td>

    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search photos" title="Search photos" src="bcerca.gif" width="37" height="34" onclick="var c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);" /><img src="c2.gif" width="41" height="34" /></td>
  </tr>
</table>
  
  <table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left"><img src="c3.gif" width="905" height="201" /></td>
  </tr>
  <tr>
    <td height="40" align="right" bordercolor="0" background="c4.gif"><input name="upload" type="file"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
  <tr>
    <td><img src="c8.gif" width="724" height="33" /><img style="cursor:pointer;cursor:hand;" alt="Subir foto" title="Subir foto" src="c9.gif" width="32" height="33" onclick="document.upload_form.submit();"/><img src="c10.gif" width="149" height="33" /></td>
  </tr>
  <tr>
    <td><img src="c11.gif" width="905" height="204" /></td>
  </tr>
  <tr>
    <td><?php print FOOT_HTML_MESSAGE;?></td>

  </tr>
</table>
</form>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1595535-2";
urchinTracker();
</script>
</body>
</html>
<?
        
        }

        function mostrar_panell_retallar_wallpaper($pas)
        {
                switch($pas)
                {
                        case RETALLAR_VERTICAL :        $tamany = WALLPAPER_VERTICAL_SIZE; break;
                        case RETALLAR_APAISAT :         $tamany = WALLPAPER_APAISAT_SIZE; break;
                        default :                       $tamany = WALLPAPER_NORMAL_SIZE;
                }

                $ci =& new CropInterface(true);
                $ci->setCropAllowResize(true);
                $ci->setCropTypeDefault(ccRESIZEPROP);
                $ci->setCropTypeAllowChange(true);
                $ci->setCropSizeDefault($tamany);
                $ci->setCropPositionDefault(ccCENTRE);
                $ci->setCropMinSize(200, 200);
                $ci->setExtraParameters(); //array('test' => '1', 'fake' => 'this_var'));
                $ci->setCropSizeList(array('75:100' => 'Vertical', '1:1' => 'Normal', '100:75' => 'Apaisat'));
                $ci->loadInterface("img.php", $_SESSION["usuari"]->nom_fitxer); //('mypicture.jpg');
        }

        function mostrar_panell_report_final()
        {
        ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
	<link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
  /*<![CDATA[*/
   td.photo {
	background: url(u_photo5.gif);
	cursor: pointer;
	cursor: pointer;
	vertical-align: middle;
}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima5.gif); cursor: hand; cursor: pointer;}
.Estilo4 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 27px;
	font-weight: bold;
	color: #cfdd1e;
}

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  </style>
</head>

<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
<?php $this->inserir_javascript_urlencode(); ?>

function gebi(id) { return document.getElementById(id); }
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
function s(id)
{
  e=gebi('none');
  e.style.display='none';
  e.style.visibility='hidden';
  e=gebi(id);
  e.style.visibility='visible';
  e.style.display='block';
}
function h(id)
{
  e=gebi(id);
  e.style.visibility='hidden';
  e.style.display='none';
  e=gebi('none');
  e.style.visibility='visible';
  e.style.display='block';
}
//]]>
</script>

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>

<table width="903" height="60" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr" style="display:none;" /></td>
    <td width="427" height="60"><img style="display:none;" id="mup" src="m_sube.gif" /><img style="display:none;" id="mfot" src="m_fototeca.gif" /></td>
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="e0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print PHOTO_DOMAIN; ?>';"><img style="display:none" id="pho" src="r_photo5.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print VIDEO_DOMAIN; ?>';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print AUDIO_DOMAIN; ?>';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print ANIMA_DOMAIN; ?>';"><img style="display:none" id="ani" src="r_anima5.gif"/></td>
    <td width="43" background="e1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search photos" title="Search photos" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);"/><img src="e2.gif" width="41" height="34" /></td>
  </tr>
</table>
  
  <form id="end" name="end" action"#" method="post">
  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="166" colspan="2" background="e3.gif"></td>
  </tr>
  <tr>
    <td width="623" height="53" background="e4.gif"></td>
    <td width="283" background="e6.gif"><span class="Estilo4">etm&nbsp;&nbsp;<? print $_SESSION["usuari"]->codi_contingut; ?></span></td>
  </tr>
  <tr>
    <td height="88" colspan="2" background="e7.gif"></td>
  </tr>
  <tr>
    <td height="36" colspan="2" align="right" background="e8.gif"><input type="text" name="email" style="width:260px"/>&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td height="11" colspan="2" background="e9.gif"></td>
  </tr>
  <tr>
    <td height="44" colspan="2"><img src="e10.gif" width="830" height="44" /><img style="cursor:pointer;cursor:hand;" alt="Finalizar" title="Finalizar" src="e11.gif" width="44" height="44" onclick="document.forms['end'].submit();"/><img src="e12.gif" width="31" height="44" /></td>
  </tr>
  <tr>
    <td height="110" colspan="2" background="e13.gif"></td>
  </tr>
  <tr>
    <td colspan="4"><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
</form>
</body>
</html>
<?
        }

        function mostrar_panell_entrar_metainformacio()
        {
                $codi_contingut = $_SESSION["usuari"]->generar_codi_contingut_disponible();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
	<link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
  /*<![CDATA[*/
   td.photo {
	background: url(u_photo4.gif);
	cursor: pointer;
	cursor: pointer;
	vertical-align: middle;
	background-repeat: no-repeat;
}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima4.gif); cursor: hand; cursor: pointer;}

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  /*]]>*/
  </style>
</head>

<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
<?php $this->inserir_javascript_urlencode(); ?>

function gebi(id) { return document.getElementById(id); }
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
function s(id)
{
  e=gebi('none');
  e.style.display='none';
  e.style.visibility='hidden';
  e=gebi(id);
  e.style.visibility='visible';
  e.style.display='block';
}
function h(id)
{
  e=gebi(id);
  e.style.visibility='hidden';
  e.style.display='none';
  e=gebi('none');
  e.style.visibility='visible';
  e.style.display='block';
}

function cc_Submit(a)
{
  document.forms['metainfo'].a.value = a;
  document.forms['metainfo'].submit();
  return true;
}
//]]>
</script>

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>

<form method="POST" action="#" name="metainfo">
<input type="hidden" name="a" value="">
<table width="903" height="60" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr"/></td>
    <td width="427" height="60"><img id="mfor" src="m_formulari.gif" /><img style="display:none;" id="mfot" src="m_fototeca.gif" /></td>
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="f0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print PHOTO_DOMAIN; ?>';"><img style="display:none" id="pho" src="r_photo4.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print VIDEO_DOMAIN; ?>';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print AUDIO_DOMAIN; ?>';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print ANIMA_DOMAIN; ?>';"><img style="display:none" id="ani" src="r_anima4.gif"/></td>
    <td width="43" background="f1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search photos" title="Search photos" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);"/><img src="f2.gif" width="41" height="34" /></td>
  </tr>
</table>
  
  
  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="627" height="483" rowspan="9" align="left" valign="top" background="f3.gif"></td>
    <td width="278" height="58" colspan="3" align="right" valign="bottom" background="f4.gif"><img style="display:none" id="back" src="r12_back.gif" width="219" height="60" /><img style="display:none" id="cancel" src="r12_cancel.gif" width="219" height="60" /><img style="display:none" id="next" src="r12_next.gif" width="219" height="60" /><input name="public" type="checkbox" value="Y" checked="checked" />
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td width="278" height="109" colspan="3" align="center" valign="bottom" background="f5.gif"><textarea name="description" cols="28" rows="2"  style="width:240px;"></textarea></td>
  </tr>
  <tr>
    <td width="278" height="61" colspan="3" align="center" valign="bottom" background="f6.gif">
      <input name="name" type="text" size="30" maxlength="255" style="width:240px;"/></td>
  </tr>
  <tr>
    <td width="75" height="52" align="right" valign="bottom" background="f14.gif"><input type="checkbox" name="<?php print CATEGORIA_AMOR; ?>" value="1" /></td>
    <td width="100" align="right" valign="bottom" background="f15.gif"><input type="checkbox" name="<?php print CATEGORIA_DEPORTE; ?>" value="1" />&nbsp;&nbsp;</td>
    <td width="103" align="right" valign="bottom" background="f16.gif"><input type="checkbox" name="<?php print CATEGORIA_MUSICA; ?>" value="1" />&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td width="75" height="30" align="right" background="f20.gif"><input type="checkbox" name="<?php print CATEGORIA_AMIGOS; ?>" value="1" /></td>
    <td align="right" background="f21.gif"><input type="checkbox" name="<?php print CATEGORIA_FAMILIA; ?>" value="1" />&nbsp;&nbsp;</td>
    <td width="103" align="right" background="f22.gif"><input type="checkbox" name="<?php print CATEGORIA_PAISAJES; ?>" value="1" />&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td align="right" background="f17.gif"><input type="checkbox" name="<?php print CATEGORIA_VIAJES; ?>" value="1" /></td>
    <td align="right" background="f19.gif"><input type="checkbox" name="<?php print CATEGORIA_DIVERTIDA; ?>" value="1" />&nbsp;&nbsp;</td>
    <td width="103" height="30" align="right" background="f18.gif"><input type="checkbox" name="<?php print CATEGORIA_EROTICA; ?>" value="1" />&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td height="17" colspan="3" background="f7.gif"></td>
  </tr>
  <tr>
    <td height="45" colspan="3"><img src="f9.gif" width="65" height="45" /><img src="f10.gif" style="cursor:pointer;cursor:hand;" title="Anterior" alt="Anterior" width="50" height="45" onclick="cc_Submit('a');"/><img src="f11.gif"  style="cursor:pointer;cursor:hand;" title="Cancelar" alt="Cancelar"  width="54" height="45" onclick="cc_Submit('c');"/><img src="f12.gif"  style="cursor:pointer;cursor:hand;" title="Siguiente" alt="Siguiente" width="49" height="45" onclick="cc_Submit('s');"/><img src="f13.gif" width="60" height="45" /></td>
  </tr>
  <tr>
    <td width="278" height="81" colspan="3" background="f8.gif"></td>
  </tr>
  <tr>
    <td colspan="5"><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
</form>
</body>
</html>

<?

        }

        function obtenir_nom_marca_handset($id_marca_handset)
        {
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("select marca from marca_handset where id_marca_handset = $id_marca_handset");

                if($res==null) return '';
                else
                {
                        $row = $res->fetchRow();
                        return $row['marca'];
                }        
        }

	function obtenir_marques_handsets()
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("select * from marca_handset order by marca asc");
		return $res;
	}

	function obtenir_handsets_marca($imh, $id_categoria_contingut=WALLPAPER)
	{
		switch($id_categoria_contingut)
		{
			case WALLPAPER:		$camp = 'wallpaper'; break;
			case VIDEO:		$camp = 'video'; break;
			case ANIMATION:		$camp = 'animation'; break;
			case AUDIO:		$camp = 'audio'; break;
			default:		die();
		}

                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("	select	h.id_handset,
							h.model
						from	handset h,
							handset_capability hc
						where	h.id_marca_handset=$imh
							and hc.id_handset = h.id_handset
							and hc.".$camp."_support = 'Y'");
                return $res;
	}

        function obtenir_handsets_marca_amb_preview($id_marca_handset, $id_categoria_contingut)
        {
                $handsets = array();
                $con_bd = new conexio_bd();

		switch($id_categoria_contingut)
		{
			case WALLPAPER:		$camp = 'wallpaper_support'; break;
			case VIDEO:		$camp = 'video_support'; break;
			case AUDIO:		$camp = 'audio_support'; break;
			case ANIMATION:		$camp = 'animation_support'; break;
			default:		$camp = 'wallpaper_support'; break;
		}

/*                $sql = "
                          select
                                ih.id_handset
                          from
                                imatge_handset ih,
                                handset h,
				handset_capability hc
                          where
                                h.id_marca_handset = $id_marca_handset
                                and ih.id_handset = h.id_handset
				and hc.id_handset = h.id_handset
				and hc.$camp = 'Y'
			  order
				by h.model asc";*/
                $sql = "
                          select
                                h.id_handset
                          from
                                handset h,
				handset_capability hc
                          where
                                h.id_marca_handset = $id_marca_handset
				and hc.id_handset = h.id_handset
				and hc.$camp = 'Y'
			  order
				by h.model asc";

                $res = $con_bd->sql_query($sql);
                if($res==null) return array();
                else while($row = $res->fetchRow()) array_push($handsets, $row[0]);
                return $handsets;
        }

        function obtenir_model_handset($id_handset)
        {
                $con_bd = new conexio_bd();
                $sql = "select model from handset where id_handset = $id_handset";
                $res = $con_bd->sql_query($sql);
                if($res==null) return '';
                else
                {
                  $row = $res->fetchRow();
                  return $row['model'];
                }
        }

        function mostrar_panell_opcions()
        {
            print "\t\t<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td><img src=\"images/cse.png\"></td>\n";
            print "\t\t\t\t<td background=\"images/cs.png\"></td>\n";
            print "\t\t\t\t<td><img src=\"images/csd.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td width=\"12px\" background=\"images/ce.png\"></td>\n";
            print "\t\t\t\t<td align=\"center\" bgcolor='white' style=\"\">\n";
            print "\t\t\t\t\t<img src=\"images/back.png\" style=\"cursor: pointer; cursor:hand;\">\n";
            print "\t\t\t\t\t<img src=\"images/cancel.png\" style=\"cursor: pointer; cursor:hand;\">\n";
            print "\t\t\t\t\t<img src=\"images/next.png\" style=\"cursor: pointer; cursor:hand;\" onclick=\"validate.submit();\">\n";
            print "\t\t\t\t</td>\n";
            print "\t\t\t\t<td width=\"12px\" background=\"images/cd.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td><img src=\"images/cie.png\"></td>\n";
            print "\t\t\t\t<td background=\"images/ci.png\"></td>\n";
            print "\t\t\t\t<td><img src=\"images/cid.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t</table>\n";
        }

        function mostrar_panell_cercar()
        {
            print "\t\t<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td><img src=\"images/cse.png\"></td>\n";
            print "\t\t\t\t<td background=\"images/cs.png\"></td>\n";
            print "\t\t\t\t<td><img src=\"images/csd.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td width=\"12px\" background=\"images/ce.png\"></td>\n";
            print "\t\t\t\t<td align=\"center\" bgcolor='white' style=\"\">\n";
            print "\t\t\t\t\t<img src=\"images/back.png\" style=\"cursor: pointer; cursor:hand;\">\n";
            print "\t\t\t\t</td>\n";
            print "\t\t\t\t<td width=\"12px\" background=\"images/cd.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td><img src=\"images/cie.png\"></td>\n";
            print "\t\t\t\t<td background=\"images/ci.png\"></td>\n";
            print "\t\t\t\t<td><img src=\"images/cid.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t</table>\n";
        }

        function mostrar_panell_preview_handset($imh=1,$ih=581)
        {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_PHOTO_ES."\n";
print META_DESCRIPTION_PHOTO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_PHOTOTURMIX."\n";
?>
	<link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
  /*<![CDATA[*/
   td.photo {
	background: url(u_photo3.gif);
	cursor: pointer;
	cursor: pointer;
	vertical-align: middle;
}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima3.gif); cursor: hand; cursor: pointer;}

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  /*]]>*/
  </style>
</head>

<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
<?php $this->inserir_javascript_urlencode(); ?>

function gebi(id) { return document.getElementById(id); }
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
function s(id)
{
  e=gebi('none');
  e.style.display='none';
  e.style.visibility='hidden';
  e=gebi(id);
  e.style.visibility='visible';
  e.style.display='block';
}
function h(id)
{
  e=gebi(id);
  e.style.visibility='hidden';
  e.style.display='none';
  e=gebi('none');
  e.style.visibility='visible';
  e.style.display='block';
}

function cc_Submit(a)
{
  document.forms['validate'].a.value = a;
  document.forms['validate'].submit();
  return true;
}
//]]>
</script>

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>

<table width="903" height="60" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"></td>
    <td width="427" height="60"></td>
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="p0.gif">&nbsp;</td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print PHOTO_DOMAIN; ?>';"><img style="display:none" id="pho" src="r_photo3.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print VIDEO_DOMAIN; ?>';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print AUDIO_DOMAIN; ?>';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print ANIMA_DOMAIN; ?>';"><img style="display:none" id="ani" src="r_anima3.gif"/></td>
    <td width="43" background="p1.gif">&nbsp;</td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search photos" title="Search photos" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);"/><img src="p2.gif" width="41" height="34" /></td>
  </tr>
</table>
  
  
  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" valign="bottom" background="p3.gif">
<?php
	print "	<form name=\"validate\" method=\"POST\" action=\"#\">\n";
	print "		<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
	print "		<input type=\"hidden\" name=\"a\" value=\"\">\n";
	print "	</form>\n";

	print "	<select name=\"imh\" style=\"width:130px;\" onchange=\"window.location='upload.php?imh='+this.options[this.selectedIndex].value;\">\n";
        $marques = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27);

	foreach($marques as $id_marca_handset)
	{
		$marca=$this->obtenir_nom_marca_handset($id_marca_handset);
		if($id_marca_handset==$imh) $selected="selected"; else $selected="";
		print "<option value=\"$id_marca_handset\" $selected>$marca\n";
	}
	print "</select>\n";

	print "	<select name=\"ih\" style=\"width:130px;\" onchange=\"window.location='upload.php?imh=$imh&ih='+this.options[this.selectedIndex].value;\">\n";
	$handsets = $this->obtenir_handsets_marca_amb_preview($imh, WALLPAPER);
	foreach($handsets as $id_handset)
	{
		$model=$this->obtenir_model_handset($id_handset);
		if($id_handset==$ih) $selected="selected"; else $selected="";
		print "<option value=\"$id_handset\" $selected>$model\n";
	}
	print "</select>\n";

	$id_handset = $ih; //785 //Motorol v535

	$con_bd = new conexio_bd();
	$res = $con_bd->sql_query("select * from imatge_handset where id_handset =  $id_handset");
	if($res==null) print "ERROR";
	else
	{
		$row = $res->fetchRow();
		$imatge_handset = $row['imatge_jpg'];
		if(!count($imatge_handset))
			$imatge_handset = base64_decode("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dGEvIiB4OnhtcHRrPSIzLjEuMS0xMTEiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczp4YXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIKICAgICAgICAgICAgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiPgogICAgICAgICA8eGFwTU06RG9jdW1lbnRJRD51dWlkOjgxMDc1MDYzOUExRURDMTE5QTIwOTI4Q0JGN0M4OUE2PC94YXBNTTpEb2N1bWVudElEPgogICAgICAgICA8eGFwTU06SW5zdGFuY2VJRD51dWlkOjgyMDc1MDYzOUExRURDMTE5QTIwOTI4Q0JGN0M4OUE2PC94YXBNTTpJbnN0YW5jZUlEPgogICAgICAgICA8eGFwTU06RGVyaXZlZEZyb20gcmRmOnBhcnNlVHlwZT0iUmVzb3VyY2UiPgogICAgICAgICAgICA8c3RSZWY6aW5zdGFuY2VJRD51dWlkOjM0NUU3QTlGRkQxOURDMTE4MDQ0QUZDRTI5MjgzRTdFPC9zdFJlZjppbnN0YW5jZUlEPgogICAgICAgICAgICA8c3RSZWY6ZG9jdW1lbnRJRD51dWlkOjMxNUU3QTlGRkQxOURDMTE4MDQ0QUZDRTI5MjgzRTdFPC9zdFJlZjpkb2N1bWVudElEPgogICAgICAgICA8L3hhcE1NOkRlcml2ZWRGcm9tPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6eGFwPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIj4KICAgICAgICAgPHhhcDpDcmVhdGVEYXRlPjIwMDctMDYtMTlUMjE6MjQ6MDkrMDI6MDA8L3hhcDpDcmVhdGVEYXRlPgogICAgICAgICA8eGFwOk1vZGlmeURhdGU+MjAwNy0wNi0xOVQyMToyNDowOSswMjowMDwveGFwOk1vZGlmeURhdGU+CiAgICAgICAgIDx4YXA6TWV0YWRhdGFEYXRlPjIwMDctMDYtMTlUMjE6MjQ6MDkrMDI6MDA8L3hhcDpNZXRhZGF0YURhdGU+CiAgICAgICAgIDx4YXA6Q3JlYXRvclRvb2w+QWRvYmUgUGhvdG9zaG9wIENTMiBXaW5kb3dzPC94YXA6Q3JlYXRvclRvb2w+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iPgogICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL2pwZWc8L2RjOmZvcm1hdD4KICAgICAgPC9yZGY6RGVzY3JpcHRpb24+CiAgICAgIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyI+CiAgICAgICAgIDxwaG90b3Nob3A6Q29sb3JNb2RlPjE8L3Bob3Rvc2hvcDpDb2xvck1vZGU+CiAgICAgICAgIDxwaG90b3Nob3A6SUNDUHJvZmlsZT5Eb3QgR2FpbiAxNSU8L3Bob3Rvc2hvcDpJQ0NQcm9maWxlPgogICAgICAgICA8cGhvdG9zaG9wOkhpc3RvcnkvPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6dGlmZj0iaHR0cDovL25zLmFkb2JlLmNvbS90aWZmLzEuMC8iPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpYUmVzb2x1dGlvbj43MjAwMDAvMTAwMDA8L3RpZmY6WFJlc29sdXRpb24+CiAgICAgICAgIDx0aWZmOllSZXNvbHV0aW9uPjcyMDAwMC8xMDAwMDwvdGlmZjpZUmVzb2x1dGlvbj4KICAgICAgICAgPHRpZmY6UmVzb2x1dGlvblVuaXQ+MjwvdGlmZjpSZXNvbHV0aW9uVW5pdD4KICAgICAgICAgPHRpZmY6TmF0aXZlRGlnZXN0PjI1NiwyNTcsMjU4LDI1OSwyNjIsMjc0LDI3NywyODQsNTMwLDUzMSwyODIsMjgzLDI5NiwzMDEsMzE4LDMxOSw1MjksNTMyLDMwNiwyNzAsMjcxLDI3MiwzMDUsMzE1LDMzNDMyO0Y3MEUyNEFGREVDREY2MkQ0MzYzMEZGNDMyNTUwNEFDPC90aWZmOk5hdGl2ZURpZ2VzdD4KICAgICAgPC9yZGY6RGVzY3JpcHRpb24+CiAgICAgIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOmV4aWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vZXhpZi8xLjAvIj4KICAgICAgICAgPGV4aWY6UGl4ZWxYRGltZW5zaW9uPjI1MDwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOlBpeGVsWURpbWVuc2lvbj40MjU8L2V4aWY6UGl4ZWxZRGltZW5zaW9uPgogICAgICAgICA8ZXhpZjpDb2xvclNwYWNlPi0xPC9leGlmOkNvbG9yU3BhY2U+CiAgICAgICAgIDxleGlmOk5hdGl2ZURpZ2VzdD4zNjg2NCw0MDk2MCw0MDk2MSwzNzEyMSwzNzEyMiw0MDk2Miw0MDk2MywzNzUxMCw0MDk2NCwzNjg2NywzNjg2OCwzMzQzNCwzMzQzNywzNDg1MCwzNDg1MiwzNDg1NSwzNDg1NiwzNzM3NywzNzM3OCwzNzM3OSwzNzM4MCwzNzM4MSwzNzM4MiwzNzM4MywzNzM4NCwzNzM4NSwzNzM4NiwzNzM5Niw0MTQ4Myw0MTQ4NCw0MTQ4Niw0MTQ4Nyw0MTQ4OCw0MTQ5Miw0MTQ5Myw0MTQ5NSw0MTcyOCw0MTcyOSw0MTczMCw0MTk4NSw0MTk4Niw0MTk4Nyw0MTk4OCw0MTk4OSw0MTk5MCw0MTk5MSw0MTk5Miw0MTk5Myw0MTk5NCw0MTk5NSw0MTk5Niw0MjAxNiwwLDIsNCw1LDYsNyw4LDksMTAsMTEsMTIsMTMsMTQsMTUsMTYsMTcsMTgsMjAsMjIsMjMsMjQsMjUsMjYsMjcsMjgsMzA7OTA1Q0UwQjc3QkM3RTg0QkExMjlDQjZCNzc0RDNBNDY8L2V4aWY6TmF0aXZlRGlnZXN0PgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAKPD94cGFja2V0IGVuZD0idyI/Pv/iA6BJQ0NfUFJPRklMRQABAQAAA5BBREJFAhAAAHBydHJHUkFZWFlaIAfPAAYAAwAAAAAAAGFjc3BBUFBMAAAAAG5vbmUAAAAAAAAAAAAAAAAAAAABAAD21gABAAAAANMtQURCRQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABWNwcnQAAADAAAAAMmRlc2MAAAD0AAAAZ3d0cHQAAAFcAAAAFGJrcHQAAAFwAAAAFGtUUkMAAAGEAAACDHRleHQAAAAAQ29weXJpZ2h0IDE5OTkgQWRvYmUgU3lzdGVtcyBJbmNvcnBvcmF0ZWQAAABkZXNjAAAAAAAAAA1Eb3QgR2FpbiAxNSUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFhZWiAAAAAAAAD21gABAAAAANMtWFlaIAAAAAAA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");

		$orientacio = strtolower($row['orientacio_pantalla']);
		$x_ini = $row['x_ini'];
		$y_ini = $row['y_ini'];
		$x_fi = $row['x_fi'];
		$y_fi = $row['y_fi'];

		$string_image = $_SESSION["usuari"]->wallpaper[$orientacio];
		$image_width = $_SESSION["usuari"]->wallpaper_tamany[$orientacio]['width'];
		$image_height = $_SESSION["usuari"]->wallpaper_tamany[$orientacio]['height'];

		$ci =& new CropInterface(true);
		$ci->loadImageFromString($imatge_handset);
		$ci->combineImage($string_image, $image_width, $image_height, $x_ini, $y_ini, $x_fi, $y_fi);

		$ample = $ci->getImageWidth();
		$llarg = $ci->getImageHeight();

		$_SESSION["usuari"]->wallpapers['handset_preview'] = $ci->loadStringFromImage($_SESSION["usuari"]->codi_usuari);
	}
?>
    </td>
    <td width="272" rowspan="4" align="center"><?php
	if(($llarg>425)&&($ample>250)) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari"]->codi_usuari)."\" width=250 height=425>";
	else if($llarg>425) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari"]->codi_usuari)."\" height=425>";
	else if($ample>250) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari"]->codi_usuari)."\" width=250>";
	else print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari"]->codi_usuari)."\">";
	?></td>
  </tr>
  <tr>
    <td height="329" align="left" valign="top" background="p4.gif"></td>
  </tr>
  <tr>
    <td height="54" align="left" valign="top"><img src="p5.gif" width="331" height="54" /><img src="p6.gif" style="cursor:pointer;cursor:hand;" alt="Anterior" title="Anterior" width="59" height="54" onclick="cc_Submit('a');"/><img src="p7.gif" style="cursor:pointer;cursor:hand;" title="Cancelar" alt="Cancelar" width="64" height="54" onclick="cc_Submit('c');"/><img src="p8.gif" style="cursor:pointer;cursor:hand;" title="Siguiente" alt="Siguiente" width="63" height="54" onclick="cc_Submit('s');"/><img src="p9.gif" width="115" height="54" /></td>
  </tr>
  <tr>
    <td width="633" height="68" align="left" valign="top" background="p10.gif"></td>
	</tr>
  <tr>
    <td colspan="3"><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
</body>
</html>
<?

        }

        function mostrar_panell_models_marca($imh)
        {
            $handsets = $this->obtenir_handsets_marca_amb_preview($imh);
            print "\t\t<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td><img src=\"images/cse.png\"></td>\n";
            print "\t\t\t\t<td background=\"images/cs.png\"></td>\n";
            print "\t\t\t\t<td><img src=\"images/csd.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td background=\"images/ce.png\"></td>\n";
            print "\t\t\t\t<td align=\"center\" bgcolor='white' style=\"width:100%;height:100%;\">\n";


            print "\t\t\t\t<table align=\"center\" border=0 cellspacing=\"20\">\n";
            //print "\t\t\t\t<tr>\n";

            $i=0;
              foreach($handsets as $id_handset)
              {
                if(($i%8)==0)
                {
                    if($i)
                    {
                      print "\t\t\t\t</div>\n";
                      print "\t\t\t\t</tr>\n";
                    }                        

                    print "\t\t\t\t<tr>\n";
                }
                $model=$this->obtenir_model_handset($id_handset);
              	print "\t\t\t\t\t\t<td align=\"center\" style=\"cursor:pointer; cursor:hand;\" onClick=\"load_h($imh,$id_handset);\"\n"; 
              	print "\t\t\t\t\t\t\t<img src=\"previewh.php?id=$id_handset\"><br>$model\n";
              	print "\t\t\t\t\t\t</td>\n";
                $i++;
              }
              print "\t\t\t\t</div>\n";
              print "\t\t\t\t</tr>\n";

            //print "\t\t\t\t</tr>\n";
            print "\t\t\t\t</table>\n";


            print "\t\t\t\t</td>\n";
            print "\t\t\t\t<td background=\"images/cd.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t\t<tr>\n";
            print "\t\t\t\t<td><img src=\"images/cie.png\"></td>\n";
            print "\t\t\t\t<td background=\"images/ci.png\"></td>\n";
            print "\t\t\t\t<td><img src=\"images/cid.png\"></td>\n";
            print "\t\t\t</tr>\n";
            print "\t\t</table>\n";
        }

        function mostrar_panell_validar_preview($imh=1,$ih=581)
        {
		$this->mostrar_panell_preview_handset($imh,$ih);
	}
}

?>
