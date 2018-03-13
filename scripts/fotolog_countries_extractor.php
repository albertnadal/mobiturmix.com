<?php
$continents = array('EU'=>'Europe');//,'AS'=>'Asia','SA'=>'South America','NA'=>'North America','OC'=>'Oceania','AF'=>'Africa');

$info = array();


/*$countries = array('EU'=>'Europe','AS'=>'Asia','SA'=>'South America','NA'=>'North America','OC'=>'Oceania','AF'=>'Africa'););
$states = array();
$cities = array();*/

processar_continents($continents);

function processar_continents($continents)
{
	foreach($continents as $id_continent => $nom_continent)
	{
		//print "$id<br/>";

		$doc = new DOMDocument();
		$doc->preserveWhiteSpace=false;
		$doc->loadHTMLFile("http://geo.fotolog.com/directory?continent=$id_continent");

		$table = $doc->getElementById('listingTable');
		if($table!=NULL)
		{
			$paisos = $table->getElementsByTagName("a");
			if($table!=NULL)
			{
				foreach($paisos as $pais)
				{
					$nom_pais = $pais->nodeValue;
					$url_pais = $pais->getAttribute("href");
		
					$url_parsejada = parse_url($url_pais);
					//print_r($info_url);
					parse_str($url_parsejada['query'], $params);
		
					//print_r(parse_url($url));
		
					$id_pais = $params['country'];
		
					//print ($pais->nodeValue)."<br/>\n";
					//array_push($info[$id_continent], $pais->nodeValue);
					processar_country($nom_continent, $id_continent, $nom_pais, $id_pais);
				}
			}
		}
	}
}

function processar_country($nom_continent, $id_continent, $nom_pais, $id_pais)
{
	//print "Nom: $nom_pais | Id: $id_pais<br/>\n";

	$doc = new DOMDocument();
	$doc->preserveWhiteSpace=false;
	$doc->loadHTMLFile("http://geo.fotolog.com/directory?country=$id_pais");

	$table = $doc->getElementById('listingTable');
	if($table!=NULL)
	{
		$estats = $table->getElementsByTagName("a");

		foreach($estats as $estat)
		{
			$nom_estat = $estat->nodeValue;
			$url_estat = $estat->getAttribute("href");

			$url_parsejada = parse_url($url_estat);
			//print_r($info_url);
			parse_str($url_parsejada['query'], $params);
			//print_r(parse_url($url));

			$id_estat = $params['state'];

			//print " -> ".($estat->nodeValue)." | Id:".($id_estat)."<br/>\n";
			//array_push($info[$id_continent], $pais->nodeValue);
			processar_state($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat);
		}
	}
}

function processar_state($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat)
{
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace=false;
	$doc->loadHTMLFile("http://geo.fotolog.com/directory?country=$id_pais&state=$id_estat");

	$table = $doc->getElementById('listingTable');
	if($table!=NULL)
	{
		$estats = $table->getElementsByTagName("a");

		foreach($ciutats as $ciutat)
		{
			$nom_ciutat = $estat->nodeValue;
			$url_ciutat = $estat->getAttribute("href");

			$url_parsejada = parse_url($url_ciutat);
			//print_r($info_url);
			parse_str($url_parsejada['query'], $params);
			//print_r(parse_url($url));

			$id_ciutat = $params['city'];

			//print " - - -> ".($ciutat->nodeValue)." | Id:".($id_ciutat)."<br/>\n";
			//array_push($info[$id_continent], $pais->nodeValue);
			processar_city($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat, $nom_ciutat, $id_ciutat);
		}
	}
}

function processar_city($nom_continent, $id_continent, $nom_pais, $id_pais, $nom_estat, $id_estat, $nom_ciutat, $id_ciutat)
{
	print "CONTINENT: $id_continent | COUNTRY: $nom_pais | ESTAT: $nom_estat | CIUTAT: $nom_ciutat<br/>\n";
	die();
}


