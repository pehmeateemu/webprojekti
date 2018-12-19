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
		$stmt = $conn->prepare("SELECT Tunnus, kayttaja_id, Nimi, asty FROM kayttaja WHERE Tunnus = '$tunnus' AND Salasana = '$ss'");
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			$result = $rivi;
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
	
	echo json_encode($result);
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
function fetchAsiakasID($tunnus)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT kayttaja_id, Nimi, Osoite, Postinro, Postitmp, Tunnus, Salasana, asty FROM kayttaja WHERE tunnus LIKE '%$tunnus%'";
		
		$stmt = $conn->prepare($q);
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			if ($rivi["Tunnus"] == $tunnus) {

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
function updateAsiakas($asiakas)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array("status" => "ok");
	
	try {
		$stmt = $conn->prepare("UPDATE kayttaja
	  		SET
				tunnus = :uname,
				salasana = :pw,
				nimi = :n,
				osoite = :o,
				postinro = :pn,
				postitmp = :pt,
				asty = :ast 
			WHERE
				kayttaja_id = :kid");	
		
		$stmt->bindParam(":kid", $kid);
		$stmt->bindParam(":uname", $uname);
		$stmt->bindParam(":pw", $pw);
		$stmt->bindParam(":n", $n);
		$stmt->bindParam(":o", $o);
		$stmt->bindParam(":pn", $pn);
		$stmt->bindParam(":pt", $pt);
		$stmt->bindParam(":ast", $ast);

		
		$kid = $asiakas["kayttaja_id"];	
		$uname = $asiakas["tunnus"];		
		$pw = $asiakas["salasana"];
		$n = $asiakas["nimi"];
		$o = $asiakas["osoite"];
		$pn = $asiakas["postinro"];
		$pt = $asiakas["postitmp"];
		$ast = $asiakas["asty"];

		$stmt->execute();

	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in updateAsiakas: " . $e->getMessage());
	}
	echo json_encode($result);

	return $result;

}
function fetchAsiakas($haku)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	$n = $haku["nimi"];
	$os = $haku["osoite"];
	$tn = $haku["kayttaja_id"];

	try {
		$q = "SELECT kayttaja_id, Nimi, Osoite, Postinro, Postitmp, Tunnus FROM kayttaja WHERE 1=1 ";
		if ( !empty($n) ) $q .= " AND Nimi like '%$n%'";
		//if ($_SESSION["ulevel"] == 0) $q .= " AND Tunnus like '%$tn%'";
		
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

function fetchLaite($hlaite)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	$n = $hlaite["nimi"];
	$me = $hlaite["merkki"];
	$ma = $hlaite["malli"];
	$sn = $hlaite["sarjanumero"];
	$ka = $hlaite["kategoria"];
	$om = $hlaite["omistaja"];
	$os = $hlaite["osoite"];
	$pn = $hlaite["postinro"];
	$pt = $hlaite["postitmp"];
	$ku = $hlaite["kuvaus"];
	try {
		$q = "SELECT laite_id, Nimi, Merkki, Malli, Sarjanumero, Kategoria, Omistaja, Osoite, Postinro, Postitmp, Kuvaus, Tila FROM laite WHERE 1=1 ";
		if ( !empty($n) ) $q .= " AND Nimi like '%$n%'";
		if ( !empty($me) ) $q .= " AND Merkki like '%$me%'";
		if ( !empty($ma) ) $q .= " AND Malli like '%$ma%'";
		if ( !empty($sn) ) $q .= " AND Sarjanumero like '%$sn%'";
		if ( !empty($ka) ) $q .= " AND Kategoria like '%$ka%'";
		if ( !empty($om) ) $q .= " AND Omistaja like '%$om%'";
		if ( !empty($os) ) $q .= " AND Osoite like '%$os%'";
		if ( !empty($pn) ) $q .= " AND Postinro like '%$pn%'";
		if ( !empty($pt) ) $q .= " AND Postitmp like '%$pt%'";
		if ( !empty($ku) ) $q .= " AND Kuvaus like '%$ku%'";
		
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

function fetchVaraus($laite_id)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	try {
		$stmt = $conn->prepare(
			"SELECT 
				laite.laite_id, 
				laite.merkki, 
				laite.malli, 
				varaus.varaus_id, 
				kayttaja.nimi, 
				varaus.kayttaja_id, 
				varaus.aloituspvm, 
				varaus.lopetuspvm,
				varaus.varaus_tila
			FROM varaus 
			JOIN laite ON varaus.laite_id = laite.laite_id 
			JOIN kayttaja ON varaus.kayttaja_id = kayttaja.kayttaja_id 
			WHERE varaus.laite_id LIKE '%$laite_id%'");
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			$result[] = $rivi;
		}
	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in fetchVaraus: " . $e->getMessage());
	}
		return $result;
}

function checkVaraus($lid, $apm, $lpm) {
		
		global $conn;
		try {
		$stmt = $conn->prepare("SELECT varaus_id FROM varaus WHERE laite_id = :lid AND aloituspvm <= :lpm AND lopetuspvm >= :apm");

		$stmt->bindParam(":lid", $lid);
		$stmt->bindParam(":apm", $apm);
		$stmt->bindParam(":lpm", $lpm);

		$stmt->execute();
		$laskuri = 0;
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			$laskuri++;
		}
		if ($laskuri > 0) {
			return true;
		}
		else {
			return false;
		}
		}
		catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in checkVaraus: " . $e->getMessage());
		}
}

