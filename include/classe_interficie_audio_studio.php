<?
require('../include/crop_canvas/class.cropinterface.php');
require('../include/classe_interficie_smsturmix.php');
require('../include/constants_smsturmix.php');
require_once("../DB/conexio_bd.php");

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

class interficie_video_studio extends interficie_smsturmix
{
        function interficie_video_studio ()
        {

        }

        function obtenir_continguts_categoria($id_mm_categoria, $cerca, $max=16)
        {
                $con_bd = new conexio_bd();
                switch($id_mm_categoria)
                {
                        case MES_RECENT:        $tokens = explode(" ", $cerca);
                                                if(count($tokens))
                                                {
                                                        $sql = "select mm.id_mm, mm.codi_contingut, mm.data_insert
                                                                from mm mm, mm_contingut_original mmco
                                                                where mm.id_mm = mmco.id_mm
									and mmco.video_codec = 'flv'
									and mm.id_categoria_contingut = 3 and ( ";
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

                                                        $sql = "select id_mm, codi_contingut, data_insert
                                                                from mm
                                                                order by data_insert desc
                                                                limit 0, $max";
                                                }
                                                break;

                        default:                $sql = "select mm.id_mm, mm.codi_contingut, mm.data_insert
                                                        from mm mm, mm_categoria_mm mcm
                                                        where mm.id_mm = mcm.id_mm
                                                                and mcm.id_mm_categoria = $id_mm_categoria
								and mm.id_categoria_contingut = 3
                                                        order by mm.data_insert desc, mcm.data_insert desc
                                                        limit 0, $max";
                                                break;
                }
                $res = $con_bd->sql_query($sql);
                return $res;
        }

        function mostrar_panell_fototeca($cerca)
        {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_VIDEO_ES."\n";
print META_DESCRIPTION_VIDEO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_VIDEOTURMIX."\n";
?>
        <link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
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
  </style>
</head>
<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[

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
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"></td>
    <td width="427" height="70"></td>
    <td width="358" align="right"><a href="http://www.smsturmix.com"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="a0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.smsturmix.com';"><img style="display:none" id="pho" src="r_photo6.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.smsturmix.com';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.smsturmix.com';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.smsturmix.com';"><img style="display:none" id="ani" src="r_anima3.gif"/></td>
    <td width="43" background="a1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" value="<?php print $cerca; ?>"/></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Buscar fotos" title="Buscar fotos" src="bcerca.gif" height="34" onclick="window.location='ft.php?s='+URLEncode(document.getElementById('search').value);" /><img src="a2.gif" width="41" height="34" /></td>
  </tr>
</table>


  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="105" rowspan="14" align="left" valign="top"><img src="a3.gif"></td>
    <td width="170" height="50" align="center" valign="bottom"><img src="a4.gif" width="135" height="15" /></td>
    <td width="631" height="490" colspan="3" rowspan="14" align="right" valign="top">
      <IFRAME SRC="ftc.php<?php if($cerca) print "?s=".(urlencode($cerca)); ?>" NAME="m" id="m" HEIGHT="465\" WIDTH="625" FRAMEBORDER=0>Sorry, your browser doesn't support iframes.</IFRAME>
    </td>
  </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print MES_RECENT; ?>" target="m"><img src="a5.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><img src="a6.gif" width="135" height="15" /></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_AMOR; ?>" target="m"><img src="a8.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_DEPORTE; ?>" target="m"><img src="a7.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_MUSICA; ?>" target="m"><img src="a9.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_AMIGOS; ?>" target="m"><img src="a10.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="0" align="center" valign="bottom"></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_FAMILIA; ?>" target="m"><img src="a11.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_PAISAJES; ?>" target="m"><img src="a12.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_VIAJES; ?>" target="m"><img src="a13.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_DIVERTIDA; ?>" target="m"><img src="a14.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_EROTICA; ?>" target="m"><img src="a15.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td width="170" height="101" align="left" valign="top"><img src="170.gif" width="170"></td>
    </tr>
  <tr>
    <td colspan="6"><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
</body>
</html>
<?php
        }

        function mostrar_categoria_videoteca($categoria=MES_RECENT, $cerca='')
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

		  print "</head>\n";
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

        	                        print "\t<td align=\"center\"><div id=\"foto\"><a href=\"info.php?c=$codi_contingut\"><img src=\"pr.php?c=$codi_contingut\" border=2></br></br><b>$codi_contingut</b> <small>($data_insert)</small></a></div></td>\n";
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

        function obtenir_info_video($codi_contingut)
        {
                $con_bd = new conexio_bd();
                $sql = "select id_mm, codi_contingut, descripcio
                        from mm
                        where codi_contingut='$codi_contingut'";
                $res = $con_bd->sql_query($sql);
                return $res;
        }

        function mostrar_info_video($codi_contingut, $imh, $ih)
        {
                $info = $this->obtenir_info_video($codi_contingut);
                $video = $info->fetchRow();
                $id_mm = $video['id_mm'];
                $codi_contingut = $video['codi_contingut'];
                $descripcio = $video['descripcio'];
                $data_insert = $video['data_insert'];

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

print '
  <style type="text/css">
  /*<![CDATA[*/
.text {font-family: Verdana, Arial, Helvetica, sans-serif}
.txt1 {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        color: #D3FF3E;
        font-weight: bold;
        font-size: 17px;
}
.txt2 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #D3FF3E; font-weight: bold; font-size: 14px; }
.txt3 {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        color: #D3FF3E;
        font-size: 21px;
}
  </style>';

                print "</head>\n";
                print BODY."\n";
                print "<table align=\"center\" border=0 cellspacing=\"5%\">\n";
                print "<tr>\n";
                print "\t<td valign=\"top\" align=\"right\">\n";
                print "\t\t<table border=0><tr><td>\n";

		print "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\" id=\"320x240\" align=\"middle\" height=\"168\" width=\"224\">\n";
		print "<param name=\"allowFlashAutoInstall\" value=\"true\">\n";
		print "<param name=\"Flashvars\" value=\"url=flv.php?c=$codi_contingut\">\n";
		print "<param name=\"allowScriptAccess\" value=\"sameDomain\">\n";
		print "<param name=\"movie\" value=\"320x240.swf\">\n";
		print "<param name=\"quality\" value=\"high\">\n";
		print "<param name=\"bgcolor\" value=\"#ffffff\">\n";
		print "<embed src=\"320x240.swf\" swliveconnect=\"true\" flashvars=\"url=flv.php?c=$codi_contingut\" quality=\"high\" bgcolor=\"#ffffff\" name=\"320x240\" allowscriptaccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" align=\"middle\" height=\"168\" width=\"224\">\n";
		print "</object>";


		print "</td><td valign=\"bottom\">&nbsp;\n";
                print "<a href=\"javascript:void 0;\" onclick=\"history.back();\"><img src=\"n8.gif\" border=0 alt=\"Volver\" title=\"Volver\"></a></td></tr></table></br>";

                print "\t\t<table width=\"397\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
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
                print "\t\t</table>\n";

                print "\t</td>\n";
                print "</tr>\n";
                print "</table>\n";
                print "</body>\n";
                print "</html>\n";
        }

        function mostrar_panell_videoteca($cerca)
        {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_VIDEO_ES."\n";
print META_DESCRIPTION_VIDEO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_VIDEOTURMIX."\n";
?>
        <link rel="shortcut icon" href="favicon.ico" />

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
  /*]]>*/
  </style>
</head>
<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[

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
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"></td>
    <td width="427" height="70"></td>
    <td width="358" align="right"><a href="http://www.smsturmix.com"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="a0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.smsturmix.com';"><img style="display:none" id="pho" src="r_photo6.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.smsturmix.com';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.smsturmix.com';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.smsturmix.com';"><img style="display:none" id="ani" src="r_anima3.gif"/></td>
    <td width="43" background="a1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input id="search" type="text" size="17" maxlength="40" value="<?php print $cerca; ?>"/></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Buscar fotos" title="Buscar fotos" src="bcerca.gif" height="34" onclick="window.location='ft.php?s='+URLEncode(document.getElementById('search').value);" /><img src="a2.gif" width="41" height="34" /></td>
  </tr>
</table>


  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="105" rowspan="14" align="left" valign="top"><img src="a3.gif"></td>
    <td width="170" height="50" align="center" valign="bottom"><img src="a4.gif" width="135" height="15" /></td>
    <td width="631" height="490" colspan="3" rowspan="14" align="right" valign="top">
      <IFRAME SRC="ftc.php<?php if($cerca) print "?s=".(urlencode($cerca)); ?>" NAME="m" id="m" HEIGHT="465\" WIDTH="625" FRAMEBORDER=0>Sorry, your browser doesn't support iframes.</IFRAME>
    </td>
  </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print MES_RECENT; ?>" target="m"><img src="a5.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><img src="a6.gif" width="135" height="15" /></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_AMOR; ?>" target="m"><img src="a8.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_DEPORTE; ?>" target="m"><img src="a7.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_MUSICA; ?>" target="m"><img src="a9.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_AMIGOS; ?>" target="m"><img src="a10.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="0" align="center" valign="bottom"></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_FAMILIA; ?>" target="m"><img src="a11.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_PAISAJES; ?>" target="m"><img src="a12.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_VIAJES; ?>" target="m"><img src="a13.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_DIVERTIDA; ?>" target="m"><img src="a14.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td height="30" align="center" valign="bottom"><a href="ftc.php?c=<?php print CATEGORIA_EROTICA; ?>" target="m"><img src="a15.gif" width="135" height="15" border="0"/></a></td>
    </tr>
  <tr>
    <td width="170" height="101" align="left" valign="top"><img src="170.gif" width="170"></td>
    </tr>
  <tr>
    <td colspan="6"><?php
	print FOOT_HTML_MESSAGE;
?>php    </td>
  </tr>
</table>
</body>
</html>
<?php
        }

        function mostrar_panell_puijada_video()
        {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_VIDEO_ES."\n";
print META_DESCRIPTION_VIDEO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_VIDEOTURMIX."\n";
?>
        <link rel="shortcut icon" href="favicon.ico" />

  <style type="text/css">
  /*<![CDATA[*/
   td.photo { background: url(u_photo.gif); cursor: hand; cursor: pointer;}
   td.video { background: url(u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(u_anima.gif); cursor: hand; cursor: pointer;}
  /*]]>*/
  </style>
</head>
<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
function gebi(id) { return document.getElementById(id); }
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
//]]>
</script>
<form action="#" method="post" name="upload_form" enctype="multipart/form-data">
<input type="hidden" name="a" value="s">
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr" style="display:none;" /></td>
    <td width="427" height="70"><img style="display:none;" id="mup" src="m_sube.gif" /><img style="display:none;" id="mfot" src="m_fototeca.gif" /></td>
    <td width="358" align="right"><a href="http://www.smsturmix.com"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr valign="top">
    <td width="105" background="c0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.smsturmix.com';"><img style="display:none" id="pho" src="r_photo.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.smsturmix.com';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.smsturmix.com';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.smsturmix.com';"><img style="display:none" id="ani" src="r_anima.gif"/></td>
    <td width="43" background="c1.gif"></td>
    <td width="141" align="center" valign="middle" background="cerca.gif"><input name="textfield" type="text" dir="ltr" lang="es" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Buscar vídeos" title="Buscar vídeos" src="bcerca.gif" widt"37" height="34" /><img src="c2.gif" width="41" height="34" /></td>
  </tr>
</table>

  <table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left"><img src="c3.gif" width="905" height="201" /></td>
  </tr>
  <tr>
    <td height="40" align="right" bordercolor="0" background="c4.gif"><input type="file" name="file" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
  <tr>
    <td><img src="c8.gif" width="724" height="33" /><img style="cursor:pointer;cursor:hand;" alt="Subir vídeo" title="Subir  vídeo" src="c9.gif" width="32" height="33"  onclick="document.upload_form.submit();"/><img src="c10.gif" width="149" height="33" /></td>
  </tr>
  <tr>
    <td><img src="c11.gif" width="905" height="204" /></td>
  </tr>
  <tr>
    <td><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
</form>
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
print META_DESCRIPTION_VIDEO_ES."\n";
print META_DESCRIPTION_VIDEO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_VIDEOTURMIX."\n";
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
  </style>
</head>
<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
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
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"></td>
    <td width="427" height="70"></td>
    <td width="358" align="right"><a href="http://www.smsturmix.com"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="e0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.smsturmix.com';"><img style="display:none" id="pho" src="r_photo5.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.smsturmix.com';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.smsturmix.com';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.smsturmix.com';"><img style="display:none" id="ani" src="r_anima5.gif"/></td>
    <td width="43" background="e1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input name="textfield" type="text" dir="ltr" lang="es" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Buscar fotos" title="Buscar fotos" src="bcerca.gif" height="34" /><img src="e2.gif" width="41" height="34" /></td>
  </tr>
</table>


  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="166" colspan="2" background="e3.gif"></td>
  </tr>
  <tr>
    <td width="623" height="53" background="e4.gif"></td>
    <td width="283" background="e6.gif"><span class="Estilo4">etm&nbsp;&nbsp;<? print $_SESSION["usuari_video"]->codi_contingut; ?></span></td>
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
    <td height="44" colspan="2"><img src="e10.gif" width="830" height="44" /><img style="cursor:pointer;cursor:hand;" alt="Finalizar" title="Finalizar" src="e11.gif" width="44" height="44" onclick="document.location.href='http://video.smsturmix.com';"/><img src="e12.gif" width="31" height="44" /></td>
  </tr>
  <tr>
    <td height="110" colspan="2" background="e13.gif"></td>
  </tr>
  <tr>
    <td colspan="4"><?php print FOOT_HTML_MESSAGE;?>php</td>
  </tr>
</table>
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

                $sql = "
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
print META_DESCRIPTION_VIDEO_ES."\n";
print META_DESCRIPTION_VIDEO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_VIDEOTURMIX."\n";
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
  /*]]>*/
  </style>
</head>

<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
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
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"></td>
    <td width="427" height="70"></td>
    <td width="358" align="right"><a href="http://www.smsturmix.com"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="p0.gif">&nbsp;</td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.smsturmix.com';"><img style="display:none" id="pho" src="r_photo3.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.smsturmix.com';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.smsturmix.com';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.smsturmix.com';"><img style="display:none" id="ani" src="r_anima3.gif"/></td>
    <td width="43" background="p1.gif">&nbsp;</td>
    <td width="141" valign="middle" background="cerca.gif"><input name="textfield" type="text" dir="ltr" lang="es" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Buscar animaciones" title="Buscar animaciones" src="bcerca.gif" height="34" /><img src="p2.gif" width="41" height="34" /></td>
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

        print " <select name=\"imh\" style=\"width:130px;\" onchange=\"window.location='do.php?imh='+this.options[this.selectedIndex].value;\">\n";
        $marques = array(1,4,6,2,7,15,23,5,8,12,10,3);

        foreach($marques as $id_marca_handset)
        {
                $marca=$this->obtenir_nom_marca_handset($id_marca_handset);
                if($id_marca_handset==$imh) $selected="selected"; else $selected="";
                print "<option value=\"$id_marca_handset\" $selected>$marca\n";
        }
        print "</select>\n";

        print " <select name=\"ih\" style=\"width:130px;\" onchange=\"window.location='do.php?imh=$imh&ih='+this.options[this.selectedIndex].value;\">\n";
        $handsets = $this->obtenir_handsets_marca_amb_preview($imh, AUDIO);
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

                $_SESSION["usuari_video"]->generar_preview_handset($imatge_handset, $x_ini, $y_ini, $x_fi, $y_fi, $orientacio);
        }
?>
    </td>
    <td width="272" rowspan="4" align="center"><?php
        if(($llarg>425)&&($ample>250)) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_video"]->codi_usuari)."\" width=250 height=425>";
        else if($llarg>425) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_video"]->codi_usuari)."\" height=425>";
        else if($ample>250) print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_video"]->codi_usuari)."\" width=250>";
        else print "<img src=\"preview.php?ih=$ih&id=".($_SESSION["usuari_video"]->codi_usuari)."\">";
        ?></td>
  </tr>
  <tr>
    <td height="329" align="left" valign="top" background="p4.gif"></td>
  </tr>
  <tr>
    <td height="54" align="left" valign="top"><img src="p5.gif" width="331" height="54" /><img src="p6.gif" style="cursor:pointer;cursor:hand;" alt="Anterior" title="Anterior" width="59" height="54" onclick="cc_Submit('a');"/><img src="p7.gif" style="cursor:pointer;cursor:hand;" title="Cancelar" alt="Cancelar" width="64" height="54" /><img src="p8.gif" style="cursor:pointer;cursor:hand;" title="Siguiente" alt="Siguiente" width="63" height="54" onclick="cc_Submit('s');"/><img src="p9.gif" width="115" height="54" /></td>
  </tr>
  <tr>
    <td width="633" height="68" align="left" valign="top" background="p10.gif"></td>
        </tr>
  <tr>
    <td colspan="3"><?php print FOOT_HTML_MESSAGE;?>php</td>
  </tr>
</table>
</body>
</html>
<?php

        }

        function mostrar_panell_entrar_metainformacio()
        {
                $codi_contingut = $_SESSION["usuari_video"]->generar_codi_contingut_disponible();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_VIDEO_ES."\n";
print META_DESCRIPTION_VIDEO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_VIDEOTURMIX."\n";
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
  /*]]>*/
  </style>
</head>

<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
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
<form method="POST" action="#" name="metainfo">
<input type="hidden" name="a" value="">
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr"/></td>
    <td width="427" height="70"><img id="mfor" src="m_formulari.gif" /><img style="display:none;" id="mfot" src="m_fototeca.gif" /></td>
    <td width="358" align="right"><a href="http://www.smsturmix.com"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
  </tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="8" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr align="left" valign="top">
    <td width="105" background="f0.gif"></td>
    <td width="150" height="34" class="photo" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.smsturmix.com';"><img style="display:none" id="pho" src="r_photo4.gif"/></td>
    <td width="117" height="34" class="video" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.smsturmix.com';"><img style="display:none" id="vid" src="r_video.gif"/></td>
    <td width="118" height="34" class="audio" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.smsturmix.com';"><img style="display:none" id="aud" src="r_audio.gif"/></td>
    <td width="153" height="34" class="anima" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.smsturmix.com';"><img style="display:none" id="ani" src="r_anima4.gif"/></td>
    <td width="43" background="f1.gif"></td>
    <td width="141" valign="middle" background="cerca.gif"><input name="textfield" type="text" dir="ltr" lang="es" size="17" maxlength="40" /></td>
    <td width="78"><img style="cursor:pointer;cursor:hand;" alt="Buscar fotos" title="Buscar fotos" src="bcerca.gif" height="34" /><img src="f2.gif" width="41" height="34" /></td>
  </tr>
</table>


  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="627" height="483" rowspan="9" align="left" valign="top" background="f3.gif"></td>
    <td width="278" height="58" colspan="3" align="right" valign="bottom" background="f4.gif"><img style="display:none" id="back" src="r12_back.gif" width="219" height="60" /><img style="display:none" id="cancel" src="r12_cancel.gif" width="219" height="60" /><img style="display:none" id="next" src="r12_next.gif" width="219" height="60" /><input name="public" type="checkbox" value="1" checked="checked" />
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
    <td height="45" colspan="3"><img src="f9.gif" width="65" height="45" /><img src="f10.gif" style="cursor:pointer;cursor:hand;" title="Anterior" alt="Anterior" width="50" height="45" onclick="cc_Submit('a');"/><img src="f11.gif"  style="cursor:pointer;cursor:hand;" title="Cancelar" alt="Cancelar"  width="54" height="45" /><img src="f12.gif"  style="cursor:pointer;cursor:hand;" title="Siguiente" alt="Siguiente" width="49" height="45" onclick="cc_Submit('s');"/><img src="f13.gif" width="60" height="45" /></td>
  </tr>
  <tr>
    <td width="278" height="81" colspan="3" background="f8.gif"></td>
  </tr>
  <tr>
    <td colspan="5"><?php print FOOT_HTML_MESSAGE;?>php</td>
  </tr>
</table>
</form>
</body>
</html>

<?

        }

}

?>
