<?php
//Gestor de contingut
require_once("../DB/conexio_bd.php");
require_once("constants_smsturmix.php");

if( isset($_POST["pag"]) ) $pag = $_POST["pag"];
elseif ( isset($_GET["pag"]) ) $pag = $_GET["pag"];
else $pag = 1;

if( isset($_POST["imh"]) ) $imh = $_POST["imh"];
elseif ( isset($_GET["imh"]) ) $imh = $_GET["imh"];
else $imh = 1;

if( isset($_POST["ih"]) ) $ih = $_POST["ih"];
elseif ( isset($_GET["ih"]) ) $ih = $_GET["ih"];
else $ih = 0;

if( isset($_POST["op"]) ) $op = $_POST["op"];
elseif ( isset($_GET["op"]) ) $op = $_GET["op"];
else $op = "";

if( isset($_POST["mm"]) ) $mm = $_POST["mm"];
elseif ( isset($_GET["mm"]) ) $mm = $_GET["mm"];
else $mm = array();

$con_bd = new conexio_bd();

function SQL_obtenir_marques_handsets($con_bd)
{
	$res = $con_bd->sql_query("select * from marca_handset");
	return $res;
}

function SQL_obtenir_info_handset($con_bd, $ih)
{
	$sql = "select		h.model as model, mh.marca as marca
		from		handset h,
				marca_handset mh
		where		h.id_handset = $ih
				and mh.id_marca_handset = h.id_marca_handset";
	$res = $con_bd->sql_query($sql);
	return $res;
}

function SQL_obtenir_info_imatge_handset($con_bd, $ih)
{
	$sql = "select		*
		from		imatge_handset ih
		where		ih.id_handset = $ih";
	$res = $con_bd->sql_query($sql);
	return $res;
}

function SQL_obtenir_handsets_marca($con_bd, $imh=1)
{
	$sql = "
		select		*
		from		handset
		where		id_marca_handset = $imh
		order by	model asc";
	$res = $con_bd->sql_query($sql);
	return $res;
}

function SQL_handset_te_preview_gran($con_bd, $ih)
{
	$sql = "
		select		count(id_imatge_handset)
		from		imatge_handset
		where		id_handset = $ih";
	$res = $con_bd->sql_query($sql);
	$row=$res->fetchRow();
	return $row[0]>0;
}

function SQL_handset_te_preview_petit($con_bd, $ih)
{
	$sql = "
		select		count(h.id_handset)
		from		wurfl_thumbnail wt,
				handset h
		where		id_handset = $ih
				and wt.id_wurfl_device = h.id_wurfl_device";
	$res = $con_bd->sql_query($sql);
	$row=$res->fetchRow();
	return $row[0]>0;
}

function llistar_handsets_marca($con_bd, $imh=1)
{
	$res = SQL_obtenir_marques_handsets($con_bd);
	print "<center>\n";
	print "<h3>Handsets</h3>\n";

	print "<table border=1 style=\"border-collapse:collapse;font-size:12px;\">\n";
	print "\t<tr>\n";
	print "\t\t<td align=\"center\" colspan=\"3\">\n";
	print "\t\t\t<select name=\"imh\" style=\"width:130px;\" onchange=\"window.location='control_handsets.php?op=llistar_handsets&imh='+this.options[this.selectedIndex].value;\">\n";
	while($row=$res->fetchRow())
	{
		$id_marca_handset = $row['id_marca_handset'];
		$marca = $row['marca'];

		if($id_marca_handset==$imh) $selected="selected"; else $selected="";
		print "\t\t\t<option value=\"$id_marca_handset\" $selected>$marca\n";
	}
	print "\t\t\t</select></br>\n";

	print "\t\t</td>\n";
	print "\t</tr>\n";
	$res = SQL_obtenir_handsets_marca($con_bd, $imh);
	while($row=$res->fetchRow())
	{
		$id_handset = $row['id_handset'];
		$model = $row['model'];
		print "\t<tr>\n";
		print "\t\t<td width=\"60%\">\n";
		print "\t\t\t<a target=\"action\" href=\"control_handsets.php?op=info_handset&ih=$id_handset\">$model</a><br />\n";
		print "\t\t</td>\n";

		print "\t\t<td width=\"20%\">\n";
		$te_preview_gran = SQL_handset_te_preview_gran($con_bd, $id_handset);
		if($te_preview_gran) print "<img src=\"ok.gif\">\n";
		else print "<img src=\"d.gif\">\n";
		print "\t\t</td>\n";

		print "\t\t<td width=\"20%\">\n";
		$te_preview_petit = SQL_handset_te_preview_petit($con_bd, $id_handset);
		if($te_preview_petit) print "<img src=\"ok.gif\">\n";
		else print "<img src=\"d.gif\">\n";
		print "\t\t</td>\n";

		print "\t</tr>\n";
	}
	print "</table>\n";
	print "</center>\n";
}

