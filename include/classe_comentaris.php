<?php
require_once("classe_sessio_usuari.php");
include("constants_smsturmix.php");
require_once("conexio_bd.php");

class comentaris
{
        function comentaris()
        {
        }

        function obtenir_comentaris_contingut($codi_contingut, $pag=1)
        {
                $con_bd = new conexio_bd();
		$sql = "select	count(c.id_coment)
                        from    coment c,
                                user u,
                                mm mm
                        where   u.id_user = c.id_user
                                and mm.codi_contingut = '$codi_contingut'";
		$res = $con_bd->sql_query($sql);
		$row = $res->fetchRow();
		$total_comentaris = $row[0];

		$sql = "select	c.id_coment as id_coment,
				c.comment as coment,
				c.id_user as id_user,
				c.data_insert as data_insert,
				u.login as login
			from	coment c,
				user u,
				mm mm
			where	u.id_user = c.id_user
				and mm.codi_contingut = '$codi_contingut'
			order by c.data_insert desc
			limit ".(($pag - 1)*COMMENTS_PER_PAGE).", ".(COMMENTS_PER_PAGE)."";

//		print "SQL :$sql";

                $res = $con_bd->sql_query($sql);
		$comentaris = array();
                while($row = $res->fetchRow())
		{
			$comentari = array();
			$comentari['id_comment'] = $row['id_comment'];
			$comentari['id_user'] = $row['id_user'];
			$comentari['login'] = $row['login'];
			$comentari['comment'] = $row['coment'];
			$comentari['data_insert'] = $row['data_insert'];
			array_push($comentaris, $comentari);
		}
                return array($total_comentaris, $comentaris);
        }

	function obtenir_panell_comentaris_contingut($codi_contingut, $pag)
	{	
		$usuari_esta_loggejat = $_SESSION["sessio_usuari"]->esta_loggejat;
		$res_comentaris = $this->obtenir_comentaris_contingut($codi_contingut, $pag);
		$total_comentaris = $res_comentaris[0];
		$total_pagines = ceil($total_comentaris / COMMENTS_PER_PAGE);
		$comentaris = $res_comentaris[1];
		$total_comentaris_pagina = count($comentaris);

                print "<div class=\"comments\">\n";

		print "<div class=\"comments_heading\">\n";
		print "<table border=0 style=\"border-collapse:collapse;width:100%\" align=\"center\">";
		print "<tr><td style=\"width:30%;\" valign=\"top\"><h3>Comments</h3></td>\n";
		print "<td>\n";

                if($usuari_esta_loggejat) print " <span class=\"results\"><a href=\"#\">Post a comment</a></span>\n";
                else print "<span>Would you like to comment?<br/>log in, or <a target=\"_top\" href=\"http://www.mobiturmix.com/accounts/new_account.php\">join MobiTurmix</a> for a free account.</span>\n";

		print "</td></tr>";
		print "</table>";

		print "</div>\n";

		print "<ul class=\"comments_results\">\n";
		if(!$total_comentaris) print "<br/><center>No comments yet, be the first to leave a comment.</center>";
		foreach($comentaris as $comentari)
		{
			$login = $comentari['login'];
			$comment = $comentari['comment'];
			print " <li class=\"comment\">\n";
			print "         <img class=\"thumb\" src=\"http://www.mobiturmix.com/accounts/uu.gif\">\n";
			print "         <h4><a href=\"\">$login</a> (2 days ago)</h4>\n";
			print "         <p class=\"desc\">$comment</p>\n";
			print " </li>\n";
		}
		print "</ul>\n";

		if($total_pagines>1)
		{
			print "<p class=\"comments_paginator\">\n";
			print " <span>Pages: ";
			for($p=1; $p<$total_pagines; $p++)
				print "$p ";

			print "|&nbsp;</span>\n";

	                print " <a href=\"#\">Previous</a>&nbsp;\n";
			print " <a href=\"#\">Next</a>\n";
			print "</p>\n";
		}

                print "</div>\n";
	}
}

?>
