<?
require('../include/crop_canvas/class.cropinterface.php');
require('classe_interficie_smsturmix.php');
require('constants_smsturmix.php');
require_once("../DB/conexio_bd.php");
require_once("classe_sessio_usuari.php");

define("SERVER_NAME",		$_SERVER["SERVER_NAME"]);

define('MAX_ITEMS_CATEGORIA', 30); //Nombre màxim de fotos a mostrar a la graella de la fototeca

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

class interficie_animacio_studio extends interficie_smsturmix
{
        function interficie_animacio_studio ()
        {

        }

        function inserir_puntuador($puntuacio, $num_vots)
        {
                print "<table align=\"left\" id=\"t\" height=\"20\" border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse:collapse;\">\n";
                print "<tr>\n";
                for($i=1; $i<=5; $i++)
                {
                        if($i<=$puntuacio) $estrella='sf.png'; else $estrella='se.png';
                        print "<td width=\"19\" style=\"cursor:pointer;\" background=\"http://www.smsturmix.com/$estrella\" onmouseover=\"over($i,'block')\" onmouseout=\"over($i,'none')\" onclick=\"set($i)\">\n";
                        print " <img id=\"$i\" src=\"http://www.smsturmix.com/sf.png\" style=\"display:none\">\n";
                        print " <img id=\"d$i\" src=\"http://www.smsturmix.com/se.png\" style=\"display:none\">\n";
                        print "</td>\n";
                }
                print "<td valign=\"middle\" class=\"Estilo7\">&nbsp;<b>$num_vots</b> ratings</td>";
                print "</tr>\n";
                print "</table>\n";
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
                print " {\n";
                print "         doSimpleXMLHttpRequest(\"async.php\", {c: \"$codi_contingut\", v: n, op: 'vot'});\n";
                print "         alert('Thank you for rating!');\n";
                print "         over(n,'block');";
                print "         ha_votat=true;\n";
                print " }\n";
                print " else    alert('You cannot rate the same content one more time');\n";
                print "}\n";
                print "function gebi(id) { return document.getElementById(id); }\n";
                print "function over(n,d)\n";
                print "{\n";
                print " if(ha_votat==false)\n";
                print " {\n";
                print "         for(i=1; i<=n; i++)\n";
                print "         {\n";
                print "                 e = gebi(''+i);\n";
                print "                 e.style.display=d;\n";
                print "         }\n";
                print "         for(i=n+1; i<=5; i++)\n";
                print "         {\n";
                print "                 e = gebi('d'+i);\n";
                print "                 e.style.display=d;\n";
                print "         }\n";
                print " }\n";
                print "}\n";
                print "</script>\n";
        }

