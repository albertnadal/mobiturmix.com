<?php
include("constants_smsturmix.php");

print XML_ENCODING."\n";
print HTML."\n";
?>
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
   td.sube { background-image: url(p_sube.gif); cursor: hand; cursor: pointer; }
   td.fototeca { background-image: url(p_fototeca.gif); cursor: hand; cursor: pointer; }
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
<td width="427" height="70"><img style="display:none;" id="mup" src="m_sube.gif" /><img style="display:none;" id="mfot" src="m_fototeca.gif" /></td>
<td width="358" align="right"><a href="<?php print WWW_DOMAIN; ?>"><img src="<?php print LINK_LOGO;?>" border=0/></a></td>
</tr>
</table>
<table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td height="53" colspan="2" align="right"><img src="p_top.gif" width="905" height="53" /></td>
</tr>
<tr>
<td height="373" rowspan="2" align="right" style="background-image: url('p_left.gif');"><img src="p_left.gif" width="585" height="373"></td>
<td width="319" height="182" align="left" class="sube" onMouseOver="show('up','block');" onMouseOut="show('up','none');"><a href="upload.php"><img id="up" style="display:none" src="r_sube.gif" width="319" border=0/></a></td>
</tr>
<tr>
<td width="319" height="191" align="left" class="fototeca" onMouseOver="show('fot','block');" onMouseOut="show('fot','none');"><a href="ft.php"><img id="fot" style="display:none" src="r_fototeca.gif" width="319" border=0/></a></td>
</tr>
<tr>
<td height="112"><img src="p_down0.gif" width="586" height="111"></td>
<td height="112"><img src="p_down1.gif" width="319" height="111"></td>
</tr>
<tr>
<td colspan="2"><?php print FOOT_HTML_MESSAGE;?></td>
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
