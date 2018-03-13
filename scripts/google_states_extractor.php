<?php
define (PAUSA_ENTRE_CIUTATS, 1); //Això es fa per suavitzar el hammering contra fotolog
define (GOOGLE_MAPS_API_KEY, 'ABQIAAAAqYbrtgGE7lHwAXafOWCjUhTzF6xK-9qvyjShZkPiI0ri_mRE3RT6PBl7cD3q7fbQurKNAoeZbB5HBw');
define (COUNTRY_ACCURACY, 1);
require_once("conexio_bd.php");
require_once "JSON.phpi";

$con_bd = new conexio_bd();
$continents = array('EU'=>'Europe', 'AS'=>'Asia', 'SA'=>'South America', 'NA'=>'North America', 'OC'=>'Oceania', 'AF'=>'Africa');

processar_google_continents($continents, $con_bd);

function processar_google_continents($continents, $con_bd)
{
	foreach($continents as $id_fotolog_continent => $nom_continent)
	{
		$paisos = obtenir_paisos_continent($id_fotolog_continent, $con_bd);
		foreach($paisos as $pais)
		{
			$id_pais = $pais['id'];
			$nom_pais = $pais['nom'];
			$id_google_country = $pais['id_google'];
			$estats = obtenir_estats_pais($id_pais, $con_bd);
			foreach($estats as $estat)
			{
				$id_estat = $estat['id'];
				$nom_estat = $estat['nom'];
				processar_google_state($id_google_country, $id_pais, $nom_pais, $id_estat, $nom_estat, $con_bd);
			}
			//processar_google_country($id_pais, $nom_pais, $con_bd);
		}
	}
}

function processar_google_state($id_google_country, $id_pais, $nom_pais, $id_estat, $nom_estat, $con_bd)
{
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace=false;
	$url = urlencode("http://maps.google.com/maps/geo?q=$nom_pais, $nom_estat&output=json&key=".GOOGLE_MAPS_API_KEY);

	$doc->loadHTMLFile($url);

	$res = $doc->getElementsByTagName("p");
	foreach($res as $tag) { $resposta = $tag->nodeValue; break; }

	//print "RESPOSTA: ($resposta)";

	$json = new Services_JSON();
	$input = $json->decode($resposta);

	print "(<pre>";
	print_r($input);
	print "</pre>)";

	$info_pais = extreure_info_estat($input);

	$sql = "UPDATE	country
		SET	id_google_country='".($info_pais['id_google_country'])."',
			nom_google='".($info_pais['nom_google'])."',
			coordinates='".($info_pais['coordinates'])."'
		WHERE	id_country = $id_pais
		LIMIT 1";
	//print "SQL: $sql<br/>\n";
	$res = $con_bd->sql_query($sql);

	print "Pais: ($id_pais) $nom_pais<br/><pre>";
	print_r($info_pais);
	print "</pre>\n";
}

function extreure_info_estat($input)
{
	$info_pais = array('coordinates'=>'', 'nom_google'=>'', 'id_google_country'=>'');

	$status = $input->Status;
	$codi = $status->code;

	if($codi!=200) return $info_pais;

	$places = $input->Placemark;
	foreach($places as $place)
	{
		$address_details = $place->AddressDetails;
		//print "ACCURACY: ".($address_details->Accuracy);

		if(($accuracy = $address_details->Accuracy)==COUNTRY_ACCURACY)
		{
			$address = $place->address;
			$point = $place->Point;
			$coordinates = implode(",", $point->coordinates);

			$address_details = $place->AddressDetails;
			$country = $address_details->Country;
			$country_name_code = $country->CountryNameCode;

			$id_google_country = $country_name_code;

			$info_pais['coordinates'] = $coordinates;
			$info_pais['nom_google'] = $address;
			$info_pais['id_google_country'] = $id_google_country;
		}
	}
	return $info_pais;
}