        function usuari_ha_votat_contingut($codi_contingut)
        {
                $ip = (getenv(HTTP_X_FORWARDED_FOR)) ?  getenv(HTTP_X_FORWARDED_FOR) :  getenv(REMOTE_ADDR);
                $con_bd = new conexio_bd();
                $sql = "select  count(uv.id_usuari_vot)
                        from    usuari_vot uv, mm mm
                        where   mm.codi_contingut = '$codi_contingut'
                                and uv.id_mm = mm.id_mm
                                and uv.ip = '$ip'";
                $res = $con_bd->sql_query($sql);
                if(!$res->numRows()) die();
                $row = $res->fetchRow();
                return $row['count(uv.id_usuari_vot)']>0;
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

        function obtenir_continguts_categoria($id_mm_categoria, $cerca, $max=16)
        {
                $con_bd = new conexio_bd();
                switch($id_mm_categoria)
                {
                        case MES_RECENT:        $tokens = explode(" ", $cerca);
                                                if(count($tokens))
                                                {
                                                        $sql = "select	mm.id_mm, mm.codi_contingut, mm.data_insert
                                                                from	mm mm
                                                                where
									mm.public = 'Y'
									and mm.estat = 'APROVAT'
									and id_categoria_contingut = ".ANIMATION." and ( ";
                                                        $or = "";
                                                        foreach($tokens as $token)
                                                        {
                                                                $sql .= " $or mm.descripcio like '%$token%' ";
                                                                $or = "or";
                                                        }
                                                        $sql .= ") order by mm.data_insert desc
                                                                limit 0, $max";
                                                }
                                                else
                                                {

                                                        $sql = "select	id_mm, codi_contingut, data_insert
                                                                from	mm
								where	mm.public = 'Y'
									and mm.estat = 'APROVAT'
									and id_categoria_contingut = ".ANIMATION."
                                                                order by data_insert desc
                                                                limit 0, $max";
                                                }
                                                break;

                        default:                $sql = "select	mm.id_mm, mm.codi_contingut, mm.data_insert
                                                        from	mm mm, mm_categoria_mm mcm
                                                        where	mm.id_mm = mcm.id_mm
								and mm.public = 'Y'
								and mm.estat = 'APROVAT'
                                                                and mcm.id_mm_categoria = $id_mm_categoria
								and mm.id_categoria_contingut = ".ANIMATION."
                                                        order by mm.data_insert desc, mcm.data_insert desc
                                                        limit 0, $max";
                                                break;
                }
                $res = $con_bd->sql_query($sql);
                return $res;
        }

        function mostrar_categoria_fototeca($categoria=MES_RECENT, $cerca='')
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



/*              print "#fotos .mm h2 strong\n";
                print "{\n";
                print " font: normal 10px Arial, Helvetica, sans-serif;\n";
                print " color:#666;\n";
                print "}\n";
                print "-->\n";
*/
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
                	        case CATEGORIA_AMOR:            print "<img src=\"".CAT_AMOR_IMAGE."\">"; break;
                        	case CATEGORIA_DEPORTE:         print "<img src=\"".CAT_DEPORTES_IMAGE."\">"; break;
	                        case CATEGORIA_MUSICA:          print "<img src=\"".CAT_MUSICA_IMAGE."\">"; break;
        	                case CATEGORIA_AMIGOS:          print "<img src=\"".CAT_AMIGOS_IMAGE."\">"; break;
                	        case CATEGORIA_FAMILIA:         print "<img src=\"".CAT_FAMILIA_IMAGE."\">"; break;
                        	case CATEGORIA_PAISAJES:        print "<img src=\"".CAT_PAISAJES_IMAGE."\">"; break;
	                        case CATEGORIA_VIAJES:          print "<img src=\"".CAT_VIAJES_IMAGE."\">"; break;
        	                case CATEGORIA_DIVERTIDA:       print "<img src=\"".CAT_DIVERTIDAS_IMAGE."\">"; break;
                	        case CATEGORIA_EROTICA:         print "<img src=\"".CAT_EROTICAS_IMAGE."\">"; break;
                        	case MES_DESCARREGAT:           print "<img src=\"".CAT_MES_DESCARREGAT_IMAGE."\">"; break;
	                        case MES_RECENT:                print "<img src=\"".CAT_MES_RECENT_IMAGE."\">"; break;
        	        }
		}
                print "</td>\n";
                print "</table>\n";

                $columnes = 5;
                $resultats = $this->obtenir_continguts_categoria($categoria, $cerca, MAX_ITEMS_CATEGORIA);
                $continguts = array();

