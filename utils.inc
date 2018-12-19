<?php

/*
	Tässä tiedostossa on sekailaisia funktioita, joita voidaan tarvita php-tiedostoissa.
*/

/*
	Palauttaa parametrina tulleen datan takaisin UTF8 muodossa.
*/
function utf8ize($d) {
	if (is_array($d)) {
		foreach ($d as $k => $v) {
			$d[$k] = utf8ize($v);
		}
	} else if (is_string ($d)) {
		return utf8_encode($d);
	}
	return $d;
}

/*
	Palauttaa $_GET-taulukosta muuttujan arvon, jos parametrina tullut muuttuja on taulukossa, muuten null;
*/
function parseGet($name)
{
	if ( isset($_GET[$name]) )
		return $_GET[$name];
	else
		return null;
}

/*
	Palauttaa $_POST-taulukosta muuttujan arvon, jos parametrina tullut muuttuja on taulukossa, muuten null;
*/
function parsePost($name)
{
	if ( isset($_POST[$name]) )
		return $_POST[$name];
	else
		return null;
}


function writeLog($data, $source)
{
	// error_log on PHP:n oma funktioita
	// itse loki löytyy 
	error_log("Error in $source: $data");
}

function consoleDebug($data) {

		if ( is_array( $data ) )
			$output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
		else
			$output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

		echo $output;
	}

?>
