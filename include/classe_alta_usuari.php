<?php

include("constants_smsturmix.php");
require_once("conexio_bd.php");


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


//inici de la session
session_start();

if(!isset($_SESSION["alta_usuari"])) $_SESSION["alta_usuari"] = & new alta_usuari();

class alta_usuari
{ 

        var $informacio = array(	'my_pseudo'	=> '',
					'my_name'	=> '',
					'my_pwd'	=> '',
					'my_email'	=> '',
					'my_brand_band'	=> '',
                                        'my_handset'	=> '',
                                        'my_gender'	=> '',
                                        'my_birth_day'	=> '',
                                        'my_birth_month'=> '',
                                        'my_birth_year'	=> '',
                                        'my_country'	=> '',
                                        'my_state'	=> '',
                                        'my_city'	=> '',
                                        'verification'	=> '');

	var $message_errors = array(	'my_pseudo'     => '',
                                        'my_name'       => '',
                                        'my_pwd'        => '',
                                        'my_email'      => '',
                                        'my_brand_band' => '',
                                        'my_handset'    => '',
                                        'my_gender'     => '',
                                        'my_birth_day'  => '',
                                        'my_birth_month'=> '',
                                        'my_birth_year' => '',
                                        'my_country'    => '',
                                        'my_state'      => '',
                                        'my_city'       => '',
                                        'verification'  => '');

        var $id_validation_code;
	var $codi;

