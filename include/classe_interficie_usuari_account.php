<?php
require('classe_interficie_smsturmix.php');
require_once("classe_sessio_usuari.php");
require('constants_smsturmix.php');
require_once("conexio_bd.php");

class interficie_usuari_account extends interficie_smsturmix
{
        function interficie_usuari_account()
        {
		session_start();
        }

	function show_user_account()
	{

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
    td.photo {background: url(u_photo2.gif); cursor:pointer;}
    td.video {background: url(/u_video6.png); cursor:pointer;}
    td.audio {background: url(/u_audio6.png); cursor:pointer;}
    td.anima {background: url(u_anima.gif); cursor:pointer;}
   /*]]>*/

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  .Estilo1 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 24px;
}
  .Estilo10 {font-family: Arial, Helvetica, sans-serif; font-size: 22px; }
.Estilo14 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
}
.Estilo13 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
}
.Estilo17 {font-size: 12px}
  </style>

<link rel="stylesheet" href="http://www.mobiturmix.com/css/cat_style.css" type="text/css" />
<link rel="stylesheet" href="http://www.mobiturmix.com/css/cat_base.css" type="text/css" />
<link rel="stylesheet" href="http://www.mobiturmix.com/css/cat_panells.css" type="text/css" />
<script src="http://www.mobiturmix.com/js/mochikit/packed/mochikit.js"></script>
<script src="http://www.mobiturmix.com/js/girafatools/html_loader.js"></script>

</head>
		<?php /*print BODY;*/ ?>
<body onload="load_map()" onunload="GUnload()" style="margin: 0px; background-color: rgb(255, 255, 255);">

<?php
        $info = $_SESSION["sessio_usuari"]->obtenir_informacio_usuari();
        $name = $info['name'];
        $gender = $info['gender'];
        $email = $info['email'];
        $birthdate = $info['birthdate'];
        $web = $info['web'];
        $relation = $info['relationship'];
        $country = $info['country'];
        $state = $info['state'];
        $city = $info['city'];
        $handset = $info['handset'];
        $coordinates = $info['coordinates'];
        $zoom = $info['zoom'];
?>

<script src="http://maps.google.com/maps?file=api&v=2.x&key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
var map = null;

function load_map()
{
  if (GBrowserIsCompatible())
  {
    map = new GMap2(gebi("mtcGoogleMap"));
    map.setCenter(new GLatLng(<?php print "$coordinates"; ?>), <?php print "$zoom"; ?>);
  }
}
function show_info(id, area)
{
        e=gebi(area);
        e.innerHTML='<center><h2><img src="http://www.mobiturmix.com/loading.gif"> Please, wait while loading...</h2></center>';
        e.style.display='block';
        initHtmlLoader('http://www.mobiturmix.com/ajax.php?do=get_info_content_html&c='+id, area, '');
}
function sp(id) {show_info(id, 'photoInfo');}
function sv(id) {show_info(id, 'videoInfo');}
function omovr(e) {e.style.opacity=0.6;}
function omout(e) {e.style.opacity=1;}
function gebi(id) {return document.getElementById(id);}
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
    <td class="photo" width="150" height="34" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='<?php print $photo_link; ?>';"><img style="display:none" id="pho" src="r_photo2.gif"/></td>
    <td class="video" width="117" height="34" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='<?php print $video_link; ?>';"><img style="display:none" id="vid" src="/r_video6.png"/></td>
    <td class="audio" width="118" height="34" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='<?php print $audio_link; ?>';"><img style="display:none" id="aud" src="/r_audio6.png"/></td>
    <td class="anima" width="153" height="34" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='<?php print $anima_link; ?>';"><img style="display:none" id="ani" src="r_anima.gif"/></td>
    <td width="262" valign="middle" align="center">
	<a href="#my_photos"><img border=0 src="/accounts/my_photos.gif"></a>
	<a href="#my_videos"><img border=0 src="/accounts/my_videos.gif"></a>
	<!--<img src="/accounts/my_audios.gif">-->
	<a href="#my_animations"><img border=0 src="/accounts/my_animations.gif"></a>
    </td>
  </tr>
