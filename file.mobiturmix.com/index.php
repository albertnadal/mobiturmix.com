<?php
require_once("wap_content_delivery_class.php");

$nom_fitxer = mysql_escape_string($_SERVER['PHP_SELF']);
$params = trim($nom_fitxer, "/");

list($codi_contingut, $id_handset) = explode("/", $params);
$codi_contingut = mysql_escape_string($codi_contingut);
$id_handset = mysql_escape_string($id_handset);

if (isset($_SERVER["HTTP_USER_AGENT"])) $user_agent = $_SERVER["HTTP_USER_AGENT"];
else                                    $user_agent = "";

$wap_deliver = new wap_content_delivery_class();
$wap_deliver->user_agent = $user_agent;
$wap_deliver->deliver_content_codi_contingut_id_handset($codi_contingut, $id_handset);

?>
