<?php
include("classe_control_inputs.php");
require("classe_alta_usuari.php");

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
header("Content-Type: image/gif");
//header('Content-Disposition: attachment; filename="preview.jpg"');
$contingut = $_SESSION["alta_usuari"]->obtenir_imatge_codi_validacio();
print $contingut;

// flush content with ordered headers
ob_end_flush();

?>