                if($resultats==null) print "Error<br>";
                else
                {

                        if($resultats->numRows())
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

	                                print "\t<td align=\"center\"><div id=\"foto\"><a href=\"info.php?c=$codi_contingut"."&ca=$categoria\"><img src=\"pr.php?c=$codi_contingut\" border=2></br></br><b>$codi_contingut</b> <small>($data_insert)</small></a></div></td>\n";
        	                        if($i%$columnes==$columnes-1) print "</tr>\n<!--<tr><td colspan=4><hr size=1px></td></tr>-->\n";
                	                $i++;
                        	}
	                        if($i%$columnes!=$columnes) print "</tr>\n";
        	                print "</table>\n";
                        }
                        else if($cerca=='') print "</br></br><img src=\"".MSG_CATEGORIA_BUIDA."\">";
                        else print "</br></br><img src=\"".MSG_BUSQUEDA_SENSE_RESULTATS."\">";
		}
                print "</body>\n";
                print "</html>\n";
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
                $sql = "select id_mm, codi_contingut, descripcio, vots, puntuacio, nom, visites
                        from mm
                        where codi_contingut='$codi_contingut'";
                $res = $con_bd->sql_query($sql);
                return $res;
        }

	function mostrar_info_animacio($codi_contingut, $imh='', $ih='', $categoria='', $pag=1, $cerca='', $iih='')
	{
		if($iih=='') $iih = $ih;

		$this->incrementar_visita_contingut($codi_contingut);
		$info = $this->obtenir_info_foto($codi_contingut);
		$foto = $info->fetchRow();
		$id_mm = $foto['id_mm'];
		$codi_contingut = $foto['codi_contingut'];
		$descripcio = $foto['descripcio'];
                $num_vots = $foto['vots'];
		$visites = $foto['visites'];
                $nom = $foto['nom'];
                $puntuacio = round($foto['puntuacio']);
                $data_insert = $foto['data_insert'];
		if($categoria!='') $icona_volver = "&nbsp;<a href=\"ftc.php?c=$categoria&p=$pag&s=$cerca&ih=$iih\"><img style=\"width:90;height:90\" src=\"n8.gif\" border=0 alt=\"Back\" title=\"Back\"></a>\n";
		else $icona_volver = '';

		print "<html>\n";
		print "<head>\n";

print '
<style type="text/css">
<!--
.Estilo3 {
        font-size: 16px;
        font-family: Geneva, Arial, Helvetica, sans-serif;
        font-weight: bold;
}
.Estilo6 {font-size: 14px; font-family: Geneva, Arial, Helvetica, sans-serif;}
.Estilo7 {
        font-family: Geneva, Arial, Helvetica, sans-serif;
        font-size: 12px;
}
.Estilo8 {
        color: #052B7A;
        font-weight: bold;
        font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo10 {
        font-size: 20px;
        font-family: Geneva, Arial, Helvetica, sans-serif;
        color: #22D82F;
}
-->
</style>
';

                print "<script type=\"text/javascript\" src=\"http://www.smsturmix.com/js/mochikit/Base.js\"></script>\n";
                print "<script type=\"text/javascript\" src=\"http://www.smsturmix.com/js/mochikit/Async.js\"></script>\n";
                print "<script type=\"text/javascript\" src=\"http://www.smsturmix.com/js/mochikit/Iter.js\"></script>\n";
                print "<script type=\"text/javascript\" src=\"http://www.smsturmix.com/js/mochikit/DOM.js\"></script>\n";

                print "<link rel=\"stylesheet\" href=\"http://www.mobiturmix.com/css/cat_style.css\" type=\"text/css\" />\n";
                print "<link rel=\"stylesheet\" href=\"http://www.mobiturmix.com/css/cat_base.css\" type=\"text/css\" />\n";

		print "</head>\n";
		print BODY."\n";

                $this->inserir_javascript_puntuador($codi_contingut);

                print "<table align=\"center\" border=0 cellspacing=\"5%\">\n";
		print "<tr>\n";
		print "\t<td align=\"center\" valign=\"top\">\n";
                print "\t\t<img src=\"pv.php?c=$codi_contingut&ih=$ih\"></br></br>\n";
        print " <select name=\"imh\" style=\"width:130px;\" onchange=\"window.location='info.php?ca=$categoria"."&c=$codi_contingut&p=$pag&s=$cerca&imh='+this.options[this.selectedIndex].value;\">\n";
//        $marques = array(1,4,6,2,7,15,23,5,8,12,10,3);
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
        $handsets = $this->obtenir_handsets_marca_amb_preview($imh, ANIMATION);
        foreach($handsets as $id_handset)
        {
                $model=$this->obtenir_model_handset($id_handset);
                if($id_handset==$ih) $selected="selected"; else $selected="";
                print "<option value=\"$id_handset\" $selected>$model\n";
        }
        print "</select>\n";

                print "\t</td>\n";
		print "<td width=\"10%\"></td>\n";
                print "\t<td valign=\"top\" align=\"right\">\n";
		print "\t\t<table border=0 align=\"center\"><tr><td><img src=\"pvi.php?c=$codi_contingut\" border=1 align=\"center\"></td><td valign=\"bottom\">\n";
		print $icona_volver;
		print "</td></tr>";
		print "</table>";

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

                print "<table width=\"414\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" background=\"pas.png\"  style=\"background-repeat: no-repeat;\">\n";
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
                print "    <td width=\"250px\" colspan=\"2\" align=\"center\"><span class=\"Estilo7\">&nbsp;\n";
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

                print "</table>\n";


/*
                print "<table width=\"414\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
                print "  <tr>\n";
                print "    <td width=\"29\" rowspan=\"9\" background=\"pa2.gif\"></td>\n";
                print "    <td height=\"20\" colspan=\"3\" background=\"pa1.gif\"></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"20\" colspan=\"3\" background=\"pa3.gif\"><span class=\"Estilo3\">$nom</span></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"65\" colspan=\"3\" valign=\"top\" background=\"pa4.gif\"><span class=\"Estilo6\">\n";
                if(strlen($descripcio)>170) print (substr($descripcio, 0, 170))."...";
                else print $descripcio;
                print "    </span></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td colspan=\"2\" background=\"pa5.gif\" align=\"left\"><span class=\"Estilo7\">\n";
                $this->inserir_puntuador($puntuacio, $num_vots);
                print "    </span></td>\n";
                print "    <td height=\"19\" background=\"pa6.gif\"><span class=\"Estilo7\">visto <b>$visites</b> veces </span></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"14\" colspan=\"3\" nowrap=\"nowrap\" background=\"pa7.gif\"></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"17\" colspan=\"3\" background=\"pa8.gif\">\n";
		print "    <span class=\"Estilo7\"><span class=\"Estilo8\">WAP gratuito:</span>&nbsp;wap.mobiturmix.com/$codi_contingut/</span>\n";
		print "   </td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"15\" colspan=\"3\" background=\"pa9.gif\"></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td width=\"108\" height=\"23\" background=\"pa10.gif\"></td>\n";
                print "    <td width=\"147\" valign=\"middle\" background=\"pa11.gif\"><div align=\"center\" class=\"Estilo10\">etm&nbsp;$codi_contingut</div></td>\n";
                print "    <td width=\"130\" background=\"pa12.gif\"></td>\n";
                print "  </tr>\n";
                print "  <tr>\n";
                print "    <td height=\"50\" colspan=\"3\" background=\"pa13.gif\">&nbsp;</td>\n";
                print "  </tr>\n";
                print "</table>\n";*/

		print "\t</td>\n";
		print "</tr>\n";
		print "</table>\n";
		print "</body>\n";
		print "</html>\n";
	}

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
        $res = $this->obtenir_handsets_marca($imh, ANIMATION);
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
		print "					<span class=\"handset_text_small\">Check which animations are available<br/>for your $text\n";
		print "					</span>\n";
		print "				</div>\n";
	}

	function mostrar_panell_fototeca($cerca, $op='', $imh, $ih='')
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
print META_DESCRIPTION_ANIMA_ES."\n";
print META_DESCRIPTION_ANIMA_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_ANIMATURMIX."\n";
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
/*<!--
.Estilo5 {font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 16px;}
.Estilo5 a:link, a:visited{text-decoration:none;}
.Estilo9 {text-decoration:none; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; color: #2ab2ff; }
.Estilo9 a:link, a:visited{text-decoration:none; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 16px; color: #FF0033; }
.Estilo11 {font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; color: #8c8c8c; }
.Estilo11 a:link, a:visited{text-decoration:none; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; color: #8c8c8c; }
.Estilo13 {color: #8c8c8c}
-->*/


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
    <td width="105" background="a0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print PHOTO_DOMAIN."/index.php?ih=$ih"; ?>';"><img style="display:none" id="pho" src="r_photo6.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print VIDEO_DOMAIN."/index.php?ih=$ih"; ?>';"><img style="display:none" id="vid" src="r_video.gif"/></td>
   <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" <?php /*onclick="document.location.href='*/?><?php print AUDIO_DOMAIN."/ft.php?ih=$ih"; ?><?php /*';*/?>><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print ANIMA_DOMAIN."/index.php?ih=$ih"; ?>';"><img style="display:none" id="ani" src="r_anima3.gif"/></td>
    <td width="43" background="a1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" value="<?php print $cerca; ?>"/></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search animations" title="Search animations" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?ih=<?php print $ih; ?>&s='+URLEncode(c.value);" /><img src="a2.gif" width="41" height="34" /></td>
  </tr>
