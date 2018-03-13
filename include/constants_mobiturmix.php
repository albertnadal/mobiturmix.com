<?php
$root = substr($_SERVER["HTTP_HOST"], strrpos($_SERVER["HTTP_HOST"], '.') + 1);

if(USE_EXTERNAL_IMAGES)
{
	define('IMG_WWW_DOMAIN',	'http://turmix.freehostia.com/www/');
	define('IMG_PHOTO_DOMAIN',	'http://turmix.freehostia.com/photo/');
	define('IMG_VIDEO_DOMAIN',	'http://turmix.freehostia.com/video/');
	define('IMG_AUDIO_DOMAIN',	'http://turmix.freehostia.com/audio/');
	define('IMG_ANIMA_DOMAIN',	'http://turmix.freehostia.com/anima/');
}
else
{
	define('IMG_WWW_DOMAIN',	'http://www.mobiturmix.'.$root);
	define('IMG_PHOTO_DOMAIN',	'http://photo.mobiturmix.'.$root);
	define('IMG_VIDEO_DOMAIN',	'http://video.mobiturmix.'.$root);
	define('IMG_AUDIO_DOMAIN',	'http://audio.mobiturmix.'.$root);
	define('IMG_ANIMA_DOMAIN',	'http://anima.mobiturmix.'.$root);
}

define('META_DESCRIPTION_ES', '<meta name="description" lang="es" content="MobiTurmix es divertido! Sube fotos, vídeos, música,... todo lo que quieras, MobiTurmix te lo envía al móvil al instante! Carga y comparte tu contenido!" />');
define('META_DESCRIPTION_EN', '<meta name="description" lang="en" content="MobiTurmix is funny! Upload photos, videos, music,... all you want, MobiTurmix send it to your mobile at the moment! Enter and share your content!" />');

define('META_DESCRIPTION_AUDIO_EN', '<meta name="description" lang="en" content="MobiTurmix is funny! Upload your music, MobiTurmix will send it to your mobile at the moment! Enter to our AudioTeca and share your music with everybody!" />');
define('META_DESCRIPTION_AUDIO_ES', '<meta name="description" lang="es" content="MobiTurmix es divertido! Sube tu música, MobiTurmix te la envía al móvil al instante!  Entra en nuestra AudioTeca y comparte tu música con todo el mundo! "/>');

define('META_DESCRIPTION_VIDEO_EN', '<meta name="description" lang="en" content="MobiTurmix is funny! Upload your videos, MobiTurmix will send it to your mobile at the moment! Enter to our VideoTeca and share your videos with everybody!" />');
define('META_DESCRIPTION_VIDEO_ES', '<meta name="description" lang="es" content="MobiTurmix es divertido! Sube tus vídeos, MobiTurmix te los envía al móvil al instante!  Entra en nuestra VideoTeca y comparte tus vídeos con todo el mundo! "/>');

define('META_DESCRIPTION_PHOTO_EN', '<meta name="description" lang="en" content="MobiTurmix is funny! Upload your fotos, MobiTurmix will send it to your mobile at the moment! Enter to our FotoTeca and share your fotos with everybody!" />');
define('META_DESCRIPTION_PHOTO_ES', '<meta name="description" lang="es" content="MobiTurmix es divertido! Sube tus fotos, MobiTurmix te las envía al móvil al instante!  Entra en nuestra FotoTeca y comparte tus fotos con todo el mundo! "/>');

define('META_DESCRIPTION_ANIMA_EN', '<meta name="description" lang="en" content="MobiTurmix is funny! Make your animation, MobiTurmix will send it to your mobile at the moment! Enter to our AnimaTeca and share your animations with everybody!" />');
define('META_DESCRIPTION_ANIMA_ES', '<meta name="description" lang="es" content="MobiTurmix es divertido! Crea tu animación, MobiTurmix te la envía al móvil al instante!  Entra en nuestra AnimaTeca y comparte tus animaciones con todo el mundo! "/>');

