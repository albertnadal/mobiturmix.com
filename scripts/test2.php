#!/usr/bin/php -q
<?php
//require("classe_video_convert.php");
require("classe_enviament_emails.php");

print "Enviant";
//$vc = new video_convert();
//$vc->enviar_email_a_usuari('llardelpernil@gmail.com', 'cacadevaca', '');

$enviador = new enviador_emails();
$enviador->enviar_email_a_usuari('turmix@tinet.org', 'tinet');
$enviador->enviar_email_a_usuari('coderboy360@hotmail.com', 'hotmail');


print "Enviat!";


?>
