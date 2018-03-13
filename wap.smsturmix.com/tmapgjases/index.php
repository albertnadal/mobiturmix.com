<?php
require_once("wap_content_delivery_class.php");

if (isset($_SERVER["HTTP_USER_AGENT"])) $user_agent = $_SERVER["HTTP_USER_AGENT"];
else                                    $user_agent = "";

$wap_deliver = new wap_content_delivery_class();
$wap_deliver->user_agent = $user_agent;
$wap_deliver->unique_id = "tmapgjases";
$wap_deliver->deliver_content();
?>