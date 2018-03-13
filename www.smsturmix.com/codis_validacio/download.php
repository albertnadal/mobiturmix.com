<?php

require('conexio_bd.php');


print_r($_POST);

if (isset($_GET["ok"])) $ok = $_GET["ok"];
elseif (isset($_POST["ok"])) $ok = $_POST["ok"];
else $ok = '';

if (isset($_POST["i"])) $i = $_POST["i"];
elseif (isset($_GET["i"])) $i = $_GET["i"];
else $i = 0;

print "[$i]<br/>";

if (isset($_GET["c"])) $c = $_GET["c"];
elseif (isset($_POST["c"])) $c = $_POST["c"];
else $c = 0;

if(($c!='')&&($i>0)&&($ok='Ok'))
{
print "OKEY!";
     $con_bd = new conexio_bd();
       $sql = "        insert into validation_code(id_validation_code, code, image, data_insert)
                       values (NULL, '".strtolower($c)."', '".addslashes(file_get_contents("/var/www/html/www.smsturmix.com/codis_validacio/".($i-1).".gif"))."', NOW())";


//print "$sql";


     $res = $con_bd->sql_query($sql);


}

print "<img src=\"".($i).".gif\">";

print "<form action=#>\n";
print "<input type=\"hidden\" value=\"".($i-1)."\" name=\"i\">";
print "<input type=\"submit\" value=\"Previous\">";
print "</form>";

print "<form method=\"POST\" action=#>\n";
print "<input type=\"text\" value=\"\" name=\"c\">\n";
print "<input type=\"hidden\" value=\"".($i+1)."\" name=\"i\">";
print "<input type=\"submit\" value=\"Ok\" name=\"ok\">";
print "</form>";









/*

$i=100;
while($i>0)
{
  system("wget http://www.youtube.com/cimg?c=JODvVwyT3HLS1MIklO1B34jc6wHPYT-bCEnVsdr27EFWhwOSbk448qLCtOOAmL6e -O $i.gif");
  $i--;
}
print "Done!";
*/

?>
