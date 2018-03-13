<?php
include("classe_control_inputs.php");
require("classe_usuari.php");

/*
function file_put_contents($n, $d, $flag = false)
{
    $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
    $f = @fopen($n, $mode);
    if ($f === false) {
        return 0;
    } else {
        if (is_array($d)) $d = implode($d);
        $bytes_written = fwrite($f, $d);
        fclose($f);
        return $bytes_written;
    }
}*/

// control buffering with output control functions
session_start();
ob_start();

// anti-cache headers
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M 2006 H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");

// send content headers
header("Content-Type: image/jpeg");
//header('Content-Disposition: attachment; filename="preview.jpg"');
$codi_usuari = $_SESSION["usuari"]->codi_usuari;
$contingut = $_SESSION["usuari"]->wallpapers['handset_preview'];
print $contingut;

file_put_contents("/var/www/html/tmp/$codi_usuari.gif", $contingut);

// flush content with ordered headers
ob_end_flush();

?>
