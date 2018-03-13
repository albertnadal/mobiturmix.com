<?php
include("constants_smsturmix.php");
require_once("classe_sessio_usuari.php");

print XML_ENCODING."\n";
print HTML."\n";
?><head>
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
   td.sube { background-image: url(p_sube.gif); cursor: hand; cursor: pointer; }
   td.animateca { background-image: url(p_animateca.gif); cursor: hand; cursor: pointer; }
   .msg
   {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #999999;
	font-weight: bold;
   }

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

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

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>

<table width="903" height="60" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="106" align="center" valign="middle"><img src="<?php print LINK_ARROWS;?>" id="arr" style="display:none;" /></td>
<td width="427" height="60">
<div style="display:none;" id="mup" class="msg" />
Is extremely easy and fast! Make funny animations in only a few seconds.</br>
Make a custom presentation with your last trip, friends, partner or family</br>
photos. We will enable your animation for your mobile!
</div>
<div style="display:none;" id="mfot" class="msg" />
Inside the Turmix community you will find the most funny animations and</br>
animated PowerPoints for your mobile. Other users like you already make</br>
and share their own animations with all the world.
</div>
</td>
<td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border=0/></a></td>
</tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td height="53" colspan="2" align="right"><img src="p_top.gif" width="905" height="53" /></td>
</tr>
<tr>
<td rowspan="2" align="right"><img src="p_left.gif" width="589"/></td>
<td class="sube" width="316" height="182" onMouseOver="show('up','block');" onMouseOut="show('up','none');"><a href="upload.php"><img id="up" style="display:none" src="r_sube.gif" border=0/></a></td>
</tr>
<tr>
<td class="animateca" width="316" height="182" onMouseOver="show('fot','block');" onMouseOut="show('fot','none');"><a href="/"><img id="fot" style="display:none" src="r_animateca.gif" border=0/></a></td>
</tr>
<tr>
<td height="112" colspan="2"><img src="p_down.gif" width="905" height="111"></td>
</tr>
<tr>
<td colspan="2"><?php print FOOT_HTML_MESSAGE;?></td>
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
