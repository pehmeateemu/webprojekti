<?php
	session_start();
	require_once("utils.inc");
	require_once("db_utils.inc");
	
	$tunnus = parsePost("tunnus");
	$ss = parsePost("ss");
	
	$result = fetchLogin($tunnus, $ss);	
	if($result == true){
		$_SESSION["login"] = 1;		
		$_SESSION["tunnus"] = $tunnus;
		if ($tunnus == "admin") {
			$_SESSION["asty"] = 1;
		}
		else {
			$_SESSION["asty"] = 0;
		}
		header("Location: asiakas.php");
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
