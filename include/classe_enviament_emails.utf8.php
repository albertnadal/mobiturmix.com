<?php
define('DEBUG', false);

//require_once("constants_smsturmix.php");
require_once("class.phpmailer.php");

class enviador_emails
{

	function enviador_emails()
	{

	}

        function enviar_email_a_usuari($email_to, $codi_contingut, $data_insert='', $a_amic=false)
        {
		if($data_insert=='') $data_insert = date("Y-m-d H:i:s");

                if($a_amic)
                {
                        $subjecte = "Un amigo te recomienda este vídeo para el móvil";
                        $missatge = "
                                <p>Hola,</p>
                                <p>un amigo tuyo ha subido un vídeo a <b><tt>MobiTurmix</tt></b> y desea compartirlo contigo.</p>

                                <p>Puedes ver una previsualización online del vídeo a trabés de <a href=\"http://video.mobiturmix.com/info.php?c=$codi_contingut\">este enlace</a>, así como los pasos que deves seguir si deseas descargarte el vídeo a tu móvil.</p>

                                <p>Te agradecemos tu colaboración y confianza con nuestros servicios exclusivos para dispositivos móviles.
Estamos en continuo trabajo para mejorar y ofrecerte próximas novedades.</p>

                                <p><br>Grácias,<br>
                                El equipo de <b><tt>MobiTurmix</tt></b>.</p>";
                }
                else
                {
			print "preparant enviament\n";
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
                        $subjecte = "Tu vídeo ya está listo para descargar";
                        $missatge = "
                                <p>Hola,</p>

                                <p>Nos alegra comunicarte que ya tenemos preparado el vídeo que subiste a <b><tt>MobiTurmix</tt></b> el día $dia de $mes a las $hora:$minut.</p>

                                <p>Puedes ver una previsualización online de tu vídeo a trabés de <a href=\"http://video.mobiturmix.com/info.php?c=$codi_contingut\">este enlace</a>, así como los pasos que deves seguir para poder descargarte el vídeo a tu móvil.</p>

                                <p>Te agradecemos tu colaboración y confianza con nuestros servicios exclusivos para dispositivos móviles.
Estamos en continuo trabajo para mejorar y ofrecerte próximas novedades.</p>

                                <p><br>Grácias,<br>
                                El equipo de <b><tt>MobiTurmix</tt></b>.</p>";
                }

                $mail = new PHPMailer();
                $mail->CharSet  = 'utf-8'; //'iso-8859-1'; //utf-8
                $mail->ContentType = 'text/html';
                $mail->From     = "turmix@mobiturmix.com";
                $mail->FromName = "MobiTurmix";
                $mail->Subject  = utf8_decode($subjecte);


$body = "<html><head></head><body>
<table nowrap=\"\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"8\" width=\"100%\">
 <tbody><tr><td>
<div>
        <div style=\"padding: 5px; background-color: rgb(160, 207, 244);\">
                <h1 style=\"color: rgb(255, 255, 255);\">&nbsp;<a style=\"font-size: 24px;color: rgb(255, 255, 255);\" href=\"http://www.mobiturmix.com\">MobiTurmix</a> &nbsp;<span style=\"font-size: 14px;\">Tu contenido multimedia, en tu móvil.</span></h1>
        </div>
        <table style=\"\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
                <tbody><tr valign=\"top\">
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
                                        <a href=\"http://photo.mobiturmix.com/upload.php\">Sube tus fotos</a> y en un instante las tendremos listas para que te las puedas descargar a tu móvil.<br><br>En nuestra <a href=\"http://photo.mobiturmix.com/ft.php\">Fototeca</a> encontrarás tus fotos y las de otra gente como tu!
                                        <hr>
                                        <b>video.turmix</b><br>
                                        <a href=\"http://video.mobiturmix.com/upload.php\">Sube tus vídeos</a> y en un instante los tendremos listos para que te los puedas descargar a tu móvil.<br><br>En nuestra <a href=\"http://video.mobiturmix.com/ft.php\">Videoteca</a> encontraras tus vídeos y los de otra gente como tu!
                                        <hr>
                                        <b>audio.turmix</b><br>
                                        <a href=\"http://audio.mobiturmix.com/upload.php\">Sube tu música</a> y en un instante la tendremos lista para que te la puedas descargar a tu móvil.<br><br>En nuestra <a href=\"http://audio.mobiturmix.com/ft.php\">Audioteca</a> encontrarás tu música y la de otra gente como tu!
                                        <hr>
                                        <b>anima.turmix</b><br>
                                        <a href=\"http://anima.mobiturmix.com/upload.php\">Sube tus fotogramas</a> y en un instante tendremos lista tu animación para que te la puedas descargar a tu móvil.<br><br>En nuestra <a href=\"http://anima.mobiturmix.com/ft.php\">Animateca</a> encontrarás tus animaciones y las de otra gente como tu!
                                </div>
                        </td>
                </tr>
        </tbody></table>
        </div>
        <div style=\"padding: 10px; background-color: rgb(238, 238, 238);\">
                <p style=\"color: rgb(102, 102, 102);\">Copyright © 2007 MobiTurmix, todos los derechos reservados.</p>
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

        function enviar_email_a_amics($email_to, $codi_contingut)
        {
                $emails = explode(",", $email_to);
                foreach($emails as $email)
                        if($this->IsEMail($email))
                                $this->enviar_email_a_usuari($email, $codi_contingut, "", true);
        }
}
?>
