<?php
include("../include/constants_smsturmix.php");

print XML_ENCODING."\n";
print HTML."\n";
?><head>
<?php
print META_CONTENT_TYPE."\n";
print META_AUTHOR."\n";
print META_COPYRIGHT."\n";
print META_DESCRIPTION_AUDIO_ES."\n";
print META_DESCRIPTION_AUDIO_EN."\n";
print META_KEYWORDS_ES."\n";
print META_KEYWORDS_EN."\n";
print META_ROBOTS_INDEX_NOFOLLOW."\n";
print TITLE_AUDIOTURMIX."\n";
?>
  <link rel="shortcut icon" href="favicon.ico" />
  <style type="text/css">
  /*<![CDATA[*/
   td.sube { background: url(p_sube.gif); cursor: pointer;}
   td.audioteca { background: url(p_audioteca.gif); cursor: pointer;}
   .msg
   {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #999999;
	font-weight: bold;
   }
  /*]]>*/
  </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<?php print BODY; ?>
<script type="text/javascript">
//<![CDATA[
function gebi(id) { return document.getElementById(id); }
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

<table width="903" height="80" border="0" align="center">
<tr>
<td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr" style="display:none;" /></td>
<td width="427" height="70">
<div style="display:none;" id="mup" class="msg" />
Si quieres que tu canción favorita esté en tu móvil, has dado en el clavo.</br>
La subes a nuestra base de datos y en unos instantes ya la dendremos</br>
disponible para que te la puedas descargar a tu móvil. ¡Aprovéchalo!
</div>
<div style="display:none;" id="mfot" class="msg" />
Descárgate todas las canciones que quieras de nuestra comunidad</br>
<b>Turmix</b> y así podrás disfrutar de toda la música que nuestros</br>
artistas han subido para ti. ¡No te lo pienses más, entra ya!
</div>
</td>
<td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border="0"/></a></td>
</tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td height="53" colspan="2" align="right"><img src="p_top.gif" width="905" height="53" /></td>
</tr>
<tr>
<td rowspan="3" align="right" valign="top"><img src="p_left.gif" width="589" height="470" /></td>
<td class="sube" width="319" height="182" onMouseOver="show('up','block');" onMouseOut="show('up','none');"><a href="do.php"><img id="up" style="display:none" src="r_sube.gif" border="0"/></a></td>
</tr>
<tr>
<td width="319" height="182" class="audioteca" onMouseOver="show('fot','block');" onMouseOut="show('fot','none');"><a href="ft.php"><img id="fot" style="display:none" src="r_audioteca.gif" border="0"/></a></td>
</tr>
<tr>
  <td height="99"><img src="p_down.gif" width="316" height="119"></td>
</tr>
<tr>
<td colspan="2"><?php print FOOT_HTML_MESSAGE;?></td>
</tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-1595535-5";
urchinTracker();
</script>
</body>
</html>
