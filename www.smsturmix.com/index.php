<?php
include("constants_smsturmix.php");
include("classe_sessio_usuari.php");
include("classe_interficie_llistar_continguts.php");

session_start();

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
  <link rel="shortcut icon" href="http://www.mobiturmix.com/favicon.ico">
  <style type="text/css">
   /*<![CDATA[*/
    td.photo {background: url(p_photo.png); cursor:pointer;}
    td.photod {background: url(p_photo_d.png); cursor:pointer;}
    td.photou {background: url(p_photo_u.png); cursor:pointer;}
    td.video {background: url(p_video.png); cursor:pointer;}
    td.videod {background: url(p_video_d.png); cursor:pointer;}
    td.videou {background: url(p_video_u.png); cursor:pointer;}
    td.audio {background: url(p_audio.png); cursor:pointer;}
    td.audiou {background: url(p_audio_u.png); cursor:pointer;}
    td.audiod {background: url(p_audio_d.png); cursor:pointer;}
    td.anima {background: url(p_anima.png); cursor:pointer;}
    td.animau {background: url(p_anima_u.png); cursor:pointer;}
    td.animad {background: url(p_anima_d.png); cursor:pointer;}

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

.mtinput {
vertical-align: top;
border:1px solid #A7A6AA;
color:#000000;
font-family:Arial,sans-serif;
font-size:10px;
font-size-adjust:none;
font-stretch:normal;
font-style:normal;
font-variant:normal;
font-weight:normal;
height:15px;
line-height:normal;
margin:0pt 0px 0pt 3px;
padding:0px 0px;
width:100px;
background-image:url('http://www.mobiturmix.com/mti_bg.gif');
}

   /*]]>*/
  </style>

<link rel="stylesheet" href="cat_style.css" type="text/css">
<link rel="stylesheet" href="cat_base.css" type="text/css">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php print BODY;?>
<script type="text/javascript">
//<![CDATA[
function gebi(id) {return document.getElementById(id);}
function show(id,b)
{
  e=gebi('mini');
  if(b=='none') e.style.display='block';
  else e.style.display='none';
  e=gebi(id);
  e.style.display=b;
  e=gebi('m'+id.substring(0,3));
  e.style.display=b;
//  e=gebi('arr');
//  e.style.display=b;
}

//]]>
</script>

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_menu_superior_sessio();
?>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="905">
  <tbody>
    <tr>
      <td colspan="3">
	    <table align="center" border="0" cellpadding="0" cellspacing="0" >
        <tbody>
          <tr>
            <td align="center" valign="middle" width="106"><img src="<?php print LINK_ARROWS;?>" id="arr" alt="" /></td>
            <td height="60" width="550"><div style="display: block;" id="mini" class="msg"> 
              <img src="wimt.png"><br/>
              "user upload his own media, <span style="color: rgb(75,79,82)">MobiTurmix</span> make it worldwide accessible for users mobiles" <!--<a href="http://www.mobiturmix.com/faq/formas_de_descargar_el_contenido.php">more</a><br/>-->
            </div>
                  <div style="display: none;" id="mpho" class="msg">
			!Imagine you put the photo you want to your mobile in only a moment!<br />
			Your last trip, your favourite artist, your partner... Upload your photo,<br />
			adjust it, see how it looks like, and download to your mobile at the moment!
                  </div>
              <div style="display: none;" id="mvid" class="msg">
			Do you wish to see your favourite artist videoclip in your mobile?<br/>
			or you prefer to see the last joke you crack with a friend? or the best<br/>
			moments you recorded with your cam... put any video to your mobile!
		</div>
              <div style="display: none;" id="maud" class="msg">
			Take up your mobile with a new song, one sound, your favourite team hymn...<br/> 
			It's so easy and funny! Upload the MP3 you want, we adapt it for all<br/>
			mobile devices and stay ready to download inmediatelly!
		</div>
              <div style="display: none;" id="mani" class="msg">
			Customize your mobile! make funny animations from your own photos,<br/>
			or enter to our <b>Turmix</b> community and put to your mobile the most<br/>
			crazy and original animations made by other users like you!
		</div></td>
            <td align="right" width="200"><img src="logo.png" alt="" /></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" height="53"><img src="p_top.png" alt="" height="53" width="905" /></td>
    </tr>
    <tr>
      <td rowspan="8" width="588"><img src="p_left.png" alt="" height="370" width="588" /></td>
      <td colspan="2" class="photo" onmouseover="show('pho','block');" onmouseout="show('pho','none');" height="66" width="317"><a href="<?php print $photo_link; ?>/select.php"><img id="pho" style="display: none;" src="r_photo.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td class="photod" onmouseover="show('phod','block');" onmouseout="show('phod','none');" height="25" width="239"><a href="<?php print $photo_link; ?>"><img id="phod" style="display: none;" src="r_photo_d.png" alt="" border="0" /></a></td>
      <td class="photou" onmouseover="show('phou','block');" onmouseout="show('phou','none');" height="25" width="78"><a href="<?php print $photo_link; ?>/upload.php"><img id="phou" style="display: none;" src="r_photo_u.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td colspan="2" class="video" onmouseover="show('vid','block');" onmouseout="show('vid','none');" height="65" width="317"><a href="<?php print $video_link; ?>/select.php"><img id="vid" style="display: none;" src="r_video.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td class="videod" onmouseover="show('vidd','block');" onmouseout="show('vidd','none');" height="26" width="239"><a href="<?php print $video_link; ?>"><img id="vidd" style="display: none;" src="r_video_d.png" alt="" border="0" /></a></td>
      <td class="videou" onmouseover="show('vidu','block');" onmouseout="show('vidu','none');" height="26" width="78"><a href="<?php print $video_link; ?>/upload.php"><img id="vidu" style="display: none;" src="r_video_u.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td colspan="2" class="audio" onmouseover="show('aud','block');" onmouseout="show('aud','none');" height="64" width="317"><a href="<?php print $audio_link; ?>"><img id="aud" style="display: none;" src="r_audio.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td class="audiod" onmouseover="show('audd','block');" onmouseout="show('audd','none');" height="27" width="239"><a href="<?php print $audio_link; ?>/ft.php"><img id="audd" style="display: none;" src="r_audio_d.png" alt="" border="0" /></a></td>
      <td class="audiou" onmouseover="show('audu','block');" onmouseout="show('audu','none');" height="27" width="78"><a href="<?php print $audio_link; ?>/upload.php"><img id="audu" style="display: none;" src="r_audio_u.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td colspan="2" class="anima" onmouseover="show('ani','block');" onmouseout="show('ani','none');" height="66" width="317"><a href="<?php print $anima_link; ?>/select.php"><img id="ani" style="display: none;" src="r_anima.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td class="animad" onmouseover="show('anid','block');" onmouseout="show('anid','none');" height="31" width="239"><a href="<?php print $anima_link; ?>"><img id="anid" style="display: none;" src="r_anima_d.png" alt="" border="0" /></a></td>
      <td class="animau" onmouseover="show('aniu','block');" onmouseout="show('aniu','none');" height="31" width="78"><a href="<?php print $anima_link; ?>/upload.php"><img id="aniu" style="display: none;" src="r_anima_u.png" alt="" border="0" /></a></td>
    </tr>
    <tr>
      <td colspan="3" height="104"><img src="p_down.png" alt="" height="104" width="905" /></td>
    </tr>
    <tr>
      <td colspan="3"><?php print FOOT_HTML_MESSAGE;?></td>
    </tr>
  </tbody>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
	_uacct = "UA-1595535-1";
	urchinTracker();
</script>
</body>
</html>
