<?php
	session_start();
	require_once("utils.inc");
	require_once("db_utils.inc");
	
	$tunnus = parsePost("tunnus");
	$ss = parsePost("ss");
	
	$result = fetchLogin($tunnus, $ss);	
	if($result == true){
		$_SESSION["login"] = 1;		
		$_SESSION["tunnus"] = $result["Tunnus"];
		$_SESSION["kid"] = $result["kayttaja_id"];
		$_SESSION["asty"] = $result["asty"];
		$_SESSION["nimi"] = $result["nimi"];
		header("Location: asiakas.php");
		//print_r($result); print_r($_SESSION["tunnus"]); print_r($_SESSION["kid"]);
		
		exit();
		
	}
	else
	{
		// Tunnus && ss oikein ohjataan pääsivulle
		header("Location: login.php?virhe=1");
		exit();
	}
	// Tarkista, löytyykö tietokannasta riviä kayttaja-taulusta, jossa tunnus ja ss ovat samat kuin parametrina tulleet

?>
