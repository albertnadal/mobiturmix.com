<?php
include("constants_smsturmix.php");
require_once("conexio_bd.php");
//require_once("classe_sessio_usuari.php");
require_once("classe_sessio_usuari.php");
require_once("classe_alta_usuari.php");
//require_once("classe_sessio_usuari.php");

//session_start();
$ok = $_SESSION["alta_usuari"]->processar_dades_nou_usuari($_POST);
mostrar_formulari($ok);


function mostrar_formulari($mostra_form)
{
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
	<link rel="shortcut icon" href="http://www.mobiturmix.com/favicon.ico" />

  <link rel="stylesheet" type="text/css" href="/css/new_account.css">
  <style type="text/css">
  /*<![CDATA[*/
   td.photo { background: url(http://www.mobiturmix.com/accounts/u_photo.gif); cursor: hand; cursor: pointer;}
   td.video { background: url(http://video.mobiturmix.com/u_video.gif); cursor: hand; cursor: pointer;}
   td.audio { background: url(http://video.mobiturmix.com/u_audio.gif); cursor: hand; cursor: pointer;}
   td.anima { background: url(http://www.mobiturmix.com/faq/u_anima.gif); cursor: hand; cursor: pointer;}

<?php
$_SESSION["sessio_usuari"]->incrustar_codi_css_menu_superior_sessio();
?>

  /*]]>*/
  </style>
</head>
<body onunload="GUnload()" style="margin: 0px; background-color: rgb(255, 255, 255);">

<script src="http://maps.google.com/maps?file=api&v=2.x&key=<?php print GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
<script type="text/javascript" src="/js/mochikit/packed/mochikit.js"></script>
<script src="/js/girafatools/geo.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
    var map = null;
    //var geocoder = null;
    var city_x = 0;
    var city_y = 0;
    var state_x = 0;
    var state_y = 0;
    var country_x = 0;
    var country_y = 0;

    function load_map() {
      if (GBrowserIsCompatible()) {
        map = new GMap2(gebi("mtcGoogleMap"));
        //map.setCenter(new GLatLng(41.668321,1.2751514), 1);
        //geocoder = new GClientGeocoder();
      }
    }

function set_color(c,e)
{
  if(c!='w') { e.style.background = '#ffffb4'; }
  else { e.style.background = '#e4fbff'; }
}

function show_map(b)
{
  d=gebi('mtcGoogleMap');
  d.style.display=b;
}

function renderHandsetSelector(data)
{
  var acumula = DIV();
  var opcio = OPTION({'value': 0}, '');
  var aux = DIV();
  aux.appendChild(opcio);
  acumula.appendChild(aux);

  for (var i=0; i<data.id_handset.length; i++)
  {
        var opcio = OPTION({'value': data.id_handset[i]}, data.name_handset[i]);
        var aux = DIV();
        aux.appendChild(opcio);
        acumula.appendChild(aux);
  }

   var aux = DIV();
   aux.appendChild(acumula);
   e=gebi('capa_model_handset');
   e.innerHTML = "<select style='width:96px' onfocus='set_color(\"y\",this);' onBlur='set_color(\"w\",this);' id='my_handset' name='my_handset'>"+aux.innerHTML+"</select>";
}

function mostrar_indicador_loading(id)
{
  e = gebi(id);
  e.innerHTML = "<center><img src='/loading.gif'></center>";
}

function posicionar_ciutat(id_city)
{
  m=gebi('mtcGoogleMap');
  m.style.display='block';

  load_map();
//  map.setCenter(new GLatLng(state_x, state_y), 6);

  geo = new MobiturmixGeoLoader('selector_ciutats', map, 'mtcGoogleMap');
  geo.generatePositionateCity(id_city);
  city_x = geo.city_x;
  city_y = geo.city_y;
}

function mostrar_selector_ciutats(id_state)
{
  m=gebi('mtcGoogleMap');
  m.style.display='block';

  e=gebi('capa_ciutats');
  e.style.display='block';

  load_map();

  mostrar_indicador_loading('selector_ciutats');
  geo = new MobiturmixGeoLoader('selector_ciutats', map, 'mtcGoogleMap');
  geo.generateStateCitiesSelector(id_state);
  state_x = geo.state_x;
  state_y = geo.state_y;
}

function mostrar_selector_estats(id_country)
{
  m=gebi('mtcGoogleMap');
  m.style.display='block';

  c=gebi('capa_ciutats');
  c.style.display='none';

  e=gebi('capa_estats');
  e.style.display='block';

  load_map();

  mostrar_indicador_loading('selector_estats');
  geo = new MobiturmixGeoLoader('selector_estats', map, 'mtcGoogleMap');
  geo.generateCountryStatesSelector(id_country);
  country_x = geo.country_x;
  country_y = geo.country_y;
}

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

<table width="903" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td width="106" align="center" valign="middle"><img src="http://www.mobiturmix.com/arrows.gif" id="arr" style="display:none;" /></td>
    <td width="427" height="60"></td>
    <td width="358" align="right"><a href="http://www.mobiturmix.com"><img src="http://www.mobiturmix.com/logo.gif" border="0"/></a></td>
  </tr>
</table>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr background="../gratis/u_audio.gif">
    <td colspan="6" align="left"><img src="http://video.mobiturmix.com/u_top.gif" width="905" height="11" /></td>
  </tr>
  <tr valign="top">
    <td><img src="http://www.mobiturmix.com/d0.gif" width="105" height="34" /></td>
    <td class="photo" width="150" height="34" onMouseOver="show('pho','block');" onMouseOut="show('pho','none');" onclick="document.location.href='http://photo.mobiturmix.com';"><img style="display:none" id="pho" src="http://www.mobiturmix.com/accounts/r_photo.gif"/></td>
    <td class="video" width="117" height="34" onMouseOver="show('vid','block');" onMouseOut="show('vid','none');" onclick="document.location.href='http://video.mobiturmix.com';"><img style="display:none" id="vid" src="http://video.mobiturmix.com/r_video.gif"/></td>
    <td class="audio" width="118" height="34" onMouseOver="show('aud','block');" onMouseOut="show('aud','none');" onclick="document.location.href='http://audio.mobiturmix.com';"><img style="display:none" id="aud" src="http://video.mobiturmix.com/r_audio.gif"/></td>
    <td class="anima" width="153" height="34" onMouseOver="show('ani','block');" onMouseOut="show('ani','none');" onclick="document.location.href='http://anima.mobiturmix.com';"><img style="display:none" id="ani" src="http://www.mobiturmix.com/faq/r_anima.gif"/></td>
    <td width="262">&nbsp;</td>
  </tr>
</table>
  
  <table width="905" border="0" align="center" cellpadding="0" cellspacing="0" background="nab.gif" style="background-repeat:no-repeat">
  <tr>
    <td height="540" align="left" valign="top"><div id="mtcHomeContainer">
			<div id="mtcHomeContent">
<!--				<div class="mtcNbrConnect"><strong>47465</strong> conectados en este momento</div>-->

<div id="mtcGoogleMap" style="width: 300px; height: 300px; display:none;"></div>

<div id="mtcInscription">
			
			<div class="mtcInscription1 mtcInscription1signup">

<?php
if($mostra_form)
{
?>
				<form method="post" action="#" id="form_signup" name="form_signup">
				<input type="hidden" name="post" value="post">
				<h2>Inscríbete, es inmediato! </h2>
				
				<label class="mtcArrowOver" id="my_pseudo_label" for="my_pseudo">Mi apodo</label>
				
				<span>
					
					<input type="text" onfocus="set_color('y',this); show_map('none');" onBlur="set_color('w',this);" maxlength="20" style="width: 184px;" class="mtcInputText" tabindex="10" id="my_pseudo" name="my_pseudo" value="<?php print $_SESSION["alta_usuari"]->informacio['my_pseudo']; ?>" />
				</span>

<?php
				$error_msg = $_SESSION["alta_usuari"]->message_errors['my_pseudo'];
				if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>

				<div class="mtcClearer"><!-- --></div>
				
				
				
				<label class="mtcArrowOver" id="my_name_label" for="my_name">Mi nombre</label>
				
				<span>
					
					<input type="text" onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  maxlength="20" style="width: 184px;" class="mtcInputText" tabindex="10" id="my_name" name="my_name" value="<?php print $_SESSION["alta_usuari"]->informacio['my_name']; ?>"/>
				</span>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_name'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>

				<div class="mtcClearer"><!-- --></div>
				
				
				
				
				
				
				
				
				
				
				
				<label id="my_pwd_label" for="my_pwd">Mi contraseña</label>
				
				<span>
					
					<input type="password" onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" tabindex="20" style="width: 184px;" class="mtcInputText" id="my_pwd" name="my_pwd" value="<?php print $_SESSION["alta_usuari"]->informacio['my_pwd']; ?>"/>
				</span>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_pwd'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>

				<div class="mtcClearer"><!-- --></div>
				
				
				
				
				
				
				
				
				
				<label id="my_email_label" for="my_email">Mi dirección de email</label>
				
				<span>
					
					<input type="text" onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" tabindex="30" style="width: 184px;" class="mtcInputText" id="my_email" name="my_email" value="<?php print $_SESSION["alta_usuari"]->informacio['my_email']; ?>"/>
				</span>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_email'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>

				<div class="mtcClearer"><!-- --></div>
				
				
<!--				<label class="mtcArrowOver" id="my_web_label" for="my_web">Mi dirección web</label>
				
				<span>
					
					<input type="text" onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" maxlength="20" style="width: 184px;" class="mtcInputText" tabindex="10" id="my_web" name="my_web"/>
				</span>
				
				<div class="mtcClearer"></div>-->
				



				<label id="my_search_age_mini_label" for="my_search_age_mini">Mi modelo de móvil</label>

				
				<span>
					<table border=0 style="border-collapse:collapse">
					<tr>
					<td>
					<select  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  tabindex="60" style="width: 96px;" id="my_brand_band" name="my_brand_band" onchange="mostrar_indicador_loading('capa_model_handset'); var d = loadJSONDoc('/handsets.php?op=get_handsets&imh='+this.options[this.selectedIndex].value); d.addCallback(renderHandsetSelector);">

					<option value="0"></option>
<?php

	$imh = $_SESSION["alta_usuari"]->informacio['my_brand_band'];

        $con_bd = new conexio_bd();
        $res = $con_bd->sql_query("select id_marca_handset, marca from marca_handset order by marca asc");
        while($row = $res->fetchRow())
	{
		if($row[0]==$imh) $selected = ' selected=selected'; else $selected='';
                print "\t<option value=\"".($row[0])."\"$selected>".utf8_encode($row[1])."</option>\n";
	}
?>
        </select></td><td align="center"><div id='capa_model_handset'><?php

	if($imh!='')
	{
                                        ?><select  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  tabindex="60" style="width: 96px;" id="my_handset" name="my_handset" ><?php
		$ih = $_SESSION["alta_usuari"]->informacio['my_handset'];

	        $con_bd = new conexio_bd();
        	$res = $con_bd->sql_query("select id_handset, model from handset where id_marca_handset=$imh order by model asc");
	        while($row = $res->fetchRow())
        	{
                	if($row[0]==$ih) $selected = ' selected=selected'; else $selected='';
	                print "\t<option value=\"".($row[0])."\"$selected>".utf8_encode($row[1])."</option>\n";
        	}

		print "</select>";
	}

?></div></td></tr></table>
	</span>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_handset'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>

				<div class="mtcClearer"><!-- --></div>




				
				
				
				<label id="my_gender_label" for="my_gender">Soy</label>	
				
				<span>
					<select  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" tabindex="40" style="width: 194px;" id="my_gender" name="my_gender">
<?php

	$gender = $_SESSION["alta_usuari"]->informacio['my_gender'];

	$id_generes = array('-1'=>'', '2'=>'una mujer', '1'=>'un hombre');
	foreach($id_generes as $id_genere => $text_genere)
	{
		if($id_genere == $gender) $selected = ' selected=selected'; else $selected = '';
		print "<option value=\"$id_genere\"$selected>".($text_genere)."</option>\n";
	}
?>
					</select>
				</span>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_gender'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>
				
				<div class="mtcClearer"><!-- --></div>
				
				
				
				
						
				
				
				
				<label id="my_birth_day_label" for="my_birth_day">Mi fecha de nacimiento</label>
				
				<span>
					
					<input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  tabindex="110" maxlength="2" style="width: 18px;" class="mtcInputText" id="my_births_err_my_birthday_null_day" name="my_birth_day" value="<?php print $_SESSION["alta_usuari"]->informacio['my_birth_day']; ?>"/> DD   <input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  maxlength="2" tabindex="120" style="width: 18px;" class="mtcInputText" id="my_birth_month" name="my_birth_month" value="<?php print $_SESSION["alta_usuari"]->informacio['my_birth_month']; ?>"/> MM   <input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  maxlength="4" tabindex="130" style="width: 30px;" class="mtcInputText" id="my_birth_year" name="my_birth_year" value="<?php print $_SESSION["alta_usuari"]->informacio['my_birth_year']; ?>"/> AAAA				</span>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_birth_day'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");

                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_birth_month'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");

                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_birth_year'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>

				<div class="mtcClearer"><!-- --></div>
				
				
				
				
				
				
				
				<!-- DEBUT ajax_geosignup -->
				<div id="geo_signup">
					
					

<!-- PAISOS -->

<label id="my_country_label" for="my_country">Mi país de residencia</label>
<span>

	<select  onfocus="set_color('y',this);" onBlur="set_color('w',this);"  onchange="mostrar_selector_estats(this.options[this.selectedIndex].value)" tabindex="200" style="width: 194px;" id="my_country" name="my_country">
	<option value="0"></option>
<?php

	$id_country = $_SESSION["alta_usuari"]->informacio['my_country'];

	$con_bd = new conexio_bd();
	$res = $con_bd->sql_query("select id_country, nom from country order by nom asc");
	while($row = $res->fetchRow())
	{
		if($id_country == $row[0]) $selected = ' selected=selected'; else $selected = '';
		print "\t<option value=\"".($row[0])."\"$selected>".utf8_encode($row[1])."</option>\n";
	}
?>	
	</select>
</span>
<div class="mtcClearer"><!-- --></div>



<!-- ESTATS -->

<div id="capa_estats" style="display:none;">
<label id="my_state_label" for="my_state">Mi estado o comunidad</label>
<span>
<div id="selector_estats"><center><img src="/loading.gif"></center></div>
</span>
<div class="mtcClearer"><!-- --></div>
</div>


<!-- CIUTATS -->

<div id="capa_ciutats" style="display:none;">
<label id="my_city_label" for="my_city">Mi ciudad o pueblo</label>
<span>
<div id="selector_ciutats"><center><img src="/loading.gif"></center></div>
</span>
<div class="mtcClearer"><!-- --></div>
</div>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_state'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");

                                $error_msg = $_SESSION["alta_usuari"]->message_errors['my_city'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>


<!-- OTHER ACCOUNTS  -->
<!--				<p>Indica los apodos de tus otras cuentas, <b>te las juntamos en un único portal web!</b></p>				

			<p>
			<table border=0 width="100%" style="border-collapse:collapse">
			<tr>
				<td>
					<label id="youtube_label" for="youtube_username">
						<input type="checkbox" name="check_youtube" id="check_youtube"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" /><img src="youtube_logo.png" border=1>
					</label>
					<span>
						<input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  class="mtcInputText" id="youtube_username" name="my_youtube" style="width: 184px;" value="- Pon aquí tu username de YouTube -"/>
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<label id="flickr_label" for="flickr_username">
						<input type="checkbox" name="check_flickr" id="check_flickr"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" /><img src="flickr_logo.png" border=1>
					</label>
					<span>
						<input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  class="mtcInputText" id="flickr_username" name="my_flickr" style="width: 184px;" value="- Pon aquí tu username de Flickr -"/>
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<label id="fotolog_label" for="fotolog_username">
						<input type="checkbox" name="check_fotolog" id="check_fotolog"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" /><img src="fotolog_logo.png" border=1>
					</label>
					<span>
						<input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  class="mtcInputText" id="fotolog_username" name="my_fotolog" style="width: 184px;" value="- Pon aquí tu username de Fotolog -"/>
					</span>
				</td>
			</tr>
			<tr>
				<td>
					<label id="myspace_label" for="myspace_username">
						<input type="checkbox" name="check_myspace" id="check_myspace"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);" /><img src="myspace_logo.png" border=1>
					</label>
					<span>
						<input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  class="mtcInputText" id="myspace_username" name="my_myspace" style="width: 184px;" value="- Pon aquí tu username de MySpace -"/>
					</span>
				</td>
			</tr>
			</table>
			</p>
-->

<!--				<p>Acepto recibir por email avisos y notícias de los socios de <b>mobiturmix</b>.</p>
				<p style="margin: 5px 0pt 8px; text-align: center;"><input type="radio" tabindex="320" value="0" id="my_optin0" name="my_optin" checked="true"/>sí<input type="radio" style="margin-left: 60px;" tabindex="330" value="1" id="my_optin1" name="my_optin"/>no</p>-->


				<br/>
				<label class="mtcArrowOver" id="my_verification_label" for="my_verification">Introduce el código de la imagen de abajo</label>

				<span>
					
					<input type="text"  onfocus="set_color('y',this);  show_map('none');" onBlur="set_color('w',this);"  maxlength="20" style="width: 184px;" class="mtcInputText" tabindex="10" id="my_verification" name="verification" value="<?php print $_SESSION["alta_usuari"]->informacio['verification']; ?>"/>
				
</span>

<?php
                                $error_msg = $_SESSION["alta_usuari"]->message_errors['verification'];
                                if($error_msg!='') print utf8_encode("<br/><span style=\"color:red;width:100%\">".$error_msg."</span>");
?>

				<div class="mtcClearer"><!-- --></div>




				
				<input type="submit" class="mtcHidden"/>
				</form><!--form_signup_index-->

<?php
}
else
{
$_SESSION["alta_usuari"]->guardar_usuari_a_bd();
$nom_usuari = $_SESSION["alta_usuari"]->informacio['my_name'];
print "                                <h2>$nom_usuari, bienvenido a la comunidad <b>Turmix</b>! </h2>\n";

$_SESSION["alta_usuari"]->finalitzar_sessio(); //Cal finalitzar la sessi�


}

?>

			</div><!--mtcInscription1-->

			<div class="mtcInscription2" align="right">

<?php
if($mostra_form)
{
?>
				<table border=0 width="100%">
				<tr>
					<td align="left">
						<img src="/accounts/validation_code.php" />
					</td>
					<td align="right">
						<a class="mtcBtInscription" tabindex="340" href="javascript:document.form_signup.submit();"><!-- --></a>			</div><!--mtcInscription2-->
					</td>
				</tr>
				</table>
<?php
}
?>
		</div>
			</div>
	</div></td>
    </tr>
  <tr>
    <td colspan="3"><?php print FOOT_HTML_MESSAGE;?></td>
  </tr>
</table>
</body>
</html>

<?php
}
?>