</table>


  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="105" rowspan="3" align="left" valign="top"><img src="a3.gif"></td>
    <td width="170" height="50" align="left" valign="middle">
        <a href="<?php print ANIMA_DOMAIN; ?>/upload.php"><img src="<?php print WWW_DOMAIN; ?>/up.png" />
    </td>
    <td width="631" height="490" colspan="3" rowspan="3" align="right" valign="top">
<?php

	if(is_numeric($imh))
	{
		print "<IFRAME SRC=\"choose_phone.php?imh=$imh&ih=$ih";
                if($cerca) print "&s=".(urlencode($cerca));
                print "\" NAME=\"m\" id=\"m\" HEIGHT=\"800\" WIDTH=\"625\" FRAMEBORDER=0>Sorry, your browser doesn't support iframes.</IFRAME>\n";
	}
	else
	{
		print "<IFRAME SRC=\"ftc.php?ih=$ih";
		if($cerca) print "&s=".(urlencode($cerca));
		print "\" NAME=\"m\" id=\"m\" HEIGHT=\"1650\" WIDTH=\"625\" FRAMEBORDER=0>Sorry, your browser doesn't support iframes.</IFRAME>\n";
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
  </td>
</tr>
</tbody>
</table>

  </td>
  </tr>



  <tr>
    <td width="170" height="1200" align="left" valign="top"><img src="170.gif" width="170"></td>
    </tr>
  <tr>
    <td colspan="6"><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1595535-4";
urchinTracker();
</script>
</body>
</html>
<?
        }

        function mostrar_panell_puijada_fotogrames()
        {
/*
            print "<html>\n<head>\n";
            print "<script src=\"js/multifile.js\"></script>\n";
            print "</head>\n";
            print BODY."\n";
            print "<form enctype=\"multipart/form-data\" action=\"upload.php\" method = \"post\">\n";
            print "\t<input id=\"fotograma\" type=\"file\" name=\"file_1\" >\n";
	    print "\t<input type=\"hidden\" name=\"a\" value=\"s\">\n";
            print "\t<input type=\"submit\">\n";
            print "</form>\n";
            print "Fotogrames:\n";

            print "<div id=\"files_list\"></div>\n";
            print "<script>\n";
            print "\tvar multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 10 );\n";
            print "\tmulti_selector.addElement( document.getElementById( 'fotograma' ) );\n";
            print "</script>\n";


	    print "<script src=\"http://www.google-analytics.com/urchin.js\" type=\"text/javascript\">\n";
	    print "</script>\n";
	    print "<script type=\"text/javascript\">\n";
	    print "_uacct = \"UA-1595535-4\";\n";
	    print "urchinTracker();\n";
	    print "</script>\n";
            print "</body>\n";
            print "</html>\n";*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_ANIMA_ES."\n";
print META_DESCRIPTION_ANIMA_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_ANIMATURMIX."\n";
?>
        <link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
  /*<![CDATA[*/
   td.photo { background: url(u_photo.gif); cursor: hand; cursor: pointer;}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima.gif); cursor: hand; cursor: pointer;}
   //input[value="Quitar"]
   input
   {
	background-color:#FFFFFF;
	border:1px solid #D6D3CE;
	font-size:9px;
	margin:2px auto;
	padding:0px;
	cursor: pointer;
	cursor: hand;
   }

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  /*]]>*/
  </style>
