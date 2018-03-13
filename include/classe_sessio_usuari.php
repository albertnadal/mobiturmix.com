<?php

include("constants_smsturmix.php");
require_once("conexio_bd.php");
require_once("classe_interficie_usuari_account.php");

if(!isset($_COOKIE['tmixsid']))
{
	session_start();
	$sid = session_id();
	setcookie("tmixsid", $sid, strtotime("+1 day"), "/", ".mobiturmix.com", 0);
}
else
{
	$sid = $_COOKIE['tmixsid'];
	session_id($sid);
	$_SESSION['session_id'] = $sid;
}

//print "SID: $sid<br>";

//inici de la session
if(($_SERVER['PHP_SELF']!='/upload.php')&&($_SERVER['PHP_SELF']!='/accounts/new_account.php')) session_start();

if(!isset($_SESSION["sessio_usuari"])) $_SESSION["sessio_usuari"] = & new sessio_usuari($sid);

class sessio_usuari
{ 
	var $id_usuari = 0;
	var $login;
	var $name;
	var $email;
	var $esta_loggejat;
	var $id_handset;
	var $last_url = 'http://www.mobiturmix.com';
	var $last_error_message = '';
	var $last_failed_login = '';
	var $session_id;

        function sessio_usuari($sid)
        {
		$this->session_id = $sid;
		$this->inicialitzar_informacio_usuari();
        }

	function inicialitzar_informacio_usuari()
	{
                $this->login = '';
                $this->name = '';
                $this->email = '';
                $this->esta_loggejat = false;
                $this->id_handset = 0;
                $this->id_usuari = 0;
		$this->last_url = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
		$this->last_error_message = '';
		$this->last_failed_login = '';
	}

