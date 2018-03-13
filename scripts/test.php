#!/usr/bin/php -q
<?php

require("class.phpmailer.php");
require("constants_smsturmix.php");

$mail = new PHPMailer();

$mail->From     = "video@smsturmix.com";
$mail->FromName = "SMSturmix";
$mail->Subject	= "Tu vídeo ya esta listo para descargar";


//$mail->AddEmbeddedImage("","my-attach","logo.gif");



 // HTML body

$body = '
	
<table nowrap="" align="center" border="0" cellpadding="0" cellspacing="8" width="100%"> 
 <tbody><tr><td>
<div>
	<div style="padding: 5px; background-color: rgb(160, 207, 244);">
		<h1 style="color: rgb(255, 255, 255);">&nbsp;<a style="font-size: 24px;color: rgb(255, 255, 255);" href="http://www.smsturmix.com">SMSturmix</a> &nbsp;<span style="font-size: 14px;">Tu contenido multimedia, en tu móvil.</span></h1>
	</div>
	<table style="" border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody><tr valign="top">
			<td>
				<div style="padding-left: 25px; padding-right: 25px;">
<p>Hola,</p>

<p>Nos alegra comunicarte que ya tenemos listo el vídeo que subiste a SMSturmix el dia 1 de Enero.</p>

<p>Puedes ver una previsualización online de tu vídeo a trabés de este enlace, así como los pasos que deves seguir para
poder descargarte el vídeo a tu móvil.</p>

<p>Te agradecemos tu colaboración y confianza con nuestros servicios exclusivos para dispositivos móviles.
Estamos en continuo trabajo para mejorar y ofrecerle próximas novedades.</p>

<p><br>Grácias,<br>
El equipo de SMSturmix.</p>
				</div>
			</td>
			<td width="300">
				<div align="center" style="padding: 2px; background-color: rgb(191, 191, 191);">
					<h3 style="color: rgb(255, 255, 255);">Usando SMSturmix</h3>
				</div>
				<div style="padding: 10px; background-color: rgb(238, 238, 238);">
					<b>photo.turmix</b><br>
					<a href="http://photo.smsturmix.com/upload.php">Sube tus fotos</a> y en un instante las tendremos listas para que te las puedas descargar a tu móvil.<br><br>En nuestra <a href="http://photo.smsturmix.com/ft.php">Fototeca</a> encontraras tus fotos y las de otra gente como tu!
					<hr>
					<b>video.turmix</b><br>
					<a href="http://video.smsturmix.com/upload.php">Sube tus videos</a> y en un instante los tendremos listos para que te los puedas descargar a tu móvil.<br><br>En nuestra <a href="http://video.smsturmix.com/ft.php">Videoteca</a> encontraras tus videos y los de otra gente como tu!
					<hr>
					<b>audio.turmix</b><br>
					<a href="http://audio.smsturmix.com/upload.php">Sube tu musica</a> y en un instante la tendremos lista para que te la puedas descargar a tu móvil.<br><br>En nuestra <a href="http://audio.smsturmix.com/ft.php">Audioteca</a> encontraras tu musica y la de otra gente como tu!
					<hr>
                                        <b>anima.turmix</b><br>
					<a href="http://anima.smsturmix.com/upload.php">Sube tus fotogramas</a> y en un instante tendremos lista tu animacion para que te la puedas descargar a tu móvil.<br><br>En nuestra <a href="http://anima.smsturmix.com/ft.php">Animateca</a> encontraras tus animaciones y las de otra gente como tu!
				</div>
			</td>
		</tr>
	</tbody></table>
	</div>
	<div style="padding: 10px; background-color: rgb(238, 238, 238);">
		<p style="color: rgb(102, 102, 102);">Copyright © 2007 SMSturmix, todos los derechos reservados.</p>
	</div>


<font color="#000000">

</font></td></tr>
</tbody></table>
';





    // Plain text body (for mail clients that cannot read HTML)
    $text_body  = "Hello prova, \n\n";
    $text_body .= "Your personal photograph to this message.\n\n";
    $text_body .= "Sincerely, \n";
    $text_body .= "PHPMailer List manager";

    $mail->Body    = $body;
    $mail->AltBody = $text_body;
    $mail->AddAddress("coderboy360@hotmail.com");
//    $mail->AddStringAttachment($row["photo"], "YourPhoto.jpg");

    if(!$mail->Send()) echo "There has been a mail error when sending<br>";

    // Clear all addresses and attachments for next loop
    $mail->ClearAddresses();
    $mail->ClearAttachments();

?>
