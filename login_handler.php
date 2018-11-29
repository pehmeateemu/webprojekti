<?php
	session_start();
	require_once("utils.inc");
	require_once("db_utils.inc");
	
	$tunnus = parsePost("tunnus");
	$ss = parsePost("ss");
	
	$result = fetchLogin($tunnus, $ss);

	
	// Tarkista, löytyykö tietokannasta riviä kayttaja-taulusta, jossa tunnus ja ss ovat samat kuin parametrina tulleet
	if ( $tunnus == "kalle" && $ss == "kalle" )
	{
		// Tunnus && ss oikein -> ohjataan pääsivulle ja talletetaan tieto sessioon jotta käyttäjä on tehnyt loginin oikein
		$_SESSION["login"] = 1;
		
		header("Location: asiakas.php");
		exit();
	}
	else if($result == true && $tunnus != "admin"){
		$_SESSION["login"] = 1;
		$_SESSION["ulevel"] = 0;
		$_SESSION["tunnus"] = $tunnus;
		header("Location: asiakas.php");
		exit();
	}
		else if($result == true && $tunnus == "admin"){
		$_SESSION["login"] = 1;
		$_SESSION["ulevel"] = 1;
		$_SESSION["tunnus"] = $tunnus;
		header("Location: asiakas.php");
		exit();
	}
	else
	{
		// Tunnus && ss oikein -> ohjataan pääsivulle
		header("Location: login.php?virhe=1");
		exit();
	}
?>