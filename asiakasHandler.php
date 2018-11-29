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

	if ( isset($_GET["muokkaa"]) )
    {
		$tunnus = parseGet("muokkaa");		
		$result = fetchAsiakasID($tunnus);
		echo($result);

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