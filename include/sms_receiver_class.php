<?php
require_once("../DB/conexio_bd.php");
require_once("constants_smsturmix.php");

define('UNIQUE_ID_LENGTH', 10);
define('WAP_PUSH_SERVER_PROTOCOL', 'http');
define('WAP_PUSH_SERVER_IP', 'wap.smsturmix.com');
define('WAP_PUSH_SERVER_HTTP_URL_PATH', '');
define('WAP_PUSH_SERVER_PATH', '/var/www/html/wap.smsturmix.com/');

class sms_receiver_class
{
        var $telnum = '';
        var $text = '';
        var $keyword = '';
        var $provider = '';
        var $shortnum = '';
        var $_date = '';
        var $_key = '';
        var $login = '';
        var $password = '';
        var $unique_id = '';
        var $wap_push = '';

        function sms_receiver_class()
        {
        }

        function generate_unique_id()
        {
                $con_bd = new conexio_bd();
                $random_id_length = UNIQUE_ID_LENGTH;

                while(true)
                {
                        //generate a random id encrypt it and store it in $rnd_id
                        $rnd_id = crypt(uniqid(rand(),1));

                        //to remove any slashes that might have come
                        $rnd_id = strip_tags(stripslashes($rnd_id));

                        //Removing any . or / and reversing the string
                        $rnd_id = str_replace(".","",$rnd_id);
                        $rnd_id = strrev(str_replace("/","",$rnd_id));

                        //finally I take the first 10 characters from the $rnd_id
                        $rnd_id = substr($rnd_id,0,$random_id_length);

                        $rnd_id = strtolower($rnd_id);
                        $numbers = array('0','1','2','3','4','5','6','7','8','9');
                        $replace = 'a';
                        $rnd_id = str_replace($numbers, 'a', $rnd_id);

                        $sql  = "       select unique_id from peticio_descarrega where unique_id='$rnd_id'";
                        $res = $con_bd->sql_query($sql);
                        if($res==null) die(ERROR_SMS_TECNICAL_PROBLEM);
                        else if($res->numRows()==0) return $rnd_id;
                }

        }

        function generate_wap_push($unique_id)
        {
                $wap_push_url = WAP_PUSH_SERVER_PROTOCOL."://".WAP_PUSH_SERVER_IP."/".WAP_PUSH_SERVER_HTTP_URL_PATH."".$unique_id."/";
                $wap_server_path = WAP_PUSH_SERVER_PATH."".$unique_id;
                mkdir($wap_server_path);

                $php_content = '<?php
require_once("wap_content_delivery_class.php");

if (isset($_SERVER["HTTP_USER_AGENT"])) $user_agent = $_SERVER["HTTP_USER_AGENT"];
else                                    $user_agent = "";

$wap_deliver = new wap_content_delivery_class();
$wap_deliver->user_agent = $user_agent;
$wap_deliver->unique_id = "'.$unique_id.'";
$wap_deliver->deliver_content();
?>';

                if(!$handle = fopen($wap_server_path."/index.php", "w")) die(ERROR_SMS_TECNICAL_PROBLEM);
                else
                {
                        fwrite($handle, $php_content);
                        fclose($handle);
                }
                return $wap_push_url;
        }

        function deliver_wap_push_response()
        {
                // control buffering with output control functions
                ob_start();

                // anti-cache headers
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Pragma: no-cache");

                // send content headers
                header("Content-type: text/plain; charset=utf-8");
                print utf8_encode($this->wap_push);

                // flush content with ordered headers
                ob_end_flush();
        }

        function process_sms()
        {
                $con_bd = new conexio_bd();
                $sql  = "       insert into sms_rebut(telnum, text_, keyword, operator, shortnum, data_sent, key_, login, password_, data_insert)
                                values ('".($this->telnum)."','".($this->text)."','".($this->keyword)."','".($this->provider)."','".($this->shortnum)."','".($this->_date)."','".($this->_key)."','".($this->login)."','".($this->password)."', NOW())";
                $res = $con_bd->sql_query($sql);
                if($res==null) die(ERROR_SMS_TECNICAL_PROBLEM);

                $res = $con_bd->sql_query("select max(id_sms_rebut) from sms_rebut");
                $row = $res->fetchRow();
                $id_sms_rebut = $row[0];

                $this->text = trim($this->text); //Cal eliminar possibles espais als costats del codi de contingut
                $sql  = "       select id_mm from mm where codi_contingut='".($this->text)."'";
                $res = $con_bd->sql_query($sql);
                if($res==null) die(ERROR_SMS_TECNICAL_PROBLEM);
                else if($res->numRows()==0) die(ERROR_SMS_CONTENT_NOT_EXISTS.($this->text));

                $row = $res->fetchRow();
                $id_mm = $row['id_mm'];
                $this->unique_id = $this->generate_unique_id();
                $this->wap_push = $this->generate_wap_push($this->unique_id);

                $sql  = "       insert into peticio_descarrega(unique_id, id_mm, wap_push, id_sms_rebut, estat, descarregues_disponibles, data_insert)
                                values ('".($this->unique_id)."', $id_mm, '".($this->wap_push)."', $id_sms_rebut, 'PENDENT', 2, NOW())";
                $res = $con_bd->sql_query($sql);
                if($res==null) die(ERROR_SMS_TECNICAL_PROBLEM);
        }
}
?>
