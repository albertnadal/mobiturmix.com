<?php
define('DEBUG', false);

require_once("constants_smsturmix.php");
require_once("class.phpmailer.php");

class enviador_emails
{

	function enviador_emails()
	{

	}
/*
define('WALLPAPER', 1);
define('ANIMATION', 2);
define('VIDEO', 3);
define('AUDIO', 4);
*/

        function enviar_email_a_usuari($email_to, $codi_contingut, $data_insert='', $a_amic=false, $path_preview='', $tipus=WALLPAPER)
        {
		if($data_insert=='') $data_insert = date("Y-m-d H:i:s");
		switch($tipus)
		{
			case WALLPAPER:		$item = 'fondo'; $domini = 'photo.mobiturmix.com'; break;
			case VIDEO:		$item = 'v�deo'; $domini = 'video.mobiturmix.com'; break;
			case AUDIO:		$item =	'audio'; $domini = 'audio.mobiturmix.com'; break;
			case ANIMATION:		$item = 'animado'; $domini = 'anima.mobiturmix.com'; break;
		}

                if($a_amic)
                {
                        $subjecte = "Un amigo te recomienda este $item para el m�vil";
                        $missatge = "
                                <p>Hola,</p>
                                <p>un amigo tuyo ha subido un $item a <b><tt>MobiTurmix</tt></b> y desea compartirlo contigo.</p>

                                <p>Puedes ver una previsualizaci�n online del $item a trab�s de <a href=\"http://$domini/info.php?c=$codi_contingut\">este enlace</a>, as� como los pasos que deves seguir si deseas descargarte el $item a tu m�vil.</p>

                                <p>Te agradecemos tu colaboraci�n y confianza con nuestros servicios exclusivos para dispositivos m�viles.
Estamos en continuo trabajo para mejorar y ofrecerte pr�ximas novedades.</p>

                                <p><br>Gr�cias,<br>
                                El equipo de <b><tt>MobiTurmix</tt></b>.</p>

                                <img src=\"cid:logo\" />";
                }
                else
                {
                        $dia = date( 'd', strtotime($data_insert));
                        $hora = date( 'H', strtotime($data_insert));
                        $minut = date( 'i', strtotime($data_insert));
                        $dia_mes = date( 'n', strtotime($data_insert));
                        $mesos = array( 'Enero',
                                        'Febrero',
                                        'Marzo',
                                        'Abril',
                                        'Mayo',
                                        'Junio',
                                        'Julio',
                                        'Agosto',
                                        'Setiembre',
                                        'Octubre',
                                        'Noviembre',
                                        'Diciembre');
                        $mes = $mesos[$dia_mes];
                        $subjecte = "Tu $item ya est� listo para descargar";
                        $missatge = "
                                <p>Hola,</p>

                                <p>Nos alegra comunicarte que ya tenemos preparado el $item que subiste a <b><tt>MobiTurmix</tt></b> el d�a $dia de $mes a las $hora:$minut.</p>

                                <p>Puedes ver una previsualizaci�n online de tu $item a trab�s de <a href=\"http://$domini/info.php?c=$codi_contingut\">este enlace</a>, as� como los pasos que deves seguir para poder descargarte el $item a tu m�vil.</p>

                                <p>Te agradecemos tu colaboraci�n y confianza con nuestros servicios exclusivos para dispositivos m�viles.
Estamos en continuo trabajo para mejorar y ofrecerte pr�ximas novedades.</p>

                                <p><br>Gr�cias,<br>
                                El equipo de <b><tt>MobiTurmix</tt></b>.</p>

				<img src=\"cid:logo\" />";
                }

                $mail = new PHPMailer();
                $mail->CharSet  = 'iso-8859-1'; //utf-8
                $mail->ContentType = 'text/html';
                $mail->From     = "turmix@mobiturmix.com";
                $mail->FromName = "MobiTurmix";
                $mail->Subject  = $subjecte;
//		$mail->isHTML(true);
		$mail->AddEmbeddedImage('/var/www/html/www.smsturmix.com/logo.gif', 'logo', 'logo.gif');
		if($path_preview)
			$mail->AddEmbeddedImage($path_preview, 'preview', 'preview.gif');


$body = "<html><head></head><body>
<table nowrap=\"\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"8\" width=\"100%\">
 <tbody><tr><td>
<div>
        <div style=\"padding: 5px; background-color: rgb(160, 207, 244);\">
                <h1 style=\"color: rgb(255, 255, 255);\">&nbsp;<a style=\"font-size: 24px;color: rgb(255, 255, 255);\" href=\"http://www.mobiturmix.com\" title=\"MobiTurmix\" border=\"0\">MobiTurmix</a> &nbsp;<span style=\"font-size: 14px;\">Tu contenido multimedia, en tu m�vil.</span></h1>
        </div>
        <table style=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                <tbody><tr valign=\"top\">
			<td>
";
	if($path_preview) $body .= "			<img src=\"cid:preview\" />\n";

$body .= "
			</td>
                        <td>
                                <div style=\"padding-left: 25px; padding-right: 25px;\">
                                $missatge
                                </div>
                        </td>
                        <td width=\"300\">
                                <div align=\"center\" style=\"padding: 2px; background-color: rgb(191, 191, 191);\">
                                        <h3 style=\"color: rgb(255, 255, 255);\">Usando MobiTurmix</h3>
                                </div>
                                <div style=\"padding: 10px; background-color: rgb(238, 238, 238);\">
                                        <b>photo.turmix</b><br>
                                        <a href=\"http://photo.mobiturmix.com/upload.php\">Sube tus fotos</a> y en un instante las tendremos listas para que te las puedas descargar a tu m�vil.<br><br>En nuestra <a href=\"http://photo.mobiturmix.com/ft.php\">Fototeca</a> encontrar�s tus fotos y las de otra gente como tu!
                                        <hr>
                                        <b>video.turmix</b><br>
                                        <a href=\"http://video.mobiturmix.com/upload.php\">Sube tus v�deos</a> y en un instante los tendremos listos para que te los puedas descargar a tu m�vil.<br><br>En nuestra <a href=\"http://video.mobiturmix.com/ft.php\">Videoteca</a> encontraras tus v�deos y los de otra gente como tu!
                                        <hr>
                                        <b>audio.turmix</b><br>
                                        <a href=\"http://audio.mobiturmix.com/upload.php\">Sube tu m�sica</a> y en un instante la tendremos lista para que te la puedas descargar a tu m�vil.<br><br>En nuestra <a href=\"http://audio.mobiturmix.com/ft.php\">Audioteca</a> encontrar�s tu m�sica y la de otra gente como tu!
                                        <hr>
                                        <b>anima.turmix</b><br>
                                        <a href=\"http://anima.mobiturmix.com/upload.php\">Sube tus fotogramas</a> y en un instante tendremos lista tu animaci�n para que te la puedas descargar a tu m�vil.<br><br>En nuestra <a href=\"http://anima.mobiturmix.com/ft.php\">Animateca</a> encontrar�s tus animaciones y las de otra gente como tu!
                                </div>
                        </td>
                </tr>
        </tbody></table>
        </div>
        <div style=\"padding: 10px; background-color: rgb(238, 238, 238);\">
                <p style=\"color: rgb(102, 102, 102);\">Copyright (C) 2007 MobiTurmix, todos los derechos reservados.</p>
        </div>


<font color=\"#000000\">

</font></td></tr>
</tbody></table></body></html>";

                $mail->Body    = $body;
                $mail->AddAddress($email_to);
                if(!$mail->Send()) if(DEBUG) print "There has been a mail error when sending<br>";
                if(DEBUG) print "Email enviat<br>";

                $mail->ClearAddresses();
                $mail->ClearAttachments();
        }

        function IsEMail($e)
        {
            if(eregi("^[a-zA-Z0-9]+[_a-zA-Z0-9-]*
        (\.[_a-z0-9-]+)*@[a-z......0-9]+
        (-[a-z......0-9]+)*(\.[a-z......0-9-]+)*
        (\.[a-z]{2,4})$", $e))
            {
                return TRUE;
            }
            return FALSE;
        }

        function enviar_email_a_amics($email_to, $codi_contingut, $path_preview='')
        {
                $emails = explode(",", $email_to);
                foreach($emails as $email)
                        if($this->IsEMail($email))
                                $this->enviar_email_a_usuari($email, $codi_contingut, "", true, $path_preview);
        }
}
?>