</head>
<?php print BODY; ?>
<script type="text/javascript" src="js/multifile.js"></script>
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
    <td width="105" height="34" background="c0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print PHOTO_DOMAIN; ?>';"><img style="display:none" id="pho" src="r_photo.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print VIDEO_DOMAIN; ?>';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print AUDIO_DOMAIN; ?>';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print ANIMA_DOMAIN; ?>';"><img style="display:none" id="ani" src="r_anima.gif"/></td>
    <td width="43" background="c1.gif"></td>
    <td width="141" align="center" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search animations" title="Search animations" src="bcerca.gif" width="37" height="34" onclick="c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);"/><img src="c2.gif" width="41" height="34" /></td>
  </tr>
</table>

  <table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="905" height="299" valign="bottom" align="right" background="c3.gif"> <!--<img src="c3.gif" width="905" height="201" />-->

       <input type="hidden" name="a" value="s">

      <div id="files_list" style="padding: 20px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; font-size: x-small; color: rgb(255, 255, 255); width:290px; height:155px;">
       <input id="fotograma" type="file" name="file_1" >
</div>
      <script>
        var multi_selector = new MultiSelector(gebi('files_list'), 8);
        multi_selector.addElement(gebi('fotograma'));
      </script>

    </td>
  </tr>
  <tr>
    <td><img src="c8.gif" width="718" height="40" /><img style="cursor:pointer;cursor:hand;" alt="Subir animacion" title="Subir animacion" src="c9.gif" width="42" height="40"  onclick="document.upload_form.submit();"/><img src="c10.gif" width="145" height="40" /></td>
  </tr>
  <tr>
    <td><img src="c11.gif" width="905" height="140" /></td>
  </tr>
  <tr>
    <td><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