function processar_google_country($id_pais, $nom_pais, $con_bd)
{
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace=false;
	$url = urlencode("http://maps.google.com/maps/geo?q=$nom_pais&output=json&key=".GOOGLE_MAPS_API_KEY);

	$doc->loadHTMLFile($url);

	$res = $doc->getElementsByTagName("p");
	foreach($res as $tag) { $resposta = $tag->nodeValue; break; }

	//print "RESPOSTA: ($resposta)";

	$json = new Services_JSON();
	$input = $json->decode($resposta);

/*	print "(<pre>";
	print_r($input);
	print "</pre>)";*/

	$info_pais = extreure_info_pais($input);

	$sql = "UPDATE	country
		SET	id_google_country='".($info_pais['id_google_country'])."',
			nom_google='".($info_pais['nom_google'])."',
			coordinates='".($info_pais['coordinates'])."'
		WHERE	id_country = $id_pais
		LIMIT 1";
	//print "SQL: $sql<br/>\n";
	$res = $con_bd->sql_query($sql);

	print "Pais: ($id_pais) $nom_pais<br/><pre>";
	print_r($info_pais);
	print "</pre>\n";
}

function extreure_info_pais($input)
{
	$info_pais = array('coordinates'=>'', 'nom_google'=>'', 'id_google_country'=>'');

	$status = $input->Status;
	$codi = $status->code;

	if($codi!=200) return $info_pais;

	$places = $input->Placemark;
	foreach($places as $place)
	{
		$address_details = $place->AddressDetails;
		//print "ACCURACY: ".($address_details->Accuracy);

		if(($accuracy = $address_details->Accuracy)==COUNTRY_ACCURACY)
		{
			$address = $place->address;
			$point = $place->Point;
			$coordinates = implode(",", $point->coordinates);

			$address_details = $place->AddressDetails;
			$country = $address_details->Country;
			$country_name_code = $country->CountryNameCode;

			$id_google_country = $country_name_code;

			$info_pais['coordinates'] = $coordinates;
			$info_pais['nom_google'] = $address;
			$info_pais['id_google_country'] = $id_google_country;
		}
	}
	return $info_pais;
}

function obtenir_estats_pais($id_pais, $con_bd)
{
	$sql = "SELECT	id_state,
			nom
		FROM	state
		WHERE	id_country = $id_pais";
	$res = $con_bd->sql_query($sql);
	$paisos = array();
	while($row = $res->fetchRow())
	{
		array_push($paisos, array('id'=>$row[0], 'nom'=>$row[1]));
	}
	return $paisos;
}