	function fer_login($login, $password)
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("select id_user, name, email, web, login from user where login='$login' and password='$password'");
		if($res->numRows())
		{
			$row = $res->fetchRow();
			$this->id_usuari = $row[0];
                        $this->name = $row[1];
                        $this->email = $row[2];
                        $this->web = $row[3];
			$this->login = $row[4];
			$this->esta_loggejat = true;
		}
		else
		{
			$this->last_error_message = 'Sorry, your login was incorrect.';
			$this->last_failed_login = $login;
		}
//print "LOCATON: ".($this->last_url)."<br>";
		header("Location: ".($this->last_url)); //Redirecciona a la url on es troba l'usuari per últim cop
	}

	function fer_logout()
	{
		if($this->esta_loggejat) $this->last_error_message = 'You have been logged out.';

		$last_url = $this->last_url;
		if(($last_url=='')||($last_url=='http://www.mobiturmix.com/accounts/my_account.php')||($last_url=='http://www.mobiturmix.com/accounts/logout.php'))
			$last_url = 'http://www.mobiturmix.com';
		$this->inicialitzar_informacio_usuari();
		//$this->finalitzar_sessio();
		//print "URL: ".($last_url);
		header("Location: $last_url"); //Redirecciona a la url on es troba l'usuari per últim cop
	}

	function obtenir_informacio_usuari()
	{
                $con_bd = new conexio_bd();

		$info = array(	'id_user'=>'',
				'login'=>'',
				'password'=>'',
				'name'=>'',
				'email'=>'',
				'web'=>'',
				'gender'=>'',
				'relationship'=>'',
				'birthday'=>'',
				'handset'=>'',
				'country'=>'',
				'state'=>'',
				'city'=>'');

		$sql = "     select  u.*,
                                                        mh.marca as marca,
                                                        h.model as model
                                                from    user u,
                                                        marca_handset mh,
                                                        handset h
                                                where   u.id_user = ".($this->id_usuari)."
                                                        and h.id_handset = u.id_handset
                                                        and mh.id_marca_handset = h.id_marca_handset";

                $res = $con_bd->sql_query($sql);
                if($res->numRows())
                {
                        $row = $res->fetchRow();
                        $info['id_user'] = $row['id_user'];
                        $info['login'] = $row['login'];
                        $info['password'] = $row['password'];
                        $info['name'] = $row['name'];
                        $info['email'] = $row['email'];
                        $info['web'] = $row['web'];
                        $info['gender'] = ucfirst(strtolower($row['gender']));
                        $info['relationship'] = ucfirst(strtolower($row['relationship']));
                        $info['birthdate'] = date('F j, Y',strtotime(str_replace(" 00:00:00", '', $row['birthday'])));
                        $info['handset'] = $row['marca']." ".$row['model'];
                        $info['country'] = $this->obtenir_nom_regio($row['id_country'], 'country');
			$info['state'] = $this->obtenir_nom_regio($row['id_state'], 'state');
			$info['city'] = $this->obtenir_nom_regio($row['id_city'], 'city');
			$coordinates = $this->obtenir_coordenades($row['id_country'], $row['id_state'], $row['id_city']);
			$info['coordinates'] = $coordinates[1];
			$info['zoom'] = $coordinates[0];
                }
		return $info;
	}

	function obtenir_coordenades($id_country, $id_state, $id_city)
	{
		if($id_city!='')	return array(12, $this->obtenir_coordenades_regio($id_city, 'city'));
		else if($id_state!='')	return array(6,  $this->obtenir_coordenades_regio($id_state, 'state'));
		else if($id_country!='')return array(4,  $this->obtenir_coordenades_regio($id_country, 'country'));
		return array(2,'0,0');
	}

	function obtenir_coordenades_regio($id, $regio)
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("select coordinates from $regio where id_$regio = $id");
                if(!$res->numRows()) return '0,0';
                $row = $res->fetchRow();
		$coordinates = $row[0];
		$x = $y = 0;
		list($y, $x) = explode(",", $coordinates);
                return "$x,$y";
	}

        function obtenir_videos_usuari()
        {
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("
                        select  codi_contingut, estat, puntuacio
                        from    mm
                        where   id_categoria_contingut = 3
                                and id_user = ".($this->id_usuari)."
                                and estat in ('APROVAT', 'DESAPROVAT')
                        order by estat asc, data_insert desc");

                if(!$res->numRows()) return null;
                $fotos = array();
                while($row = $res->fetchRow())
                {
                        $fotos[$row['codi_contingut']] = array('estat'=>$row['estat'], 'puntuacio'=>$row['puntuacio']);
                }
                return $fotos;
        }

	function obtenir_fotos_usuari()
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("
			select	codi_contingut, estat, puntuacio
			from	mm
			where	id_categoria_contingut = 1
				and id_user = ".($this->id_usuari)."
				and estat in ('APROVAT', 'DESAPROVAT')
			order by estat asc, data_insert desc");

                if(!$res->numRows()) return null;
		$fotos = array();
                while($row = $res->fetchRow())
		{
			$fotos[$row['codi_contingut']] = array('estat'=>$row['estat'], 'puntuacio'=>$row['puntuacio']);
		}
		return $fotos;
	}

	function obtenir_nom_regio($id, $regio)
	{
                $con_bd = new conexio_bd();
		$res = $con_bd->sql_query("select nom from $regio where id_$regio = $id");
		if(!$res->numRows()) return '';
		$row = $res->fetchRow();
		return utf8_encode($row[0]);
	}

	function incrustar_codi_menu_superior_sessio()
	{
		$this->last_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];

		print "<!-- account options menu -->\n";

                print "                        <form method=\"POST\" action=\"http://www.mobiturmix.com/accounts/login.php\" name=\"auth\">\n";

		print "<div align=\"right\" width=\"100%\" style=\"padding: 0pt 0pt 0px;height:21px;\" id=\"guser\">\n";

		print "<script>\n";
                print " e=gebi('guser');\n";
                print " e.style.paddingRight=((screen.width - 905)/2)+\"px\";\n";
                print "</script>\n";

                print "<div style=\"color:#C50076;font-family:Arial,sans-serif;font-size:11px;font-size-adjust:none;font-style:normal;font-variant:normal;font-weight:bold;line-height:normal;margin-top:0px;height:21px;background:#FFFFFF url(http://www.mobiturmix.com/bms.gif) no-repeat right bottom;\">\n";


                print "<table border=0 style=\"border-collapse:collapse;margin-top:1px;\">\n";
                print "<tr>\n";

		if($this->last_error_message!='')
		{
			print "<td valign=\"middle\" style=\"font-family:tahoma,arial;font-size:11px;font-size-adjust:none;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;color:#C50076\">".(utf8_encode($this->last_error_message))."</td>\n";
			$this->last_error_message = '';
		}

		if(!$this->esta_loggejat)
		{
	                print "<td valign=\"middle\">\n";
			if($this->last_failed_login!='') $login_value = $this->last_failed_login; else $login_value = 'Your username';

        	        print " <input type=\"text\" onfocus=\"if(this.value=='Your username'){this.value='';}\" tabindex=\"1\" maxlength=\"70\" value=\"$login_value\" class=\"mtinput\" name=\"login\"/>\n";
                	print "</td>\n";
	                print "<td valign=\"middle\">\n";
        	        print "<input type=\"password\" tabindex=\"2\" onfocus=\"if(this.value=='".(utf8_encode("Your password"))."'){this.value=''}\" maxlength=\"32\" value=\"".(utf8_encode("Your password"))."\" style=\"width: 100px;\" class=\"mtinput\" name=\"pwd\"/>\n";
                	print "</td>\n";

	                print "<td valign=\"middle\">\n";
        	        print "<a href=\"javascript:document.auth.submit();\"><span><img border=0 alt=\"Log In\" title=\"Log In\" src=\"http://www.mobiturmix.com/login.gif\"></span></a>\n";
                	print "</td>\n";

	                print "<td>\n";
        	        print "<a href=\"http://www.mobiturmix.com/accounts/new_account.php\"><span><img border=0 src=\"http://www.mobiturmix.com/signup.gif\" alt=\"Sign Up\" title=\"Sign Up\"></span></a>\n";
                	print "</td>\n";
		}
		else
		{
                        print "<td valign=\"middle\" style=\"font-family:tahoma,arial;font-size:11px;font-size-adjust:none;font-style:normal;font-variant:normal;font-weight:normal;line-height:normal;color:#C50076\">Hi, <b>".(utf8_encode($this->name))."</b>&nbsp;</td>\n";

	                print "<td>\n";
        	        print "<a href=\"http://www.mobiturmix.com/accounts/my_account.php\"><span><img alt=\"My Account\" title=\"My Account\" border=0 src=\"http://www.mobiturmix.com/myaccount.gif\"></span></a>\n";
                	print "</td>\n";

	                print "<td>\n";
        	        print "<a href=\"http://www.mobiturmix.com/accounts/logout.php\"><span><img alt=\"Log Out\" title=\"Log Out\" border=0 src=\"http://www.mobiturmix.com/logout.gif\"></span></a>\n";
                	print "</td>\n";
		}
                print "<td>\n";
                print "<a href=\"#\"><span><img alt=\"Help\" title=\"Help\" border=0 src=\"http://www.mobiturmix.com/help.gif\"></span></a>\n";
                print "</td>\n";
		print "<td width='10px'></td>";
                print "</tr>\n";
                print "</table>\n";
                print "                </div>\n";
                print "</div>\n";

                print "                        </form>\n";
                print "<!-- end account options menu -->\n";
	}

	function incrustar_codi_css_menu_superior_sessio()
	{
		print "
.mtinput {
vertical-align: top;
border:1px solid #A7A6AA;
color:#000000;
font-family:Arial,sans-serif;
font-size:10px;
font-size-adjust:none;
font-stretch:normal;
font-style:normal;
font-variant:normal;
font-weight:normal;
height:15px;
line-height:normal;
margin:0pt 0px 0pt 3px;
padding:0px 0px;
width:100px;
background-image:url('http://www.mobiturmix.com/mti_bg.gif');
}";
	}

	function generar_nou_codi_validacio()
	{
		$con_bd = new conexio_bd();
                $res = $con_bd->sql_query("select max(id_validation_code) from validation_code");
                $row = $res->fetchRow();
                $this->id_validation_code = (rand()%($row[0])); //S'escull un codi de validacio aleatoriament

                $res = $con_bd->sql_query("select code from validation_code where id_validation_code=".($this->id_validation_code));
                $row = $res->fetchRow();
		$this->codi = strtolower($row[0]);
	}

	function obtenir_imatge_codi_validacio()
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("select image from validation_code where id_validation_code=".($this->id_validation_code));
                $row = $res->fetchRow();
		return $row[0];
	}

	function show_my_account()
	{
                if(!$this->esta_loggejat)
                {
			$this->last_error_message = 'You must be logged in.';
        	        $last_url = $this->last_url;
	                if(($last_url=='')||($last_url=='http://www.mobiturmix.com/accounts/my_account.php')||($last_url=='http://www.mobiturmix.com/accounts/logout.php'))
				$last_url = 'http://www.mobiturmix.com';
	                header("Location: $last_url"); //Redirecciona a la url on es troba l'usuari per últim cop
		}
		else
		{
			$iface = new interficie_usuari_account();
			$iface->show_user_account();
		}
	}

	function guardar_usuari_a_bd()
	{
		$con_bd = new conexio_bd();
		$login = $this->informacio['my_pseudo'];
		$passwd = $this->informacio['my_pwd'];
		$name = $this->informacio['my_name'];
		$email = $this->informacio['my_email'];
		$web = mysql_escape_string('');
		$id_gender = $this->informacio['my_gender'];
		if($id_gender == 1) $gender = 'MALE';
		else $gender = 'FEMALE';

		$birth_day = $this->informacio['my_birth_day'];
                $birth_month = $this->informacio['my_birth_month'];
                $birth_year = $this->informacio['my_birth_year'];
		$birthday = "$birth_year-$birth_month-$birth_day 00:00:00";

		$relationship = 'SINGLE';
		$id_country = $this->informacio['my_country'];
                $id_state = $this->informacio['my_state'];
                $id_city = $this->informacio['my_city'];

                $sql = "insert into user(id_user, login, password, name, email, web, gender, relationship, birthday, id_country, id_state, id_city, data_insert)
		values (null, '$login', '$passwd', '$name', '$email', '$web', '$gender', '$relationship', '$birthday', $id_country, $id_state, $id_city, NOW())";
//		print "SQL: $sql<br>";

		$res = $con_bd->sql_query("$sql");
	}

	function finalitzar_sessio()
	{
		session_start();
		// Destruye todas las variables de la sesi&oacute;n
		session_unset();
		// Finalmente, destruye la sesi&oacute;n
		session_destroy();
	}
}
?>
