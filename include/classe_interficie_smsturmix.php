<?
define("PHOTOTURMIX",		0);
define("VIDEOTURMIX",		1);
define("AUDIOTURMIX",		2);
define("ANIMATURMIX",		3);

class interficie_smsturmix
{
        function interficie_smsturmix ()
        {
        }

        function footer()
        {
          print "<div id=\"foot\"><br>";
          print "<small>";
          print "(c) 2006 <a href=\"http://".SERVER_NAME."/about\" title=\"".htmlentities("Más información sobre nosotros la encontrarás aquí!")."\">SmsTurmix</a>,\n";
          print htmlentities("todos los derechos reservados. Ninguna parte del site puede ser reproducida sin el permiso del propietario de los derechos de autor.")."<br>\n";
          print htmlentities("Todos los servicios de SmsTurmix muestran las compatibilidades del contenido multimedia con tu teléfono móvil o el que tu quieras.")."<br>\n";
          print htmlentities("El coste por descarga es de 1 solo sms (1.2e+iva), tráfico gprs no incluido. Site desarrollado y mantenido por ")." <a href=\"http://www.girafatools.com\" title=\"www.girafatools.com\" alt=\"www.girafatools.com\">GirafaTools</a>.\n";
          print "</small>";
          print "</div>";
        }

        function header_menu($selected=PHOTOTURMIX)
        {
          print "\t<link href=\"http://".SERVER_NAME."/css/smsturmix.css\" media=\"screen\" rel=\"Stylesheet\" type=\"text/css\">\n";
          print "\t<table border=1 align=\"center\" style=\"border-collapse:collapse; margin-top:5px;\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\"><tr><td>\n";
          print "\t<table style=\"border-collapse:collapse;\" width=\"974\" height=\"70\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" background=\"http://".SERVER_NAME."/images/hbg.png\">\n";
          print "\t  <tr>\n";
          print "\t    <td width=\"5%\"></td>\n";
          print "\t    <td width=\"28%\"></td>\n";
          print "\t    <td width=\"52%\"></td>\n";
          print "\t    <td width=\"15%\"></td>\n";
          print "\t  </tr>\n";
          print "\t  <tr align=\"left\" >\n";
          print "\t    <td height=\"50\" align=\"right\"></td>\n";
          print "\t    <td colspan=\"2\"><img src=\"http://".SERVER_NAME."/images/ptlogo.png\"/></td>\n";
          print "\t    <td>&nbsp;</td>\n";
          print "\t  </tr>\n";
          print "\t  <tr>\n";
          print "\t    <td colspan=\"4\">\n";
          print "\t		<table width=\"64%\" border=0 align=\"left\" cellpadding=\"0\" cellspacing=\"0\">\n";
          print "\t			<tr align=\"center\" valign=\"middle\">\n";
          $tab_photo = $tab_video = $tab_audio = $tab_anima = $tab_java = 'tab-off.png';
          $tab_photo_color = $tab_video_color = $tab_audio_color = $tab_anima_color = $tab_java_color = '';
          switch($selected)
          {
            case PHOTOTURMIX: $tab_photo = 'tab-on.png'; $tab_photo_color = 'style="color:#FFFFFF;"'; break;
          }
          print "\t				<td width=\"120\" height=\"40\" nowrap=\"nowrap\" background=\"http://".SERVER_NAME."/images/$tab_photo\"><a href=\"http://www.google.es\" $tab_photo_color>photo.turmix</a></td>\n";
          print "\t				<td width=\"119\" height=\"40\" background=\"http://".SERVER_NAME."/images/$tab_video\"><a href=\"http://www.google.es\" $tab_video_color>video.turmix</a></td>\n";
          print "\t				<td width=\"119\" height=\"40\" background=\"http://".SERVER_NAME."/images/$tab_audio\"><a href=\"http://www.google.es\" $tab_audio_color>audio.turmix</a></td>\n";
          print "\t				<td width=\"119\" height=\"40\" background=\"http://".SERVER_NAME."/images/$tab_anima\"><a href=\"http://www.google.es\" $tab_anima_color>anima.turmix</a></td>\n";
          print "\t				<td width=\"120\" height=\"40\" background=\"http://".SERVER_NAME."/images/$tab_java\"><a href=\"http://www.google.es\" $tab_java_color>java.turmix</a></td>\n";
          print "\t		  </tr>\n";
          print "\t	  </table>	</td>\n";
          print "\t  </tr>\n";
          print "\t  <tr bgcolor=\"#000000\">\n";
          print "\t    <td height=\"5px\" colspan=\"4\"></td>\n";
          print "\t  </tr>\n";
          print "\t  <tr valign=\"baseline\" bgcolor=\"#D4D0C8\">\n";
          print "\t    <td>&nbsp;</td>\n";
          print "\t    <td colspan=\"2\" align=\"center\"><span class=\"mtx_srch2_form Estilo1\">Buscar:\n";
          print "\t        <input name=\"qt\" size=\"50\" maxlength=\"255\" class=\"mtx_srch2_box\" type=\"text\" />\n";
          print "\t        <select class=\"mtx_srch2_select\" name=\"tg\">\n";
          print "\t          <option value=\"dl-2160\">-- FTP</option>\n";
          print "\t          <option value=\"dl-2018\">- Utilities &amp; Drivers</option>\n";
          print "\t          <option value=\"dl-2001\" selected=\"selected\">Windows</option>\n";
          print "\t          <option value=\"dl-20\">Software</option>\n";
          print "\t          <option value=\"dl-2012\">Games</option>\n";
          print "\t          <option value=\"mdl\">Music</option>\n";
          print "\t          <option value=\"nw\">All CNET</option>\n";
          print "\t          <option value=\"wb\">The Web</option>\n";
          print "\t        </select>\n";
          print "\t        <input name=\"search\" id=\"searchGo\" alt=\"Go\" value=\"Go!\" src=\"http://".SERVER_NAME."/images/srch_go2.gif\" class=\"mtx_srch2_go\" type=\"submit\" />\n";
          print "\t    </span></td>\n";
          print "\t    <td>&nbsp;</td>\n";
          print "\t  </tr>\n";
          print "\t</table>\n";
          print "\t</td></tr></table>\n";
        }

