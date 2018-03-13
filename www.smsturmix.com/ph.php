<?php
	require('../DB/conexio_bd.php');

	if (!empty($_POST["ih"])) $id_handset = $_POST["ih"];
	elseif (!empty($_GET["ih"])) $id_handset = $_GET["ih"];
	else die();

	if($id_handset)
	{
		$con_bd = new conexio_bd();

		$sql = "SELECT wt.thumbnail as thumbnail
			FROM wurfl_thumbnail wt, handset h
			WHERE wt.id_wurfl_device = h.id_wurfl_device
				and h.id_handset = $id_handset";

		$res = $con_bd->sql_query($sql);

		ob_start();
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Content-Type: image/gif");

		if($res->numRows())
		{
			//Si s'entra aquí dins aleshores vol dir que existeix preview d'aquest handset a la BD...
			$row = $res->fetchRow();
			print $row['thumbnail'];
		}
		else
		{
			//Si s'entra aquí a dins aleshores vol dir que no existeix preview d'aquest handset a la BD
			//i cal mostrar una imatge genèrica que està guardada en base64 en una variable...
			$gif_imatge_generica = "R0lGODlhSABIALMAAP////z8/PX09O/v7+3t7ezr6+rq6ejo6Obl5ePi4uHh4d7e3dfX19XV1c/OzsvLyiH5BAAHAP8ALAAAAABIAEgAAAT/EMhJq7046827/2AojmRpnmiqrmzrvnAsW8EwFHiu78UQzJ6AQUEsGo9Iww+YCSCQ0CgRsWRaDkWEYcvterdP4sFqERR7Np7adisKyJTB1MZ42O/4PMMWHsAnQwo9C3mFhQttCgZ/EliCAw52DpOUlZSSiWOMjj13VRgBd5mMAJwDnhuhdqObRJ12nxeqD6x/pqgas7Vwt7CpogVipL0PsTTAwq2PuE3ICpq2rqe+uc7QvNLMoNbD2dTNq8HP3cvf2+HJ0eXFv+jjyq/s1e7XZMTGFbri9Vb37bT7yMXDR0FfOmzrCE4w+E7dwH+77HmTBw7gQYmPIhna+MBBxH7S/whxNIQoIDwbCzSO7IjoIxNTbGLKnJnmIshHAw4kQIBmAIKdbAr8PFCzIcJBDZI26MFAaYMbTkvafCmtqVItThsYQJDVJRBTWRMYyGogQVeTDgdYTcozq9CzU79KM6uUAIEFSluuTeB1BswcMQGz4VKUH1WcNBMHjutX2gEGkFsmgMyA7wC8SYmiParWKQICWQlwddpXBlinYsnSVVo6xmmlqZ2WhWsUI1KlBjqzHpu3NQxTwRYsQGDDgPAFuX0Kf7TZtuLnhQXyIZK8QILraAJ9tsvYdEinTEkXiOr7xeuksXGvTlrexfkG6ZPOFt89RqDwV8eDH81a3CJGcihAHEoClLWkgFIKoHSVT0T4wYgZ0tzQUxoT3lCUAm+QI+AXHHYRRm1wOCHFiEZQQcpCgZAYhRIn5kOhGmso1OKMNNZo44045qjjjipEAAA7";
			print base64_decode($gif_imatge_generica);
		}

		ob_end_flush();
	}
?>
