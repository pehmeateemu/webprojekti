<?php
	require_once("db_utils.inc");

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
		$nimi = parseGet("nimi");
		$osoite = parseGet("osoite");
		$kayttaja_id = parseGet("kayttaja_id");
		
		$data = fetchAsiakas($nimi, $osoite, $kayttaja_id);
		
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
			//echo "<td><button class=\"poistaButton\" onclick=\"poista_asiakas($id);\">Poista</button></td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}


?>