        function header_menu_old($selected=PHOTOTURMIX)
        {
          print "\t<link href=\"../css/smsturmix.css\" media=\"screen\" rel=\"Stylesheet\" type=\"text/css\">\n";

          print "\t<div id=\"bodywrapper\">\n";
          print "\t\t<a name=\"top\"></a>\n";
          print "\t\t<div id=\"hd_shell\">\n";

          switch($selected)
          {
            case PHOTOTURMIX: $logo = "/smsturmix/smsturmix.com/images/phototurmix_logo.png"; break;
            case VIDEOTURMIX: $logo = "/smsturmix/smsturmix.com/images/videoturmix_logo.png"; break;
            case AUDIOTURMIX: $logo = "/smsturmix/smsturmix.com/images/audioturmix_logo.png"; break;
            case ANIMATURMIX: $logo = "/smsturmix/smsturmix.com/images/animaturmix_logo.png"; break;
            default :         $logo = "/smsturmix/smsturmix.com/images/phototurmix_logo.png"; break;
          }
          print "\t\t\t<div class=\"hd_tag\" style=\"background:url('$logo') no-repeat 118px 9px transparent;\">\n";

/*          print "\t\t\t\t<div class=\"hd_unilogin\">\n";
          print "\t\t\t\t\t<div id=\"uloginOut\" style=\"display:block\" class=\"mtx_uni_out\">\n";
          print "\t\t\t\t\t\t<div class=\"mtx_uni_bg\">\n";
          print "\t\t\t\t\t\t\t<a href=\"\" onClick=\"\"><b class=\"v1\">Log in</b></a> | \n";
          print "\t\t\t\t\t\t\t<a href=\"\"><b class=\"v1\">Sign up</b></a></div>\n";
          print "\t\t\t\t\t\t\t<a href=\"\" class=\"v1\">Why join?</a>\n";
          print "\t\t\t\t\t\t</div>\n";*/
/*          print "\t\t\t\t\t\t<div id=\"uloginForm\" style=\"display:none\" class=\"mtx_uni_login\">\n";
          print "\t\t\t\t\t\t\t<form name=\"uloginform\" action=\"\" method=\"post\" class=\"unilogin_form\">\n";
          print "\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"path\" value=\"\" />\n";
          print "\t\t\t\t\t\t\t\t<div class=\"mtx_uni_bg\">\n";
          print "\t\t\t\t\t\t\t\t\t<div class=\"unilog_txt_em\">E-mail:</div>\n";
          print "\t\t\t\t\t\t\t\t\t\t<input type=\"text\" name=\"EMAILADDR\" value=\"\" class=\"mtx_unilog_txtbox_em\" onclick=\"\" />\n";
          print "\t\t\t\t\t\t\t\t\t\t\t<div class=\"unilog_txt_pw\">Password:</div>\n";
          print "\t\t\t\t\t\t\t\t\t\t<input type=\"password\" name=\"PASSWORD\" value=\"\" class=\"mtx_unilog_txtbox_pw\" size=\"5\" maxlength=\"20\" onclick=\"\" />\n";
          print "\t\t\t\t\t\t\t\t\t\t<input value=\"button\" type=\"image\" src=\"\" alt=\"Submit\" width=\"53\" height=\"16\" border=\"0\" class=\"unilog_btn\" onClick=\"\" />\n";
          print "\t\t\t\t\t\t\t\t\t</div>\n";
          print "\t\t\t\t\t\t\t\t<div class=\"unilog_save\">\n";
          print "\t\t\t\t\t\t\t\t\t<a href=\"\" class=\"unilog_pword\">Forgot password?</a>\n";
          print "\t\t\t\t\t\t\t\t\t<a href=\"\">Cancel</a>\n";
          print "\t\t\t\t\t\t\t\t</div>\n";
          print "\t\t\t\t\t\t\t</form>\n";
          print "\t\t\t\t\t\t</div>\n";
          print "\t\t\t\t\t</div>\n";*/
          print "\t\t\t\t\t<br/>\n";
          print "\t\t\t\t\t<div class=\"hd_tabs\">\n";
          print "\t\t\t\t\t\t<ul id=\"t\">\n";
          print "\t\t\t\t\t\t\t<li class=\"tab on\" id=\"t1\"><a href=\"\">photo.turmix</a></li>\n";
          print "\t\t\t\t\t\t\t<li class=\"tab\" id=\"t2\"><a href=\"\">video.turmix</a></li>\n";
          print "\t\t\t\t\t\t\t<li class=\"tab\" id=\"t3\"><a href=\"\">audio.turmix</a></li>\n";
          print "\t\t\t\t\t\t\t<li class=\"tab\" id=\"t4\"><a href=\"\">anima.turmix</a></li>\n";
          print "\t\t\t\t\t\t\t<li class=\"tab\" id=\"t5\"><a href=\"\">java.turmix</a></li>\n";
          print "\t\t\t\t\t\t</ul>\n";
          print "\t\t\t\t\t</div>\n";
          print "\t\t\t\t\t<div class=\"mtx_hbar\"></div>\n";
          print "\t\t\t\t\t\t<div class=\"mtx_srch2\">\n";
          print "\t\t\t\t\t\t\t<div class=\"mtx_srch2_pad\">\n";
          print "\t\t\t\t\t\t\t\t<form method=\"get\" action=\"\" id=\"searchForm\" name=\"searchForm\" class=\"mtx_srch2_form\">\n";
          print "\t\t\t\t\t\t\t\t\tSearch:\n";
          print "\t\t\t\t\t\t\t\t\t<input type=\"text\" name=\"qt\" size=\"50\" maxlength=\"255\" class=\"mtx_srch2_box\" />\n";
          print "\t\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"tag\" value=\"srch\" />\n";
          print "\t\t\t\t\t\t\t\t\t<select class=\"mtx_srch2_select\" name=\"tg\">\n";
          print "\t\t\t\t\t\t\t\t\t\t<option value=\"dl-2001\" selected=\"selected\">Todo multimedia</option>\n";
          print "\t\t\t\t\t\t\t\t\t\t<option value=\"dl-20\">Fondos</option>\n";
          print "\t\t\t\t\t\t\t\t\t\t<option value=\"dl-20\">Videos</option>\n";
          print "\t\t\t\t\t\t\t\t\t\t<option value=\"dl-2012\">Sonidos reales</option>\n";
          print "\t\t\t\t\t\t\t\t\t\t<option value=\"mdl\">Animaciones</option>\n";
          print "\t\t\t\t\t\t\t\t\t</select>\n";
          print "\t\t\t\t\t\t\t\t\t<input type=\"submit\" name=\"search\" id=\"searchGo\" alt=\"Go\" value=\"Go!\" src=\"\" class=\"mtx_srch2_go\" />\n";
          print "\t\t\t\t\t\t\t\t\t\t<a href=\"\" class=\"opt\">Advanced search</a>\n";
          print "\t\t\t\t\t\t\t\t</form>\n";
          print "\t\t\t\t\t\t\t</div>\n";
          print "\t\t\t\t\t\t</div>\n";		
          print "\t\t\t\t\t</div>\n";
          print "\t\t\t\t</div>\n";
        }

}

?>
