<?php
require_once("db_utils.inc");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
require_once("login_utils.inc");
session_start();

	if ( isset($_POST["lisaa"]))
	{
	
		$a["tunnus"] = parsePost("tunnus");
		$a["salasana"] = parsePost("salasana");
		$a["nimi"] = parsePost("nimi");
		$a["osoite"] = parsePost("osoite");
		$a["postinro"] = parsePost("postinro");
		$a["postitmp"] = parsePost("postitmp");
		$a["asty"] = parsePost("asty");
		
		$result = createAsiakas($a);
		echo $result;
	}

	if ( isset($_GET["poista"]))
	{
		$avain = parseGet("poista");
		$result = deleteAsiakas($avain);
		echo $result;
	}

	if ( isset($_GET["muokkaa"]) )
    {
		$tunnus = parseGet("muokkaa");		
		$result = fetchAsiakasID($tunnus);
		echo($result);

	}


	if ( isset($_GET["haeVaraus"]))
	{
		$id = $_SESSION["kid"];
		$data = fetchUserVaraus($id);
		echo "<table id=\"varaukset\">";
		echo "<tr><th>VarausID</th><th>LaiteID</th><th>Merkki</th><th>Malli</th><th>Varaaja</th><th>Aloituspvm</th><th>Lopetuspvm</th><th>Tila</th></tr>";
		foreach($data as $row)
		{
			if ($row["varaus_tila"] < 5){
			$id = $row["varaus_id"];
			$kid = $row["kayttaja_id"];
			echo "<tr>";
			echo "<td>". $row["varaus_id"]. "</td>";
			echo "<td>". $row["laite_id"]. "</td>";
			echo "<td>". $row["merkki"]. "</td>";
			echo "<td>". $row["malli"]. "</td>";
			echo "<td>". $row["nimi"]. "</td>";
			echo "<td>". $row["aloituspvm"]. "</td>";
			echo "<td>". $row["lopetuspvm"]. "</td>";
			//tulostellaan tilan mukaiset tekstit ja napit

			if ($row["varaus_tila"] == 0) {echo "<td> Varaus </td> <td> <button class=\"lainaaButton\" onclick=\"lainaaVaraus($id)\">Lainaa </button></td> <td> <button class=\"muokkaaButton\" onclick=\"muokkaaVaraus($id)\">Muokkaa</button></td>";}
			if ($row["varaus_tila"] == 1) {echo "<td> Lainaus </td>"; if ($_SESSION["asty"] == 0) { echo  "<td> <button class=\"palautaButton\" onclick=\"palautaLainaus($id)\">Palauta </button></td> <td> <button class=\"muokkaaButton\" onclick=\"muokkaaVaraus($id)\">Muokkaa</button></td>";}}
			if ($row["varaus_tila"] == 2) {echo '<td> Palautettu </td>';}
			if ($row["varaus_tila"] == 3) {echo "<td> Huolto </td>"; if ($_SESSION["asty"] == 0) { echo "<td> <button class=\"palautaButton\" onclick=\"palautaLainaus($id)\">Palauta </button></td><td> <button class=\"muokkaaButton\" onclick=\"muokkaaVaraus($id)\">Muokkaa</button></td>";}}
			if ($row["varaus_tila"] == 5) {echo '<td> Poistettu </td>';}
			//varaus voidaan poistaa jos siitä ei ole tehty lainausta tai huoltoa eikä sitä ole poistettu
			if ($_SESSION["asty"] == 0) {
			echo "<td> <button class=\"poistaButton\" onclick=\"poistaVaraus($id)\">Poista </button> </td>";
			}
			elseif ($row["varaus_tila"] == 0 && $_SESSION["kid"] == $kid) {
			echo "<td> <button class=\"poistaButton\" onclick=\"poistaVaraus($id)\">Poista </button> </td>";
			}
			echo "</tr>";
			}
		}
		
		
		echo "</table>";
	}

	if ( isset($_POST["tallenna"]))
	{
		$m["kayttaja_id"] = parsePost("mid");
		$m["tunnus"] = parsePost("mtunnus");
		$m["salasana"] = parsePost("msalasana");
		$m["nimi"] = parsePost("mnimi");
		$m["osoite"] = parsePost("mosoite");
		$m["postinro"] = parsePost("mpostinro");
		$m["postitmp"] = parsePost("mpostitmp");
		$m["asty"] = parsePost("masty");

		$result = updateAsiakas($m);
		echo $result;
	}
	

	if ( isset($_GET["hae_asiakas_json"]) )
	{
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');

		$result = fetchAsiakas(null, null, null);
		//print_r($result);
		echo json_encode(utf8ize($result));
//		echo json_last_error_msg();
		exit();
	}

	if ( isset($_GET["hae_asiakastyyppi_json"]) )
	{
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');

		$result = fetchAsiakastyyppi();
		echo json_encode(utf8ize($result));
		exit();
	}
	
	if ( isset($_GET["hae"]) )
    {
		$haku["nimi"] = parseGet("hnimi");
		$haku["osoite"] = parseGet("osoite");
		$haku["kayttaja_id"] = parseGet("kayttaja_id");
		$data = fetchAsiakas($haku);
		//print_r($data);
		// $data sisältää datan indeksoidussa taulukkossa, jossa jokainen alkio on assosiatiivinen taulukko
		echo "<table>";
		echo "<tr><th>Avain</th><th>Nimi</th><th>Osoite</th><th>Toiminto</th></tr>";
		foreach($data as $row)
		{
			$id = $row["kayttaja_id"];
			echo "<tr>";
			echo "<td>".$id."</td>";
			//echo "<td>". $row["kayttaja_id"]. "</td">;
			echo "<td>". $row["Nimi"]. "</td>";
			echo "<td>". $row["Osoite"]. "</td>";			
			echo "<td>". $row["Postinro"]. "</td>";	
			echo "<td> <button class=\"muokkaaButton\"onclick=\"muokkaa_asiakas($id)\">Muokkaa </button> </td>";
			echo "<td><button class=\"poistaButton\" onclick=\"poista_asiakas($id);\">Poista</button></td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}


?>