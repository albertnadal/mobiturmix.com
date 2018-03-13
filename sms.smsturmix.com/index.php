<?php
require_once('sms_receiver_class.php');

if (isset($_GET["telnum"])) $telnum = $_GET["telnum"];
elseif (isset($_POST["telnum"])) $telnum = $_POST["telnum"];
else $telnum = '';

if (isset($_GET["text"])) $text = $_GET["text"];
elseif (isset($_POST["text"])) $text = $_POST["text"];
else $text = '';

if (isset($_GET["keyword"])) $keyword = $_GET["keyword"];
elseif (isset($_POST["keyword"])) $keyword = $_POST["keyword"];
else $keyword = '';

if (isset($_GET["provider"])) $provider = $_GET["provider"];
elseif (isset($_POST["provider"])) $provider = $_POST["provider"];
else $provider = '';

if (isset($_GET["shortnum"])) $shortnum = $_GET["shortnum"];
elseif (isset($_POST["shortnum"])) $shortnum = $_POST["shortnum"];
else $shortnum = '';

if (isset($_GET["date"])) $date = $_GET["date"];
elseif (isset($_POST["date"])) $date = $_POST["date"];
else $date = '';

if (isset($_GET["key"])) $key = $_GET["key"];
elseif (isset($_POST["key"])) $key = $_POST["key"];
else $key = '';

if (isset($_GET["login"])) $login = $_GET["login"];
elseif (isset($_POST["login"])) $login = $_POST["login"];
else $login = '';

if (isset($_GET["password"])) $password = $_GET["password"];
elseif (isset($_POST["password"])) $password = $_POST["password"];
else $password = '';

$sms_receiver = new sms_receiver_class();
$sms_receiver->telnum = $telnum;
$sms_receiver->text = $text;
$sms_receiver->keyword = $keyword;
$sms_receiver->provider = $provider;
$sms_receiver->shortnum = $shortnum;
$sms_receiver->_date = $date;
$sms_receiver->_key = $key;
$sms_receiver->login = $login;
$sms_receiver->password = $password;
$sms_receiver->process_sms();
$sms_receiver->deliver_wap_push_response();
?>
