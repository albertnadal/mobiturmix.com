<?php
include("constants_smsturmix.php");

if (isset($_GET["c"])) $codi_contingut = $_GET["c"];
elseif (isset($_POST["c"])) $codi_contingut = $_POST["c"];
else $codi_contingut = '';

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
?><head>
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
    td.photo {background: url(u_photo.gif); cursor:pointer;}
    td.video {background: url(/u_video6.png); cursor:pointer;}
    td.audio {background: url(/u_audio6.png); cursor:pointer;}
    td.anima {background: url(u_anima.gif); cursor:pointer;}
   /*]]>*/
  .Estilo1 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 24px;
}
  .Estilo10 {font-family: Arial, Helvetica, sans-serif; font-size: 24px; }
.Estilo14 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
}
.Estilo17 {font-size: 12px}
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
    <td width="106" align="center" valign="middle"></td>
    <td width="427" height="70"></td>
    <td width="358" align="right"><a href="http://www.mobiturmix.com"><img src="../logo.gif" border="0"/></a></td>
  </tr>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="u_audio.gif">
    <td colspan="6" align="left"><img src="u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr valign="top">
    <td><img src="d0.gif" width="105" height="34" /></td>
    <td class="photo" width="150" height="34" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print $photo_link; ?>';"><img style="display:none" id="pho" src="r_photo.gif"/></td>
    <td class="video" width="117" height="34" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print $video_link; ?>';"><img style="display:none" id="vid" src="/r_video6.png"/></td>
    <td class="audio" width="118" height="34" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print $audio_link; ?>';"><img style="display:none" id="aud" src="/r_audio6.png"/></td>
    <td class="anima" width="153" height="34" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print $anima_link; ?>';"><img style="display:none" id="ani" src="r_anima.gif"/></td>
    <td width="262">&nbsp;</td>
  </tr>
</table>

  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="105" align="left" valign="top"><img src="d1.gif" width="105" height="475" /></td>
    <td width="798" height="486" align="left" valign="top"><p class="Estilo1"><span class="Estilo10">Debes introducir este enlace WAP en el navegador web de tu m&oacute;vil:</span>

    <h2><center>wap.mobiturmix.com/<?php print $codi_contingut; ?>/</center></h2>

      <ol>
        <li class="Estilo14"> Estos son los pasos que deves seguir para descargarlo <strong>gratuitamente</strong>:
          <ol type="a">
            <li>Con&eacute;ctate a Internet mediante el navegador de tu m&oacute;vil.</li>
            <li>Introduce la direcci&oacute;n <strong>WAP</strong> del contenido y desc&aacute;rgatelo gratis!<br />
                <table width="100%" border="0" cellspacing="5">
                  <tr>
                    <td align="left" valign="middle"><img src="sample2.jpg" width="553" height="94" />
					<img src="sample_wap.jpg" alt="d" width="123" height="190" /></td>
                  </tr>
                </table>

            </li>
          </ol>
        </li>
       </ol>
        <p class="Estilo1"><span class="Estilo10">...o cómodamente mediante el envío de un único SMS:</span>
       <ol>
	<li class="Estilo14">Estos son los pasos que deverás seguir enviando un <strong>SMS</strong> <span class="Estilo17">(1,2 euros + i.v.a)</span>.
          <ol type="a">
            <li>Escribe la palabra <strong>etm</strong> seguido de un espacio y el c&oacute;digo <b><?php print $codi_contingut; ?></b> en un <strong>SMS</strong>.<br />
            </li>
            <li>Env&iacute;a el <strong>SMS</strong> al n&uacute;mero <strong>5767</strong> <span class="Estilo17">(Solo v&aacute;lido para las operadoras Movistar, Vodafone y Orange en españa)</span>.</li>
            <li>Recivir&aacute;s un <strong>SMS</strong> con el enlace <strong>WAP</strong> del contenido.<br />
                <table width="100%" border="0" cellspacing="5" align="left">
                  <tr>
                    <td align="left" valign="middle"><img src="sample.jpg" alt="" width="556" height="98" />
					<img src="sample_sms.jpg" alt="" width="123" height="190" /></td>
                  </tr>
                </table>
              <br />
                </li>
          </ol>
        </li>
      </ol>      </td>
  </tr>
  <tr>
    <td colspan="2" align="left" bordercolor="0"><?php print FOOT_HTML_MESSAGE;?></td>
    </tr>
</table>
</body>
</html>