function mostrar_info_handset($con_bd, $ih=0)
{
	$info = SQL_obtenir_info_handset($con_bd, $ih);
	$row=$info->fetchRow();
	$marca = $row['marca'];
	$model = $row['model'];
	print "<table align=\"center\" cellspacing=\"0\" cellpadding=\"5\" border=1 style=\"border-collapse:collapse;\" width=\"95%\">\n";
	print "\t<tr>\n";

	//Mostra el nom i model del handset escollit
	print "\t\t<td colspan=\"3\" style=\"background:#E8E8E8;font-size:16px;\" valign=\"middle\">\n";
	print "<b>$marca - $model</b>\n";
	print "\t\t</td>\n";

	print "\t</tr>\n";
	print "\t<tr>\n";

	//Mostra el preview petit del handset
	print "\t\t<td align=\"center\" style=\"background:#F3F3F3;font-size:16px;width:250px\" valign=\"middle\">\n";
	print "\t\t\t<table border=0>\n";
	print "\t\t\t<tr>\n";
	print "\t\t\t\t<td align=\"center\">\n";
	print "\t\t\t\t\t<img border=1 src=\"ph.php?ih=$ih\"><br />\n";
	print "\t\t\t\t\t(72 x 72)\n";
	print "\t\t\t\t</td>\n";

	print "\t\t\t\t<td valign=\"middle\" align=\"center\">\n";
	print "\t\t\t\t\t<input type=\"file\" name=\"mini\"><br /><br />\n";
	print "\t\t\t\t\t<input type=\"button\" value=\" Update \">&nbsp;\n";
	print "\t\t\t\t\t<input type=\"button\" value=\" Remove \">\n";
	print "\t\t\t\t</td>\n";
	print "\t\t\t</tr>\n";
	print "\t\t\t</table>\n";
	print "\t\t</td>\n";

	//Mostra info del handset
	$info = SQL_obtenir_info_imatge_handset($con_bd, $ih);
	$row=$info->fetchRow();
	$orientacio = $row['orientacio_pantalla'];
	$x_ini	= $row['x_ini'];
	$y_ini	= $row['y_ini'];
	$x_fi	= $row['x_fi'];
	$y_fi	= $row['y_fi'];

	print "\t\t<td style=\"background:#F3F3F3;font-size:16px;width:175px;\" valign=\"top\">\n";
	print "\t\t\tScreen:\n";
	print "\t\t\t<select name=\"screen\">\n";
	$tipus_pantalles = array('NORMAL'=>'Square','VERTICAL'=>'Vertical','APAISAT'=>'Horizontal');
	foreach($tipus_pantalles as $tipus => $descripcio)
	{
		if($tipus==$orientacio) $selected='selected'; else $selected='';
		print "\t\t\t\t<option value=\"$tipus\" $selected>$descripcio\n";
	}
	print "\t\t\t</select><br />\n";

	print "\t\t\tX:&nbsp;<input type=\"text\" name=\"xini\" value=\"$x_ini\" style=\"width:60px\"><br />\n";
	print "\t\t\tY:&nbsp;<input type=\"text\" name=\"yini\" value=\"$y_ini\" style=\"width:60px\"><br />\n";
	print "\t\t\tX Offset:&nbsp;<input type=\"text\" name=\"xfi\" value=\"$x_fi\" style=\"width:60px\"><br />\n";
	print "\t\t\tY Offset:&nbsp;<input type=\"text\" name=\"yfi\" value=\"$y_fi\" style=\"width:60px\"><br />\n";
	print "\t\t</td>\n";


	print "\t\t<td style=\"background:#F3F3F3;font-size:16px;\" valign=\"middle\" align=\"center\">\n";
	print "\t\t<img src=\"back.jpg\" width=\"".($x_fi - $x_ini)."\" height=\"".($y_fi - $y_ini)."\">\n";
	print "\t\t</td>\n";

	print "\t</tr>\n";


	//Panell de selecció d'àrea de pantalla
	print "\t<tr>\n";

	print "\t\t<td colspan=\"3\" style=\"background:#F3F3F3;font-size:16px;\" valign=\"middle\">\n";
	print "\t\t</td>\n";

	print "\t</tr>\n";
	print "</table>\n";
}

function cap()
{
	print "<html>\n";
	print "<head>\n";
	print "<title>SMSturmix - Handsets Manager</title>\n";
	print "</head>\n";
	print "<body topmargin=\"5\" leftmargin=\"5\">\n";
}

function peu()
{
	print "</body>\n";
	print "</html>\n";
}


cap();
switch($op)
{
/*	case 'Aprovar':				SQL_canviar_estat_continguts($con_bd, $mm, 'APROVAT'); break;
	case 'Desaprovar':			SQL_canviar_estat_continguts($con_bd, $mm, 'DESAPROVAT'); break;
	case 'Suspendre':			SQL_canviar_estat_continguts($con_bd, $mm, 'SUSPES'); break;
	case 'crear_taula_handset_capability':	SQL_crear_taula_handset_capability($con_bd); break;
	case 'insertar_handsets':		SQL_insertar_handsets($con_bd); break;
	case 'update_capabilities':		SQL_update_capabilities($con_bd); break;*/
	case 'info_handset':			mostrar_info_handset($con_bd, $ih); break;
	case 'llistar_handsets':		llistar_handsets_marca($con_bd, $imh); break;
}
peu();

?>