<?php
require_once("/var/www/html/DB/adodb/adodb.inc.php");
require_once("parametres_conexio_fotodenuncia.php");

class conexio_bd
{
        var $conn = null;

        function conexio_bd()
        {
                $conn = NewADOConnection(DB_MYSQL_TYPE);

		print "HOST:".DB_HOST_NAME." USER:".DB_USER." PWD:".DB_PWD." DB: ".DB_DATABASE;;

                $conn->Connect(DB_HOST_NAME, DB_USER, DB_PW, DB_DATABASE) or die ("<br><br><B>ERROR:</B> Conexió<br><br>");
                $this->conn = $conn;
        }

        function sql_query($sql)
        {
                $res =  $this->conn->Execute($sql);// or die ("<br><br><B>Error en la consulta a BD: </B>".$this->conn->ErrorMsg());
                return $res;
        }
}
?>
