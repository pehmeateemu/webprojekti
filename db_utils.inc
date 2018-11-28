<?php
/*
	Tässä tiedostossa on funktioita, jotka käsittelevät Asiakas-taulua.
	Muiden taulujen funktiot kannattaa tehdä omiin tiedostoihinsa.
*/

require_once("db.inc");
require_once("utils.inc");

function fetchLogin($tunnus, $ss)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT * FROM kayttaja WHERE TUNNUS='$tunnus' AND SALASANA = '$ss'";
		
		$stmt = $conn->prepare($q);
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			$result[] = $rivi;
		}
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in fetchAsiakas: " . $e->getMessage());
	}
	return $result;
}

function deleteAsiakas($avain)
{
	global $conn;
	$result = "OK";
	try {

		$stmt = $conn->prepare("DELETE FROM kayttaja WHERE kayttaja_id = :avain"); 

		$key = $avain;
		$stmt->bindParam(":avain", $key);

		$stmt->execute();            
	}
	catch(PDOException $e) {
		$result = "ERROR " . $e->getMessage();
		error_log("Error in deleteAsiakas: " . $e->getMessage());
	}
	
	return $result;
}


function createAsiakas($asiakas)
{
	global $conn;
	$result = array("status" => "ok");
	try {

		$stmt = $conn->prepare("INSERT INTO kayttaja (Tunnus, Salasana, Nimi, Osoite, Postinro, Postitmp, Asty) 
								VALUES (:uname, :pw, :n, :o, :pn, :pt, :asty)");
		$stmt->bindParam(":uname", $uname);
		$stmt->bindParam(":pw", $pw);
		$stmt->bindParam(":n", $n);
		$stmt->bindParam(":o", $o);
		$stmt->bindParam(":pn", $pn);
		$stmt->bindParam(":pt", $pt);
		$stmt->bindParam(":asty", $asty);

		$uname = $asiakas["tunnus"];		
		$pw = $asiakas["salasana"];
		$n = $asiakas["nimi"];
		$o = $asiakas["osoite"];
		$pn = $asiakas["postinro"];
		$pt = $asiakas["postitmp"];
		$asty = $asiakas["asty"];

		$stmt->execute();           
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		// Kirjoittaa virheviestin PHP:n error lokiin (löytyy WAMP:n ikonin alta PHP -> PHP error log)
		error_log("Error in createAsiakas: " . $e->getMessage());
	}
	
	echo json_encode($result);
}

/*
	Funktio hakee datan Asiakas-taulusta. Parametrina tulee mahdolliset hakuehdot.
*/
function fetchAsiakasID($kayttaja_id)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT TUNNUS, SALASANA, NIMI, OSOITE, POSTINRO, POSTITMP, ASTY FROM kayttaja WHERE kayttaja_id = '$kayttaja_id'";	
	
		$stmt = $conn->prepare($q);
		$stmt->execute();
			

		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			if ($rivi["laite_id"] == $laite_id) {

			$result = array(
			$rivi["tunnus"],
			$rivi["salasana"],
			$rivi["nimi"],
			$rivi["osoite"],
			$rivi["postinro"], 
			$rivi["postitmp"], 
			$rivi["asty"]);
			}
			
		}
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in fetchAsiakasID: " . $e->getMessage());
	}
	return $result;

}function updateAsiakas($asiakas)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array("status" => "ok");
	
	try {
		$stmt = $conn->prepare("UPDATE kayttaja
	  		SET
				TUNNUS = :uname,
				SALASANA = :pw,
				NIMI = :n,
				OSOITE = :o,
				POSTINRO = :pn,
				POSTITMP = :pt,
				ASTY = 1
			WHERE
				KAYTTAJA_ID = :kid");	
		
		$stmt->bindParam(":kid", $kid);
		$stmt->bindParam(":uname", $uname);
		$stmt->bindParam(":pw", $pw);
		$stmt->bindParam(":n", $n);
		$stmt->bindParam(":o", $o);
		$stmt->bindParam(":pn", $pn);
		$stmt->bindParam(":pt", $pt);

		
		$kid = $asiakas["kayttaja_id"];	
		$uname = $asiakas["tunnus"];		
		$pw = $asiakas["salasana"];
		$n = $asiakas["nimi"];
		$o = $asiakas["osoite"];
		$pn = $asiakas["postinro"];
		$pt = $asiakas["postitmp"];

		$stmt->execute();

	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in updateAsiakas: " . $e->getMessage());
	}
	echo json_encode($result);

	return $result;

}
function fetchAsiakas($name, $address, $type_key)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT kayttaja_id, Nimi, Osoite, Postinro FROM kayttaja WHERE 1=1 ";
		if ( !empty($name) ) $q .= " AND Nimi like '%$name%'";
		if ( !empty($address) ) $q .= " AND Nimi like '%$address%'";
		
		$stmt = $conn->prepare($q);
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			$a = $rivi["kayttaja_id"];
			$n = $rivi["Nimi"];
			$o = $rivi["Osoite"];
			$p = $rivi["Postinro"];
			$result[] = $rivi;
		}
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in fetchAsiakas: " . $e->getMessage());
	}
	return $result;

}

