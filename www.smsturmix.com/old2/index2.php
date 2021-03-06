<?php
include("constants_smsturmix.php");
include("classe_interficie_llistar_continguts.php");

switch($_SERVER["HTTP_HOST"])
{
	case 'www.smsturmix.ct':	$photo_link = "http://photo.smsturmix.ct";
					$video_link = "http://video.smsturmix.ct";
					$audio_link = "http://audio.smsturmix.ct";
					$anima_link = "http://anima.smsturmix.ct";
					break;

	case 'www.mobiturmix.com':	$photo_link = "http://photo.mobiturmix.com";
                                        $video_link = "http://video.mobiturmix.com";
                                        $audio_link = "http://audio.mobiturmix.com";
                                        $anima_link = "http://anima.mobiturmix.com";
                                        break;

        case 'mobiturmix.com':		$photo_link = "http://photo.mobiturmix.com";
                                        $video_link = "http://video.mobiturmix.com";
                                        $audio_link = "http://audio.mobiturmix.com";
                                        $anima_link = "http://anima.mobiturmix.com";
                                        break;

	default:			$photo_link = "http://photo.smsturmix.com";
					$video_link = "http://video.smsturmix.com";
					$audio_link = "http://audio.smsturmix.com";
					$anima_link = "http://anima.smsturmix.com";
					break;
}

header('Content-Type: text/html; charset=UTF-8');

print XML_ENCODING."\n";
print DOCTYPE."\n";
print HTML."\n";
?><head>
<?php
print "  ".META_CONTENT_TYPE."\n";
print "  ".META_AUTHOR."\n";
print "  ".META_COPYRIGHT."\n";
print "  ".META_DESCRIPTION_ES."\n";
print "  ".META_DESCRIPTION_EN."\n";
print "  ".META_KEYWORDS_ES."\n";
print "  ".META_KEYWORDS_EN."\n";
print "  ".META_ROBOTS_INDEX_FOLLOW."\n";
print "  ".TITLE_SMSTURMIX."\n";
?>
  <link rel="shortcut icon" href="favicon.ico" />
  <style type="text/css">
   /*<![CDATA[*/
    td.photo {background: url(p_photo.png); cursor:pointer;}
    td.video {background: url(p_video.png); cursor:pointer;}
    td.audio {background: url(p_audio.png); cursor:pointer;}
    td.anima {background: url(p_anima.png); cursor:pointer;}

    .msg
    {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #999999;
	font-weight: bold;
    }
   /*]]>*/
  </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" href="css/girafa_charts.css" type="text/css" />
<link rel="stylesheet" href="css/style-defaults.css" type="text/css" />
<link rel="stylesheet" href="css/cat_style.css" type="text/css" />
<link rel="stylesheet" href="css/cat_base.css" type="text/css" />

<script type="text/javascript" src="js/mochikit/Base.js"></script>
<script type="text/javascript" src="js/mochikit/Async.js"></script>
<script type="text/javascript" src="js/mochikit/Iter.js"></script>
<script type="text/javascript" src="js/mochikit/DOM.js"></script>

<script type="text/javascript" src="js/girafatools/gbarchart.js"></script>
<script type="text/javascript" src="js/girafatools/barchart.js"></script>
</head>
<?php print BODY;?>
<script type="text/javascript">
//<![CDATA[
function gebi(id) {return document.getElementById(id);}
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
  e=gebi('m'+id);
  e.style.display=b;
  e=gebi('arr');
  e.style.display=b;
}
//]]>
</script>

<table width="905" border="0" align="center">
<tr>
<td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr" style="display:none;" alt=""/></td>
<td width="427" height="70">

<div style="display:none;" id="mpho" class="msg">
!Imagina la foto que tu quieras en tu móvil en tan solo un momento!<br>
Tu último viaje, tu artista favorito, tu pareja... Subes la foto, la ajustas,<br>
miras como te quedará en el móvil y te la descargas al momento!
</div>

<div style="display:none;" id="mvid" class="msg"/>
Te apetece ver el videoclip de tu artista favorito en tu móvil? O tal vez<br>
prefieres esa broma que le gastaste a tu amigo? O el gol que grabaste<br>
mientras estabas en el fútbol... Pon el vídeo que quieras en tu móvil!
</div>

<div style="display:none;" id="maud" class="msg"/>
Dale vida a tu móvil con una canción, un sonido, el himno de tu equipo,...<br>
Es facilsimo y divertido! nos envias el MP3 que quieras, nosotros lo<br>
adaptamos y al instante te lo enviamos a tu móvil para que lo disfrutes!
</div>

<div style="display:none;" id="mani" class="msg"/>
Customízate el móvil! Crea divertidas animaciones a partir de fotos<br>
tuyas, o entra en la comunidad <b>Turmix</b> y pon en tu móvil las animaciones<br>
más alocadas y originales de otros artistas como tu!
</div>

</td>
<td width="358" align="right"><img src="logo.png" alt=""/></td>
</tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td height="53" colspan="2"><img src="p_top.png" width="905" height="53" alt=""/></td>
</tr>
<tr>
<td width="588" rowspan="4"><img src="p_left.png" width="588" height="370" alt=""/></td>
<td class="photo" width="317" height="91" onmouseover="show('pho','block');" onmouseout="show('pho','none');"><a href="<?php print $photo_link; ?>"><img id="pho" style="display:none" src="r_photo.png" border="0" alt=""/></a></td>
</tr>
<tr>
<td class="video" width="317" height="91" onmouseover="show('vid','block');" onmouseout="show('vid','none');"><a href="<?php print $video_link; ?>"><img id="vid" style="display:none" src="r_video.png" border="0" alt=""/></a></td>
</tr>
<tr>
<td class="audio" width="317" height="91" onmouseover="show('aud','block');" onmouseout="show('aud','none');"><a href="<?php print $audio_link; ?>"><img id="aud" style="display:none" src="r_audio.png" border="0" alt=""/></a></td>
</tr>
<tr>
<td class="anima" width="317" height="97" onmouseover="show('ani','block');" onmouseout="show('ani','none');"><a href="<?php print $anima_link; ?>"><img id="ani" style="display:none" src="r_anima.png" border="0" alt=""/></a></td>
</tr>
<tr>
<td height="104" colspan="2"><img src="p_down.png" width="905" height="104" alt=""/></td>
</tr>

<!-- VIDEOS -->
<tr>
<td colspan="2">
<?php
	$llistat = new interficie_llistar_continguts();
	$llistat->mostrar_llista_continguts_frontpage(WALLPAPER);
        $llistat->mostrar_llista_continguts_frontpage(VIDEO);
//        $llistat->mostrar_llista_continguts_frontpage(ANIMATION);
?>
</td>
</tr>
<!-- END VIDEOS-->

<?php
/*
<!-- STATISTICS
<tr>
<td colspan="2">
  <div align="center" value="120" href="stats/gchart.json" id="grafic1" class="gbarchart"></div><br/>
</td>
</tr
 END STATISTICS -->
*/
?>

<tr>
<td colspan="2"><?php print FOOT_HTML_MESSAGE;?></td>
</tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
	_uacct = "UA-1595535-1";
	urchinTracker();
</script>
</body>
</html>