die();














































if(!($table->hasChildNodes())) die('Llista de paisos no trobada');

$arbre = array();

print "INICIANT1<br/>";
//$arbre = xml2array($table);
print "<pre>";
//print_r($arbre);
print "</pre>";

print "INICIANT2<br/>";
$string = $doc->saveHTML();

/*$xml = simplexml_load_string($string);
print "asasass<pre>";
print_r($xml);
print "</pre>asasas";*/


/*function xmlToArray($n)
{
    $return=array();

    foreach($n->childNodes as $nc)
    {
        if( $nc->hasChildNodes() )
        {
            if( $n->firstChild->nodeName== $n->lastChild->nodeName&&$n->childNodes->length>1)
            {
		$item = $n->firstChild;
		print "INFO: (".($nc->nodeName).")<br>";
		print "ITEM: ($item)<br>";
		$return[$nc->nodeName][] = xmlToArray($item);
            }
            else
            {
		$return[$nc->nodeName] = xmlToArray($nc);
            }
       }
       else
       {
           $return=$nc->nodeValue;
       }
    }
    return $return;
}*/


//processar_subnodes($table->firstChild, 0);


/*print "INICIANT1";

$first = $table->firstChild;
print "NAME: ".($first->nodeName)."<br>";
$last  = $table->lastChild;

print "INICIANT2";
while(!($first->isSameNode($last)))
{
	print "VOLTA - ";
	print "NAME: ".($first->nodeName)."<br>";
	echo $first->getAttribute('href').'<br>';
	$first = $first->nextSibling;
	print "NAME: ".($first->nodeName)."<br>";
}

die();*/

/*
function processar_subnodes($node, $l)
{
	$parent = $node->parentNode;
	$last = $parent->lastChild;
	print "<br/>NIVELL $l<br/>";
	print "NODE NAME: (".($node->nodeName).")<br>";
	print "NODE VALUE: (".($node->nodeValue).")<br>";
	print "LAST NAME: (".($last->nodeValue).")<br>";

	$nodes_nivell = array();

	if(!($node->hasChildNodes()) || ($node->nodeName == '#text')) { print "No te nodes<br/>"; return; }
	else
	{
		print "RECOLECTANT NODES DEL NIVELL<br/>";
		$fi = false;



		//print "Deso: (".($node->nodeName).")(".($node->nodeValue).")<br>";
		//array_push($nodes_nivell, $parent->firstChild);
		while(!$fi)
		{
			print "Deso: (".($node->nodeName).")(".($node->nodeValue).")<br>";
			$node = $node->nextSibling;
			array_push($nodes_nivell, $node);
			if($node->isSameNode($last)) { print "FI=TRUE<br>"; $fi = true; }
		}
		$nodes_nivell = array_reverse($nodes_nivell);

		print "INICIANT BUCLE<br/>";
		$fi = false;
		while(!$fi)
		{
			$node = array_pop($nodes_nivell);
			print "NAME:  (".($node->nodeName).")<br>";
			print "VALUE: (".($node->nodeValue).")<br>";
			print "TYPE: (".($node->nodeType).")<br>";
			//$fill = $node->firstChild;
			if($node->nodeType != 3) processar_subnodes($node->firstChild, ($l + 1));
			else print "Es un node de text<br/>";

			//if($node->nodeValue=='') print "LINK: ".($node->getAttribute('href'));

			if($node->isSameNode($last)) { print "FI=TRUE<br>"; $fi = true; }
		}
	}
	print "ACABAT NIVELL $l<br/>";
}*/

/*
$links = $doc->getElementsByTagName('a');



foreach ($links as $link)
{
	echo $link->getAttribute('href').'<br>';
}
*/

print "FI";

/*print "<pre>";
print $table->innerHTML;
print "</pre>";*/
//echo $doc->saveHTML();
//echo $doc->saveHTML();
?>
