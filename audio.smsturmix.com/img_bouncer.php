<?php
$host = $_SERVER["HTTP_HOST"];
$file = $_SERVER['PHP_SELF'];

if(		($host=='www.smsturmix.ct')
	||	($host=='www.smsturmix.com')
	||	($host=='smsturmix.com')
	||	($host=='mobiturmix.com')
	||	($host=='www.mobiturmix.com') )		$host = "usuaris.tinet.org/turmix/www";

else if(	($host=='photo.smsturmix.com')
	|| 	($host=='photo.smsturmix.ct')
	||	($host=='photo.mobiturmix.com') )	$host = "usuaris.tinet.org/turmix/photo";

else if(	($host=='video.smsturmix.com')
	||	($host=='video.smsturmix.ct')
	||	($host=='video.mobiturmix.com') )	$host = "usuaris.tinet.org/turmix/video";

else if(	($host=='audio.smsturmix.com')
	||	($host=='audio.smsturmix.ct')
	||	($host=='audio.mobiturmix.com') )	$host = "usuaris.tinet.org/turmix/audio";

else if(	($host=='anima.smsturmix.com')
	||	($host=='anima.smsturmix.ct')
	||	($host=='anima.mobiturmix.com') )	$host = "usuaris.tinet.org/turmix/anima";

header("Location: http://$host$file");
?>
