<?php
/*
	Tässä tiedostossa on funktioita, jotka käsittelevät Asiakas-taulua.
	Muiden taulujen funktiot kannattaa tehdä omiin tiedostoihinsa.
*/

require_once("db.inc");
require_once("utils.inc");

function deleteAsiakas($avain)
{
	global $conn;
	$result = "OK";
	try {

		$stmt = $conn->prepare("DELETE FROM Asiakas WHERE Avain = :avain"); 

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

?>