function obtenir_paisos_continent($id_fotolog_continent, $con_bd)
{
	$sql = "SELECT	cou.id_country,
			cou.nom
			cou.id_google_country
		FROM	continent con,
			country cou
		WHERE	con.id_fotolog_continent='$id_fotolog_continent'
			AND cou.id_continent=con.id_continent";
	$res = $con_bd->sql_query($sql);
	$paisos = array();
	while($row = $res->fetchRow())
	{
		array_push($paisos, array('id'=>$row[0], 'nom'=>$row[1], 'id_google'=>$row[2]);
	}
	return $paisos;
}

die();
/*************************************************************************************************************************************/
print "<br/>Reiniciant taules... \n";
//reinicialitzar_taules(array('continent', 'country', 'state', 'city'), $con_bd);
print "OK<br/><br/>Capturant regions del mon...<br/>\n";
//processar_continents($continents, $con_bd);
print "<br/>**** Proces finalitzat ****<br/>\n";
//mostrar_report($con_bd);

function mostrar_report($con_bd)
{
	$taules = array('continent', 'country', 'state', 'city');
	foreach($taules as $taula)
	{
		$sql = "select count(id_$taula) from $taula";
		$res = $con_bd->sql_query($sql);
		$row = $res->fetchRow();
		print "$taula: ".($row[0])." registres.<br/>\n";
	}
}

function reinicialitzar_taules($taules, $con_bd)
{
	foreach($taules as $taula)
	{
		//S'eliminen tots els registres de les taules
		$sql = "DELETE FROM `$taula` WHERE `id_$taula` >= 0 LIMIT 99999999999;";
		$res = $con_bd->sql_query($sql);
		//Cal posar l'autoincrement a 0
		$sql = "ALTER TABLE `$taula` PACK_KEYS =1 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT = 0";
		$res = $con_bd->sql_query($sql);
	}
}

function get_max_id($taula, $con_bd)
{
	$sql = "select max(id_$taula) from $taula";
	$res = $con_bd->sql_query($sql);
	$row = $res->fetchRow();
	return $row[0];
}

function processar_continents($continents, $con_bd)
{
	foreach($continents as $id_continent => $nom_continent)
	{
		$sql = "INSERT INTO `continent` ( `id_continent` , `id_fotolog_continent` , `nom` , `data_insert` ) VALUES ('', '$id_continent', '$nom_continent', NOW( ));";
		$res = $con_bd->sql_query($sql);

		$doc = new DOMDocument();
		$doc->preserveWhiteSpace=false;
		$doc->loadHTMLFile("http://geo.fotolog.com/directory?continent=$id_continent");

		print "Continent: $id_continent ";
		$table = $doc->getElementById('listingTable');
		if($table!=NULL)
		{
			if($table!=NULL)
			{
				$paisos = $table->getElementsByTagName("a");
				print "(".(count($paisos))." paisos)<br/>\n";

				foreach($paisos as $pais)
				{
					$nom_pais = utf8_decode($pais->nodeValue);
					$url_pais = $pais->getAttribute("href");
		
					$url_parsejada = parse_url($url_pais);
					//print_r($info_url);
					parse_str($url_parsejada['query'], $params);
		
					$id_pais = $params['country'];
					processar_country($nom_continent, $id_continent, $nom_pais, $id_pais, $con_bd);
				}
			}else print "<br/>\n";
		}
	}
}

function processar_country($nom_continent, $id_continent, $nom_pais, $id_pais, $con_bd)
{
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace=false;
	$doc->loadHTMLFile("http://geo.fotolog.com/directory?country=$id_pais");

	$id_max_continent = get_max_id('continent', $con_bd);
	$sql = "INSERT INTO `country` ( `id_country` , `id_continent`, `id_fotolog_country` , `nom` , `data_insert` ) VALUES ('', '$id_max_continent', '$id_pais', '$nom_pais', NOW( ));";
	$res = $con_bd->sql_query($sql);

	print "Country: $id_pais ";
	$table = $doc->getElementById('listingTable');
	if($table!=NULL)
	{
		$estats = $table->getElementsByTagName("a");
		print "(".(count($estats))." estats)<br/>\n";

		foreach($estats as $estat)
		{
			$nom_estat = utf8_decode($estat->nodeValue);
			$url_estat = $estat->getAttribute("href");

			$url_parsejada = parse_url($url_estat);
			parse_str($url_parsejada['query'], $params);

			$id_estat = $params['state'];
			processar_state($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat, $con_bd);
		}
	}else print "<br/>\n";
}

function processar_state($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat, $con_bd)
{
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace=false;
	$doc->loadHTMLFile("http://geo.fotolog.com/directory?country=$id_pais&state=$id_estat");

	$id_max_country = get_max_id('country', $con_bd);
	$sql = "INSERT INTO `state` ( `id_state` , `id_country`, `id_fotolog_state` , `nom` , `data_insert` ) VALUES ('', '$id_max_country', '$id_estat', '$nom_estat', NOW( ));";
	$res = $con_bd->sql_query($sql);

	print "Estat: $id_estat ";
	$table = $doc->getElementById('listingTable');
	if($table!=NULL)
	{
		$ciutats = $table->getElementsByTagName("a");
		print "(".(count($ciutats))." ciutats)<br/>\n";

		foreach($ciutats as $ciutat)
		{
			$nom_ciutat = utf8_decode($ciutat->nodeValue);
			$url_ciutat = $ciutat->getAttribute("href");

			$url_parsejada = parse_url($url_ciutat);
			parse_str($url_parsejada['query'], $params);

			$id_ciutat = $params['city'];
			processar_city($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat, $nom_ciutat, $id_ciutat, $con_bd);
		}
	}else print "<br/>\n";
}

function processar_city($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat, $nom_ciutat, $id_ciutat, $con_bd)
{
	$id_max_state = get_max_id('state', $con_bd);
	$sql = "INSERT INTO `city` ( `id_city` , `id_state`, `id_fotolog_city` , `nom` , `data_insert` ) VALUES ('', '$id_max_state', '$id_ciutat', '$nom_ciutat', NOW( ));";
	$res = $con_bd->sql_query($sql);
	sleep(PAUSA_ENTRE_CIUTATS);
	//print "CONTINENT: $id_continent | COUNTRY: $nom_pais | ESTAT: $nom_estat | CIUTAT: $nom_ciutat<br/>\n";
	//die();
}

?>