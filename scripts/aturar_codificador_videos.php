#!/usr/bin/php -q
<?php
require_once("classe_video_convert.php");

define("LOCK_FILE", "/var/www/html/scripts/codificador_videos.lock");

if(!file_exists(LOCK_FILE)) die("\nStopping: [ FAIL ]\n");
$pid = file_get_contents(LOCK_FILE);
system("/usr/bin/kill $pid");
unlink(LOCK_FILE);
print "\nStopping: [ OK ]\n";

$queue = new video_convert();
$queue->posar_videos_en_proces_com_a_pendents(); //Codifica els videos pendents sense parar..

?>
