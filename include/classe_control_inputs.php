<?
require('constants_smsturmix.php');

class control_inputs
{
	var $valors_numerics = array();
	var $valors_alfanumerics = array();

        function control_inputs()
        {
		$this->valors_numerics = array('imh','ca','ih','p','ca','sx','sy','ex','ey','1','2','3','4','5','6','7','8','9');
		$this->valors_alfanumerics = array('s','op','id','a','public','description','name','email');
		$this->validate_numeric_inputs();
		$this->prevent_sql_injection();
        }

	function validate_numeric_inputs()
	{
		$inputs = array($_GET, $_POST);
		foreach($inputs as $input)
			foreach($input as $clau => $valor)
				if(in_array($clau, $this->valors_numerics))
					if((!is_numeric($valor))&&($valor!='')) die("$clau = $valor ?"); //Si no es un valor numeric doncs adeu siau!
	}

	function prevent_sql_injection()
	{
		foreach($_GET as $clau => $valor)
			if(in_array($clau, $this->valors_alfanumerics))
				$_GET[$clau] = mysql_real_escape_string($valor);

                foreach($_POST as $clau => $valor)
                        if(in_array($clau, $this->valors_alfanumerics))
                                $_POST[$clau] = mysql_real_escape_string($valor);
	}
}

$control = new control_inputs();
?>
