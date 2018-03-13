<?php
include("constants_smsturmix.php");

switch($_SERVER["HTTP_HOST"])
{
	case 'www.smsturmix.ct':	$photo_link = "http://photo.smsturmix.ct";
					$video_link = "http://video.smsturmix.ct";
					$audio_link = "http://audio.smsturmix.ct";
					$anima_link = "http://anima.smsturmix.ct";
					break;

	default:			$photo_link = "http://photo.mobiturmix.com";
					$video_link = "http://video.mobiturmix.com";
					$audio_link = "http://audio.mobiturmix.com";
					$anima_link = "http://anima.mobiturmix.com";
					break;
}

header('Content-Type: text/html; charset=UTF-8');

print XML_ENCODING."\n";
print DOCTYPE."\n";
print HTML."\n";
?>
<head>
<?php
print "  ".META_CONTENT_TYPE."\n";
print "  ".META_AUTHOR."\n";
print "  ".META_COPYRIGHT."\n";
print "  ".META_DESCRIPTION_ES."\n";
print "  ".META_DESCRIPTION_EN."\n";
print "  ".META_KEYWORDS_ES."\n";
print "  ".META_KEYWORDS_EN."\n";
print "  ".META_ROBOTS_NOINDEX_FOLLOW."\n";
print "  ".TITLE_SMSTURMIX."\n";
?>
  <link rel="shortcut icon" href="favicon.ico" />
  <style type="text/css">
   /*<![CDATA[*/
    td.photo {background: url(u_photo6.png); cursor:pointer;}
    td.video {background: url(u_video6.png); cursor:pointer;}
    td.audio {background: url(u_audio6.png); cursor:pointer;}
    td.anima {background: url(u_anima6.png); cursor:pointer;}
   /*]]>*/
  </style>
</head>
<?php print BODY;?>
<script type="text/javascript">
//<![CDATA[
function gebi(id) {return document.getElementById(id);}
function show(id,b)
{
  e=gebi(id);
  e.style.display=b;
}
//]]>
</script>
<table width="903" height="80" border="0" align="center">
  <tr>
    <td width="106" align="center" valign="middle"><img src="arrows.png" id="arr" style="display:none;" /></td>
    <td width="427" height="70"><img style="display:none;" id="mup" src="m_sube.png" /><img style="display:none;" id="mfot" src="m_fototeca.png" /></td>
    <td width="358" align="right"><a href="http://www.smsturmix.com"><img src="logo.png" border="0"/></a></td>
  </tr>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.png">
    <td colspan="6" align="left"><img src="u_top.png" width="905" height="11" /></td>
  </tr>
  <tr valign="top">
    <td><img src="d0.png" width="105" height="34" /></td>
    <td class="photo" width="150" height="34" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print $photo_link; ?>';"><img style="display:none" id="pho" src="r_photo6.png"/></td>
    <td class="video" width="117" height="34" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print $video_link; ?>';"><img style="display:none" id="vid" src="r_video6.png"/></td>
    <td class="audio" width="118" height="34" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print $audio_link; ?>';"><img style="display:none" id="aud" src="r_audio6.png"/></td>
    <td class="anima" width="153" height="34" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print $anima_link; ?>';"><img style="display:none" id="ani" src="r_anima6.png"/></td>
    <td width="262" background="d1.png">&nbsp;</td>
  </tr>
</table>
  
  <table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><img src="d3.png" width="905" height="486" /></td>
  </tr>
  <tr>
    <td align="right" bordercolor="0">&nbsp;</td>
  </tr>
  <tr>
    <td><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
<!--<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
	_uacct = "UA-1595535-1";
	urchinTracker();
</script>-->
</body>
</html>