</form>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1595535-4";
urchinTracker();
</script>
</body>
</html>
<?php

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
print META_DESCRIPTION_ANIMA_ES."\n";
print META_DESCRIPTION_ANIMA_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_ANIMATURMIX."\n";
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
    <td width="106" align="center" valign="middle"></td>
    <td width="427" height="60"></td>
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="logo.gif" border="0"/></a></td>
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
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search animations" title="Search animations" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);"/><img src="e2.gif" width="41" height="34" /></td>
  </tr>
</table>

  <form id="end" name="end" action"#" method="post">
  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="166" colspan="2" background="e3.gif"></td>
  </tr>
  <tr>
    <td width="623" height="53" background="e4.gif"></td>
    <td width="283" background="e6.gif"><span class="Estilo4">etm&nbsp;&nbsp;<? print $_SESSION["usuari_animacio"]->codi_contingut; ?></span></td>
  </tr>
  <tr>
    <td height="88" colspan="2" background="e7.gif"></td>
  </tr>
  <tr>
    <td height="36" colspan="2" align="right" background="e8.gif"><input type="text" name="textfield2" style="width:260px"/>&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td height="11" colspan="2" background="e9.gif"></td>
  </tr>
  <tr>
    <td height="44" colspan="2"><img src="e10.gif" width="830" height="44" /><img style="cursor:pointer;cursor:hand;" alt="Finalizar" title="Finalizar" src="e11.gif" width="44" height="44" onclick="document.forms['end'].submit();" /><img src="e12.gif" width="31" height="44" /></td>
  </tr>
  <tr>
    <td height="110" colspan="2" background="e13.gif"></td>
  </tr>
  <tr>
    <td colspan="4"><img src="p_foot.gif" width="882" height="25" /></td>
  </tr>
</table>
</form>
</body>
</html>
<?
        }

	function mostrar_panell_validar_preview($imh=1, $ih=581)
	{
	     $this->mostrar_panell_preview_handset($imh,$ih);
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

	function mostrar_panell_preview_handset($imh, $ih)
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_ANIMA_ES."\n";
print META_DESCRIPTION_ANIMA_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_ANIMATURMIX."\n";
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
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="logo.gif" border="0"/></a></td>
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
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search animations" title="Search animations" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);"/><img src="p2.gif" width="41" height="34" /></td>
  </tr>
</table>


  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center" valign="bottom" background="p3.gif">
<?php
        print " <form name=\"validate\" method=\"POST\" action=\"#\">\n";
        print "         <input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
        print "         <input type=\"hidden\" name=\"a\" value=\"\">\n";
        print " </form>\n";

        print " <select name=\"imh\" style=\"width:130px;\" onchange=\"window.location='upload.php?imh='+this.options[this.selectedIndex].value;\">\n";
//        $marques = array(1,4,6,2,7,15,23,5,8,12,10,3);
        $marques = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27);

        foreach($marques as $id_marca_handset)
        {
                $marca=$this->obtenir_nom_marca_handset($id_marca_handset);
                if($id_marca_handset==$imh) $selected="selected"; else $selected="";
                print "<option value=\"$id_marca_handset\" $selected>$marca\n";
        }
        print "</select>\n";

        print " <select name=\"ih\" style=\"width:130px;\" onchange=\"window.location='upload.php?imh=$imh&ih='+this.options[this.selectedIndex].value;\">\n";
        $handsets = $this->obtenir_handsets_marca_amb_preview($imh);
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
		$im = imagecreatefromstring($imatge_handset);
		$llarg = imagesy($im);
		$ample = imagesx($im);
		imagedestroy($im);
                $orientacio = strtolower($row['orientacio_pantalla']);
                $x_ini = $row['x_ini'];
                $y_ini = $row['y_ini'];
                $x_fi = $row['x_fi'];
                $y_fi = $row['y_fi'];