</table>

  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="105" align="left" valign="top"><img src="d1.gif" width="105" height="475" /></td>
    <td width="798" height="486" align="left" valign="top">
      <!--<p class="Estilo1" align="center">-->
      <br/>
        <span class="Estilo10"><?php print utf8_encode("Bienvenido a tu espacio personal, aquí podrás administrar todos tus contenidos!"); ?></span>
      <br/><br/>
      <!--</p>-->


	<!-- Informació personal de l'usuari  -->
      <table border=0 style="border-collapse:collapse;width:100%;" background="ma_bar_bg.gif">
      <tr>
	<td class="Estilo14" style="width:90%">
		<strong>&nbsp;Personal Information:</strong> <?php print $_SESSION["sessio_usuari"]->login; ?>
	</td>
<!--	<td style="width:10%;" align="center"><a href=""><img alt="Setup" title="Setup" border=0 src="setup.gif"></a></td>-->
      </tr>
      </table>

      <table border=0 style="border-collapse:collapse;width:100%;margin-top:5px;" class="Estilo13">
      <tr>
	<td valign="middle" align="center" width="125px">
		<img src="http://www.mobiturmix.com/users.php?op=get_avatar&user=<?php print $_SESSION["sessio_usuari"]->login; ?>" width="100px" height="100px">
	</td>
	<td>
<?php
	$info = $_SESSION["sessio_usuari"]->obtenir_informacio_usuari();
	$name = $info['name'];
	$gender = $info['gender'];
	$email = $info['email'];
	$birthdate = $info['birthdate'];
	$web = $info['web'];
	$relation = $info['relationship'];
	$country = $info['country'];
	$state = $info['state'];
	$city = $info['city'];
	$handset = $info['handset'];
	$coordinates = $info['coordinates'];
	$zoom = $info['zoom'];

	print "		<strong>Name:</strong> $name<br/>";
        print "                <strong>Gender:</strong> $gender<br/>";
        print "                <strong>Email:</strong> $email<br/>";
        print "		<strong>Birthdate:</strong> $birthdate<br/>";
        print "		<strong>Web:</strong> $web<br/>";
        print "	</td>";
        print "        <td>";
        print "                <strong>Relationship:</strong> $relation<br/>";
        print "                <strong>Country:</strong> $country<br/>";
        print "                <strong>State:</strong> $state<br/>";
        print "                <strong>City:</strong> $city<br/>";
        print "                <strong>Mobile:</strong> $handset<br/>";
        print "        </td>";
        print "	<td valign=\"middle\" align=\"center\" width=\"290px\">";
        print "		<div id=\"mtcGoogleMap\" style=\"width:285px;height:100px;\"></div>\n";
        print "	</td>";
?>
      </tr>
      </table>
       <!-- Fi informació personal de l'usuari  -->



        <!-- My Photos  -->
<?php
        $fotos = $_SESSION["sessio_usuari"]->obtenir_fotos_usuari();
?>
      <a name="my_photos"></a>
      <table border=0 style="border-collapse:collapse;width:100%;margin-top:30px;" background="ma_bar_bg.gif">
      <tr>
        <td class="Estilo14" style="width:90%">
                <strong>&nbsp;My photos</strong><?php $total_fotos = count($fotos); print "&nbsp;<span class=\"Estilo13\">($total_fotos photos)</span>"; ?>
        </td>
        <td style="width:10%;" align="center">
<!--		<a href=""><img alt="Setup" title="Setup" border=0 src="setup.gif"></a>-->
	</td>
      </tr>
      </table>

      <div id="photoInfo" style="display:none; border-width:1px 1px 1px 1px;border-style: dotted; background-color:#FFFFCC;"></div>

      <table border=0 style="border-collapse:collapse;width:100%;margin-top:5px;font-size:9px;" class="Estilo13">