        function alta_usuari()
        {
		$this->generar_nou_codi_validacio();
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

	function inicialitzar_informacio_usuari()
	{
                foreach($this->informacio as $clau => $valor)
		{
                        $this->informacio[$clau] = '';
			$this->message_errors[$clau] = '';
		}
	}

	function validar_pseudo($clau, $valor)
	{
		$fail = true;
		if($valor=='') $this->message_errors['my_pseudo'] = 'Debes indicar tu apodo en <b>mobiturmix</b>';
		else $fail=false;

		return $fail;
	}

        function validar_name($clau, $valor)
        {
                $fail = true;
                if($valor=='') $this->message_errors['my_name'] = 'Debes indicar un nombre';
                else $fail=false;

                return $fail;
        }

        function validar_pwd($clau, $valor)
        {
                $fail = true;
                if($valor=='') $this->message_errors['my_pwd'] = 'Debes indicar una contraseña de acceso';
                else $fail=false;

                return $fail;
        }

        function validar_email($clau, $valor)
        {
                $fail = true;
                if($valor=='') $this->message_errors['my_email'] = 'Debes indicar una direccion de email válida';
                else $fail=false;

                return $fail;
        }

        function validar_brand_band($clau, $valor)
        {
                $fail = true;
                if($valor=='') $this->message_errors['my_brand_band'] = 'Debes indicar la marca de tu dispositibo móvil';
                else $fail=false;

                return $fail;
        }

        function validar_handset($clau, $valor)
        {
                $fail = true;
                if(($valor=='')||($valor==0)) $this->message_errors['my_handset'] = 'Debes indicar cual es tu dispositivo móvil';
		else if((!is_numeric($valor))||($valor<0)||(($this->max_id_handset())<$valor)) $this->message_errors['my_handset'] = 'Debes indicar un dispositivo móvil válido';
                else $fail=false;

                return $fail;
        }

        function validar_gender($clau, $valor)
        {
                $fail = true;
                if(($valor=='')||($valor<=0)||(!is_numeric($valor))||($valor>2)) $this->message_errors['my_gender'] = 'Debes indicar tu sexo';
                else $fail=false;

                return $fail;
        }

        function validar_birth_day($clau, $valor)
        {
                $fail = true;
                if(($valor=='')||($valor<=0)||(!is_numeric($valor))||($valor>31)) $this->message_errors['my_birth_day'] = 'Debes indicar el día de tu nacimiento';
                else $fail=false;

                return $fail;
        }

        function validar_birth_month($clau, $valor)
        {
                $fail = true;
                if(($valor=='')||($valor<=0)||(!is_numeric($valor))||($valor>12)) $this->message_errors['my_birth_month'] = 'Debes indicar el mes de tu nacimiento';
                else $fail=false;

                return $fail;
        }

        function validar_birth_year($clau, $valor)
        {
                $fail = true;
                if(($valor=='')||($valor<=0)||(!is_numeric($valor))||($valor > date("Y"))) $this->message_errors['my_birth_year'] = 'Debes indicar el año de tu nacimiento';
                else $fail=false;

                return $fail;
        }

        function validar_state($clau, $valor)
        {
                $fail = true;
                if(($valor=='')||($valor<=0)||(!is_numeric($valor))||($valor>2)) $this->message_errors['my_state'] = 'Debes indicar almenos tu estado o comunidad';
                else $fail=false;

                return $fail;
        }

        function validar_city($clau, $valor)
        {
                $fail = true;
                if((($valor=='')||($valor<=0)||(!is_numeric($valor))||($valor>2)) && ($this->message_errors['my_state']!=''))
		{
			$this->message_errors['my_city'] = 'Debes indicar tu ciudad, o al menos tu estado o comunidad';
			$this->message_errors['my_state'] = '';
		}
                else $fail=false;

                return $fail;
        }

	function max_id_handset()
	{
                $con_bd = new conexio_bd();
                $res = $con_bd->sql_query("select max(id_handset) from handset");
                $row = $res->fetchRow();
		return $row[0];
	}

	function validar_camp_informacio_usuari($clau, $valor)
	{
		if($clau=='my_pseudo') return $this->validar_pseudo($clau, $valor);
		else if($clau=='my_name') return $this->validar_name($clau, $valor);
		else if($clau=='my_pwd') return $this->validar_pwd($clau, $valor);
		else if($clau=='my_email') return $this->validar_email($clau, $valor);
		else if($clau=='my_handset') return $this->validar_handset($clau, $valor);
		else if($clau=='my_gender') return $this->validar_gender($clau, $valor);
                else if($clau=='my_birth_day') return $this->validar_birth_day($clau, $valor);
                else if($clau=='my_birth_month') return $this->validar_birth_month($clau, $valor);
                else if($clau=='my_birth_year') return $this->validar_birth_year($clau, $valor);
                else if($clau=='my_state') return $this->validar_state($clau, $valor);
                else if($clau=='my_city') return $this->validar_city($clau, $valor);

		return false;
	}

	function validar_dades_usuari()
	{
		$fail = 0;
		foreach($this->informacio as $clau => $valor)
			$fail += $this->validar_camp_informacio_usuari($clau, $valor);
		return $fail;
	}

	function validar_codi_validacio()
	{
		$ok = true;

		if(strtolower($this->informacio['verification'])!=strtolower($this->codi))
		{
			$this->generar_nou_codi_validacio();
			if($this->informacio['verification']=='') $this->message_errors['verification'] = 'Debes introducir él código de validacion';
			else $this->message_errors['verification'] = 'El código de validacion es incorrecto';
			$ok = false;
		}
		return $ok;
	}

	function processar_dades_nou_usuari($info)
	{
		$this->inicialitzar_informacio_usuari();
		if(array_key_exists('post', $info))
		{
			foreach($info as $clau => $valor)
				$this->informacio[$clau] = $valor;

			$ok = $this->validar_codi_validacio();
			if($ok) $fail = $this->validar_dades_usuari();
			else $fail = true;

/*                print "FAIL = $fail<br>";
                print "<pre>";
                print_r($this->informacio);
                print "</pre>";*/

			return $fail;
		}
		else return true;
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
		$id_handset = $this->informacio['my_handset'];

                $sql = "insert into user(id_user, login, password, name, email, web, gender, relationship, birthday, id_handset, id_country, id_state, id_city, data_insert)
		values (null, '$login', '$passwd', '$name', '$email', '$web', '$gender', '$relationship', '$birthday', $id_handset, $id_country, $id_state, $id_city, NOW())";
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