//                $string_image = $_SESSION["usuari_animacio"]->animacions[$orientacio];
                $_SESSION["usuari_animacio"]->generar_preview_handset($imatge_handset, $x_ini, $y_ini, $x_fi, $y_fi, $orientacio);
        }
?>
    </td>
    <td width="272" rowspan="4" align="center"><?php
        if(($llarg>425)&&($ample>250)) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_animacio"]->codi_usuari)."\" width=250 height=425>";
        else if($llarg>425) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_animacio"]->codi_usuari)."\" height=425>";
        else if($ample>250) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_animacio"]->codi_usuari)."\" width=250>";
        else print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_animacio"]->codi_usuari)."\">";
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
    <td colspan="3"><img src="p_foot.gif" width="882" height="25" /></td>
  </tr>
</table>
</body>
</html>
<?

        }

        function mostrar_panell_entrar_metainformacio()
        {
                $codi_contingut = $_SESSION["usuari_animacio"]->generar_codi_contingut_disponible();
/*                print "
                        <html>
                        <head></head>
                        <?php print BODY; ?>
                                <form action=\"\" method=POST>
                                        <slot>Nom: <input type=\"text\" name=\"name\" value=\"\"></slot><br>
                                        <slot>Descripci� <input type=\"text\" name=\"description\" value=\"\"></slot><br>
                                        <slot>Codi: <input type=\"text\" name=\"code\" value=\"$codi_contingut\"></slot><br>
                                        <slot>Pblic: <input type=\"checkbox\" name=\"public\" value=\"Y\" checked></slot><br>                                        <input type=\"submit\" name=\"confirm\" value=\"true\">
                                </form>
                        </body>
                        </html>";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_ANIMA_ES."\n";
print META_DESCRIPTION_ANIMA_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_ANIMATURMIX."\n";
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
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr"/></td>
    <td width="427" height="70"><img id="mfor" src="m_formulari.gif" /><img style="display:none;" id="mfot" src="m_fototeca.gif" /></td>
    <td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="logo.gif" border="0"/></a></td>
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
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Search animations" title="Search animations" src="bcerca.gif" height="34" onclick="c=gebi('search'); window.location='index.php?s='+URLEncode(c.value);"/><img src="f2.gif" width="41" height="34" /></td>
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
    <td height="45" colspan="3"><img src="f9.gif" width="65" height="45" /><img src="f10.gif" style="cursor:pointer;cursor:hand;" title="Anterior" alt="Anterior" width="50" height="45" onclick="cc_Submit('a');"/><img src="f11.gif"  style="cursor:pointer;cursor:hand;" title="Cancelar" alt="Cancelar" width="54" height="45" onclick="cc_Submit('c');"/><img src="f12.gif"  style="cursor:pointer;cursor:hand;" title="Siguiente" alt="Siguiente" width="49" height="45" onclick="cc_Submit('s');"/><img src="f13.gif" width="60" height="45" /></td>
  </tr>
  <tr>
    <td width="278" height="81" colspan="3" background="f8.gif"></td>
  </tr>





  <tr>
    <td colspan="5"><img src="p_foot.gif" width="882" height="25" /></td>
  </tr>
</table>
</form>
</body>
</html>

<?

        }

}

?>