function lisaaVaraus($varaus)
{
	global $conn;
	$result = array("status" => "ok");

	$apm = date("Y-m-d", strtotime($varaus["aloituspvm"]));
	$lpm = date("Y-m-d", strtotime($varaus["lopetuspvm"]));

	//if (checkVaraus($varaus["laite_id"], $apm, $lpm)){
	try {
		$stmt = $conn->prepare("INSERT INTO varaus (varaus_tila, laite_id, kayttaja_id, aloituspvm, lopetuspvm) 
								VALUES (:ti, :lid, :kid, :apm, :lpm)");
		$stmt->bindParam(":ti", $ti);
		$stmt->bindParam(":lid", $lid);
		$stmt->bindParam(":kid", $kid);
		$stmt->bindParam(":apm", $apm);
		$stmt->bindParam(":lpm", $lpm);
		
		$ti = $varaus["tila"];
		$lid = $varaus["laite_id"];
		$kid = $varaus["kayttaja_id"];
		$stmt->execute();   
		}
		
	
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		// Kirjoittaa virheviestin PHP:n error lokiin (löytyy WAMP:n ikonin alta PHP -> PHP error log)
		error_log("Error in lisaaVaraus: " . $e->getMessage());
	}
	
	echo json_encode($result);
	//}
}


function poistaVaraus($varaus_id)
{
	global $conn;
	$result = "OK";
	try {

		$stmt = $conn->prepare("UPDATE varaus SET varaus_tila = 5 WHERE varaus_id = :avain"); 

		$muutos_id = $varaus_id;
		$stmt->bindParam(":avain", $muutos_id);

		$stmt->execute();            
	}
	catch(PDOException $e) {
		$result = "ERROR " . $e->getMessage();
		error_log("Error in poistaLaite: " . $e->getMessage());
	}
	
	return $result;
}



function lainaaVaraus($varaus_id)
{
	global $conn;
	$result = "OK";
	try {

		$stmt = $conn->prepare("UPDATE varaus SET varaus_tila = 1 WHERE varaus_id = :avain"); 

		$muutos_id = $varaus_id;
		$stmt->bindParam(":avain", $muutos_id);

		$stmt->execute();            
	}
	catch(PDOException $e) {
		$result = "ERROR " . $e->getMessage();
		error_log("Error in lainaaVaraus: " . $e->getMessage());
	}
	
	return $result;
}

function palautaLainaus($varaus_id)
{
	global $conn;
	$result = "OK";
	try {

		$stmt = $conn->prepare("UPDATE varaus SET varaus_tila = 2 WHERE varaus_id = :avain"); 

		$muutos_id = $varaus_id;
		$stmt->bindParam(":avain", $muutos_id);

		$stmt->execute();            
	}
	catch(PDOException $e) {
		$result = "ERROR " . $e->getMessage();
		error_log("Error in palautaVaraus: " . $e->getMessage());
	}
	
	return $result;
}

function updateVaraus($varaus)
{
	global $conn;
	$result = array("status" => "ok");
	$apm = date("Y-m-d", strtotime($varaus["aloituspvm"]));
	$lpm = date("Y-m-d", strtotime($varaus["lopetuspvm"]));
	try {

				$stmt = $conn->prepare("UPDATE varaus
	  		SET
				varaus_Tila = :ti,
				aloituspvm = :apm,
				lopetuspvm = :lpm

			WHERE 
				varaus_id = :lid");	

		$stmt->bindParam(":ti", $ti);
		$stmt->bindParam(":lid", $lid);	
		$stmt->bindParam(":apm", $apm);
		$stmt->bindParam(":lpm", $lpm);
		
		$ti = $varaus["tila"];
		$lid = $varaus["varaus_id"];


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

function editVaraus($varaus_id)
{
	// $conn -muuttuja EI näy funktion sisällä jos sitä ei määritellä täällä global:ksi
	global $conn;
	$result = array();
	
	try {
		$q = "SELECT varaus_id, laite_id, kayttaja_id, aloituspvm, lopetuspvm, varaus_Tila FROM varaus WHERE varaus_id LIKE '%$varaus_id%'";
		
		$stmt = $conn->prepare($q);
		$stmt->execute();
			
		//käydään rivit läpi 
		while ($rivi = $stmt->fetch(PDO::FETCH_ASSOC)) { 
			if ($rivi["varaus_id"] == $varaus_id) {

			$result = $rivi;
			}
			
		}

	}
	catch(PDOException $e) {
		$result = array("status" => "not ok", "error" => $e->getMessage());
		error_log("Error in editVaraus: " . $e->getMessage());
	}
	echo json_encode($result);

}

?>