<?php
        $fotos_per_fila = 9;
        $col = 0;
        if(count($fotos)) print "<tr>";
        foreach($fotos as $codi_contingut => $foto)
        {
		if($col>=$fotos_per_fila)
		{
			print "</tr><tr>\n";
			$col=0;
		}
		$estat = $foto['estat'];
		if($estat=='APROVAT') $f=2; else $f=3;
		print "<td style=\"background-repeat:no-repeat;cursor:hand;cursor:pointer;\" valign=\"bottom\" align=\"center\" width=\"90px\" height=\"107\" background=\"http://photo.mobiturmix.com/pr.php?c=$codi_contingut&f=$f\" onmouseover=\"omovr(this);\" onmouseout=\"omout(this);\">\n";

		print "<table style=\"font-size:9px;height:100%;border-collapse:collapse;\" cellSpacing=0 cellPadding=0 border=0 onclick=\"sp('$codi_contingut');\">\n";
		print "<tr>\n";
		print "<td style=\"vertical-align:top;\" height=\"70\"><br/><!--<input type=\"checkbox\" name=\"b\">--></td>";
		print "</tr><tr>\n";
		print "<td style=\"text-align:center;vertical-align:bottom;\" height=\"10\">\n";

		$puntuacio = $foto['puntuacio'];
		for($i=1; $i<=5; $i++)
		{
			if($i<=$puntuacio) print "\t\t\t\t\t\t\t\t<img src=\"/star.gif\">\n";
			else print "\t\t\t\t\t\t\t\t<img src=\"/stare.gif\">\n";
		}

		print "</td></tr>\n";
		print "<tr><td height=\"12\" align=\"center\">0 comments</td></tr>\n";
		print "</table>\n";

		$col++;
        }
	while($col<$fotos_per_fila)
	{
		print "<td></td>\n";
		$col++;
	}
	if(count($fotos)) print "</tr>\n";
?>
      </table>

        <!-- Fi My Photos  -->



        <!-- My Videos  -->
<?php
        $videos = $_SESSION["sessio_usuari"]->obtenir_videos_usuari();
?>
      <a name="my_videos"></a>


      <table border=0 style="border-collapse:collapse;width:100%;margin-top:30px;" background="ma_bar_bg.gif">
      <tr>
        <td class="Estilo14" style="width:90%">
                <strong>&nbsp;My videos</strong><?php $total_videos = count($videos); print "&nbsp;<span class=\"Estilo13\">($total_videos videos)</span>"; ?>
        </td>
<!--        <td style="width:10%;" align="center"><a href=""><img alt="Setup" title="Setup" border=0 src="setup.gif"></a></td>-->
      </tr>
      </table>

        <div id="videoInfo" style="display:none; border-width:1px 1px 1px 1px;border-style: dotted; background-color:#FFFFCC;"></div>

      <table border=0 style="border-collapse:collapse;width:100%;margin-top:5px;font-size:9px;" class="Estilo13">
<?php
        $videos_per_fila = 7;
        $col = 0;
        if(count($videos)) print "<tr>";
        foreach($videos as $codi_contingut => $video)
        {
                if($col>=$videos_per_fila)
                {
                        print "</tr><tr>\n";
                        $col=0;
                }
                $estat = $video['estat'];
                if($estat=='APROVAT') $f=3; else $f=4;
                print "<td style=\"background-repeat:no-repeat;cursor:hand;cursor:pointer;\" valign=\"bottom\" align=\"center\" width=\"110px\" height=\"120\" background=\"http://video.mobiturmix.com/pr.php?c=$codi_contingut&f=$f\" onmouseover=\"omovr(this);\" onmouseout=\"omout(this);\" onclick=\"sv('$codi_contingut');\">\n";

                $puntuacio = $video['puntuacio'];
                for($i=1; $i<=5; $i++)
                {
                        if($i<=$puntuacio) print "\t\t\t\t\t\t\t\t<img src=\"/star.gif\">\n";
                        else print "\t\t\t\t\t\t\t\t<img src=\"/stare.gif\">\n";
                }

                print "<br/>0 comments</td>\n";
                $col++;
        }
        while($col<$videos_per_fila)
        {
                print "<td></td>\n";
                $col++;
        }
        if(count($videos)) print "</tr>\n";
?>
      </table>

        <!-- Fi My Videos  -->



        <!-- My Animations  -->
      <a name="my_animations"></a>
      <table border=0 style="border-collapse:collapse;width:100%;margin-top:30px;" background="ma_bar_bg.gif">
      <tr>
        <td class="Estilo14" style="width:90%">
                <strong>&nbsp;My animations</strong>
        </td>
<!--        <td style="width:10%;" align="center"><a href=""><img alt="Setup" title="Setup" border=0 src="setup.gif"></a></td>-->
      </tr>
      </table>

      <table border=0 style="border-collapse:collapse;width:100%;margin-top:5px;" class="Estilo13">
      <tr>
        <td valign="middle" align="center" width="200px">

        </td>
        <td>
        </td>
      </tr>
      </table>

        <!-- Fi My Animations  -->



      </td>
  </tr>
  <tr>
    <td colspan="2" align="left" bordercolor="0"><?php print FOOT_HTML_MESSAGE;?></td>
    </tr>
</table>
</body>
</html>
<?php
	}

}