define('META_COPYRIGHT', '<meta name="copyright" content="(c)2007 MobiTurmix" />');
define('META_AUTHOR', '<meta name="author" content="MobiTurmix - http://www.mobiturmix.com" />');
define('META_CONTENT_TYPE', '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
define('META_KEYWORDS_ES', '<meta name="keywords" lang="es" content="mobiturmix, mobiturmix.com, www.mobiturmix.com, móvil, descargar, contenido, multimedia, foto, vídeo, audio, animación, comunidad, turmix, wap" />');
define('META_KEYWORDS_EN', '<meta name="keywords" lang="en" content="mobiturmix, mobiturmix.com, www.mobiturmix.com, phone, handset, download, content, multimedia, photo, video, audio, animation, comunity, turmix, wap" />');
define('META_ROBOTS_INDEX_FOLLOW', '<meta name="ROBOTS" content="INDEX, FOLLOW" />');
define('META_ROBOTS_INDEX_NOFOLLOW', '<meta name="ROBOTS" content="INDEX, NOFOLLOW" />');
define('META_ROBOTS_NOINDEX_NOFOLLOW', '<meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />');
define('META_ROBOTS_NOINDEX_FOLLOW', '<meta name="ROBOTS" content="NOINDEX, FOLLOW" />');

define('DOCTYPE', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
define('HTML', '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">');
define('BODY', "<body style=\"margin-top:0px;margin-bottom:0px;margin-left:0px;margin-right:0px;background-color:#FFFFFF;\">\n");
define('XML_ENCODING', '<?xml version="1.0" encoding="UTF-8" ?>');

/*
define('TITLE_SMSTURMIX', '<title>MobiTurmix - Tu contenido multimedia en tu móvil</title>');
define('TITLE_PHOTOTURMIX', '<title>MobiTurmix - Tus fotos en tu móvil</title>');
define('TITLE_VIDEOTURMIX', '<title>MobiTurmix - Tus vídeos en tu móvil</title>');
define('TITLE_AUDIOTURMIX', '<title>MobiTurmix - Tu música en tu móvil</title>');
define('TITLE_ANIMATURMIX', '<title>MobiTurmix - Tus animaciones en tu móvil</title>');
*/

        define('TITLE_SMSTURMIX', '<title>MobiTurmix - Your multimedia to your mobile</title>');
        define('TITLE_PHOTOTURMIX', '<title>MobiTurmix - Your photos to your mobile</title>');
        define('TITLE_VIDEOTURMIX', '<title>MobiTurmix - Your videos to your mobile</title>');
        define('TITLE_AUDIOTURMIX', '<title>MobiTurmix - Your music to your mobile</title>');
        define('TITLE_ANIMATURMIX', '<title>MobiTurmix - Your animations to your mobile</title>');

define('BACKGROUND_CATEGORIES', ''); //'http://www.mobiturmix.'.$root.'/f.png');

define('LINK_LOGO', 'http://www.mobiturmix.'.$root.'/logo.gif');
define('LINK_ARROWS', 'http://www.mobiturmix.'.$root.'/arrows.gif');
define('LINK_LOADING', 'http://www.mobiturmix.'.$root.'/loading.gif');


define('MAIL_HTML_MESSAGE', 'Introduce tu e-mail aquí');
define('FOOT_HTML_MESSAGE', '<div style="width:900px"><span style="font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 9px; color: #999999;"><b>(c) 2007 <a href="mailto:turmix@tinet.org">MobiTurmix</a></b>, All rights reserved. No part of this website may be copied without permision of the author. All <b>MobiTurmix</b> services show mobile devices compatibilities with all the content in the website. Only one message is required if you want to download a content via SMS (1.2e+iva only for spain users).</span><span style="font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 9px; color: #000000"> Direct WAP downloads are 100% free of charge.</span></div>');

/*define('FOOT_HTML_MESSAGE', '<img src="http://www.mobiturmix.'.$root.'/p_foot.gif" alt=""/>'); '<div id="foot">(c) 2007 SmsTurmix, todos los derechos reservados. Ninguna parte del site puede ser reproducida sin
        el permiso del propietario de los derechos de autor. Todos los servicios de SmsTurmix muestran las
        compatibilidades del contenido multimedia con tu teléfono móvil o el que tu quieras. El coste por
        descarga es de 1 solo sms (1.2e + iva), tráfico <em>gprs</em> no incluido. Site desarollado por
        <a href="http://www.girafatools.com">GirafaTools</a>.</div>'); //<img src="p_foot.gif" width="882" height="25" />*/

define('CAT_AMOR_IMAGE',		'http://www.mobiturmix.'.$root.'/cat1.gif');
define('CAT_DEPORTES_IMAGE',		'http://www.mobiturmix.'.$root.'/cat2.gif');
define('CAT_MUSICA_IMAGE',		'http://www.mobiturmix.'.$root.'/cat3.gif');
define('CAT_AMIGOS_IMAGE',		'http://www.mobiturmix.'.$root.'/cat4.gif');
define('CAT_FAMILIA_IMAGE',		'http://www.mobiturmix.'.$root.'/cat5.gif');
define('CAT_PAISAJES_IMAGE',		'http://www.mobiturmix.'.$root.'/cat6.gif');
define('CAT_VIAJES_IMAGE',		'http://www.mobiturmix.'.$root.'/cat7.gif');
define('CAT_DIVERTIDAS_IMAGE',		'http://www.mobiturmix.'.$root.'/cat8.gif');
define('CAT_EROTICAS_IMAGE',		'http://www.mobiturmix.'.$root.'/cat9.gif');
define('CAT_MES_DESCARGAT_IMAGE', 	'http://www.mobiturmix.'.$root.'/lmd.gif');
define('CAT_MES_RECENT_IMAGE',		'http://www.mobiturmix.'.$root.'/lmr.gif');

define('MSG_RESULTATS_BUSQUEDA',	'http://www.mobiturmix.'.$root.'/rdlb.gif');
define('MSG_CATEGORIA_BUIDA',		'http://www.mobiturmix.'.$root.'/cb.gif');
define('MSG_BUSQUEDA_SENSE_RESULTATS',	'http://www.mobiturmix.'.$root.'/nor.gif');

define('WWW_DOMAIN',    'http://www.mobiturmix.'.$root);
define('PHOTO_DOMAIN',  'http://photo.mobiturmix.'.$root);
define('VIDEO_DOMAIN',  'http://video.mobiturmix.'.$root);
define('AUDIO_DOMAIN',  'http://audio.mobiturmix.'.$root);
define('ANIMA_DOMAIN',  'http://anima.mobiturmix.'.$root);

?>
