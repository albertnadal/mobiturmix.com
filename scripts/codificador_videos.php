#!/usr/bin/php -q
<?php
require_once("classe_video_convert.php");

define("LOCK_FILE", "/var/www/html/scripts/codificador_videos.lock");

//No es vol executar l'aplicacio 2 vegades, per tant es mira si hi ha el fitxer de bloqueix...
if(!file_exists(LOCK_FILE)) file_put_contents(LOCK_FILE, getmypid() , LOCK_EX);
else die("\nStarting: [ FAIL ]\n");

$queue = new video_convert();
print "\nStarting: [ OK ]\n";
$queue->iniciar_codificacio_reiterada(); //Codifica els videos pendents sense parar...

//Cal eliminar sempre el fitxer al marxar!
unlink(FILE_LOCK);
?>
