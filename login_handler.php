<?php
	session_start();
	require_once("utils.inc");
	require_once("db_utils.inc");
	
	if ( isset($_GET["login"]))
	{
		$l["tunnus"] = parseGet("tunnus");
		$l["ss"] = parseGet("ss");
		$result = fetchLogin($l);
		echo json_encode($result);

	};
	
	// Tarkista, löytyykö tietokannasta riviä kayttaja-taulusta, jossa tunnus ja ss ovat samat kuin parametrina tulleet

?>