function fetchAsiakastyyppi()
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT asty FROM kayttaja ";
		
		$stmt = $conn->prepare($q);
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			$result[] = $rivi;
		}
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in fetchAsiakastyyppi: " . $e->getMessage());
	}

	return $result;
}

function editLaite($laite_id)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT laite_id, Nimi, Merkki, Malli, Sarjanumero, Kategoria, Omistaja, Osoite, Postinro, Postitmp, Kuvaus, Tila FROM laite WHERE laite_id LIKE '%$laite_id%'";
		
		$stmt = $conn->prepare($q);
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			if ($rivi["laite_id"] == $laite_id) {

			$result[] = $rivi;
			}
			
		}

	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in fetchLaite: " . $e->getMessage());
	}
	echo json_encode($result);

}

function fetchLaite($laite_id, $nimi, $merkki, $malli, $sarjanumero, $kategoria, $omistaja, $osoite, $postinro, $postitmp, $kuvaus, $tila)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT laite_id, Nimi, Merkki, Malli, Sarjanumero, Kategoria, Omistaja, Osoite, Postinro, Postitmp, Kuvaus, Tila FROM laite WHERE 1=1 ";
		if( !empty($laite_id) ) $q .= "AND laite_id like '%$laite_id%'";
		if ( !empty($nimi) ) $q .= " AND Nimi like '%$nimi%'";
		if ( !empty($merkki) ) $q .= " AND Merkki like '%$merkki%'";
		if ( !empty($malli) ) $q .= " AND Malli like '%$malli%'";
		if ( !empty($sarjanumero) ) $q .= " AND Sarjanumero like '%$sarjanumero%'";
		if ( !empty($kategoria) ) $q .= " AND Kategoria like '%$kategoria%'";
		if ( !empty($omistaja) ) $q .= " AND Omistaja like '%$omistaja%'";
		if ( !empty($osoite) ) $q .= " AND Osoite like '%$osoite%'";
		if ( !empty($postinro) ) $q .= " AND Postinro like '%$postinro%'";
		if ( !empty($postitmp) ) $q .= " AND Postitmp like '%$postitmp%'";
		if ( !empty($kuvaus) ) $q .= " AND Kuvaus like '%$kuvaus%'";
		
		$stmt = $conn->prepare($q);
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			$result[] = $rivi;
		}
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in fetchLaite: " . $e->getMessage());
	}
	return $result;

}
function createlaite($laite)
{
	global $conn;
	$result = array("status" => "ok");
	try {

		$stmt = $conn->prepare("INSERT INTO laite (Tila, Nimi, Merkki, Malli, Sarjanumero, Kategoria, Omistaja, Osoite, Postinro, Postitmp, Kuvaus) 
								VALUES (:ti, :n, :me, :ma, :sn, :ka, :om, :o, :pn, :pt, :ku)");
		$stmt->bindParam(":ti", $ti);
		$stmt->bindParam(":n", $n);
		$stmt->bindParam(":me", $me);
		$stmt->bindParam(":ma", $ma);
		$stmt->bindParam(":sn", $sn);
		$stmt->bindParam(":ka", $ka);
		$stmt->bindParam(":om", $om);
		$stmt->bindParam(":o", $o);
		$stmt->bindParam(":pn", $pn);
		$stmt->bindParam(":pt", $pt);
		$stmt->bindParam(":ku", $ku);
		
		$ti = $laite["tila"];
		$n = $laite["nimi"];
		$me = $laite["merkki"];
		$ma = $laite["malli"];
		$sn = $laite["sarjanumero"];
		$ka = $laite["kategoria"];
		$om = $laite["omistaja"];
		$o = $laite["osoite"];
		$pn = $laite["postinro"];
		$pt = $laite["postitmp"];
		$ku = $laite["kuvaus"];

		$stmt->execute();           
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		// Kirjoittaa virheviestin PHP:n error lokiin (löytyy WAMP:n ikonin alta PHP -> PHP error log)
		error_log("Error in createLaite: " . $e->getMessage());
	}
	
	echo json_encode($result);
}

function updateLaite($laite)
{
	global $conn;
	$result = array("status" => "ok");
	try {

				$stmt = $conn->prepare("UPDATE laite
	  		SET
				Tila = 1,
				Nimi = :n, 
				Merkki = :me, 
				Malli = :ma, 
				Sarjanumero = :sn, 
				Kategoria = :ka, 
				Omistaja = :om, 
				Osoite = :o, 
				Postinro = :pn, 
				Postitmp = :pt, 
				Kuvaus = :ku 
			WHERE 
				laite_id = :id");	

		$stmt->bindParam(":id", $id);		
		//$stmt->bindParam(":ti", $ti);
		$stmt->bindParam(":n", $n);
		$stmt->bindParam(":me", $me);
		$stmt->bindParam(":ma", $ma);
		$stmt->bindParam(":sn", $sn);
		$stmt->bindParam(":ka", $ka);
		$stmt->bindParam(":om", $om);
		$stmt->bindParam(":o", $o);
		$stmt->bindParam(":pn", $pn);
		$stmt->bindParam(":pt", $pt);
		$stmt->bindParam(":ku", $ku);
		
		$id = $laite["laite_id"];
		//$ti = $laite["tila"];
		$n = $laite["nimi"];
		$me = $laite["merkki"];
		$ma = $laite["malli"];
		$sn = $laite["sarjanumero"];
		$ka = $laite["kategoria"];
		$om = $laite["omistaja"];
		$o = $laite["osoite"];
		$pn = $laite["postinro"];
		$pt = $laite["postitmp"];
		$ku = $laite["kuvaus"];

		$stmt->execute();          
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		// Kirjoittaa virheviestin PHP:n error lokiin (löytyy WAMP:n ikonin alta PHP -> PHP error log)
		error_log("Error in muokkaaLaite: " . $e->getMessage());
	}
	global $conn;
	$result = array("status" => "ok");
	

}

function poistaLaite($laite_id)
{
	global $conn;
	$result = "OK";
	try {

		$stmt = $conn->prepare("UPDATE laite SET Tila=5 WHERE laite_id = :avain"); 

		$poisto_id = $laite_id;
		$stmt->bindParam(":avain", $poisto_id);

		$stmt->execute();            
	}
	catch(PDOException $e) {
		$result = "ERROR " . $e->getMessage();
		error_log("Error in poistaLaite: " . $e->getMessage());
	}
	
	return $result;
}

?>