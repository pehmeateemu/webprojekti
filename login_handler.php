<?php
	session_start();
	require_once("utils.inc");
	
	$tunnus = parsePost("tunnus");
	$ss = parsePost("ss");
	
	// Tarkista, löytyykö tietokannasta riviä kayttaja-taulusta, jossa tunnus ja ss ovat samat kuin parametrina tulleet
	if ( $tunnus == "kalle" && $ss == "kalle" )
	{
		// Tunnus && ss oikein -> ohjataan pääsivulle ja talletetaan tieto sessioon jotta käyttäjä on tehnyt loginin oikein
		$_SESSION["login"] = 1;
		
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