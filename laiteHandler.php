<?php
	require_once("db_utils.inc");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: PUT, GET, POST");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	//check_session();
if ( isset($_POST["lisaa"]))
	{
		//asd
		$a["tila"] = parsePost("tila");
		$a["nimi"] = parsePost("nimi");
		$a["merkki"] = parsePost("merkki");
		$a["malli"] = parsePost("malli");
		$a["sarjanumero"] = parsePost("sarjanumero");
		$a["kategoria"] = parsePost("kategoria");
		$a["omistaja"] = parsePost("omistaja");
		$a["osoite"] = parsePost("osoite");
		$a["postinro"] = parsePost("postinro");
		$a["postitmp"] = parsePost("postitmp");
		$a["kuvaus"] = parsePost("kuvaus");

		$result = createLaite($a);
		echo $result;
	}

	if ( isset($_GET["poista"]))
	{
		$laite_id = parseGet("poista");
		$result = poista_laite($laite_id);
		echo $result;
	}

if ( isset($_GET["hae"]) )
    {
		$laite_id = parseGet("laite_id");
		$nimi = parseGet("nimi");
		$merkki = parseGet("merkki");
		$malli = parseGet("malli");
		$sarjanumero = parseGet("sarjanumero");
		$kategoria = parseGet("kategoria");
		$omistaja = parseGet("omistaja");
		$osoite = parseGet("osoite");
		$postinro = parseGet("postinro");
		$postitmp = parseGet("postitmp");
		$kuvaus = parseGet("kuvaus");
		
		$data = fetchLaite($laite_id, $nimi, $merkki, $malli, $sarjanumero, $kategoria, $omistaja, $osoite, $postinro, $postitmp, $kuvaus);
		
		// $data sisältää datan indeksoidussa taulukkossa, jossa jokainen alkio on assosiatiivinen taulukko
		echo "<table id=\"laitteet\">";
		echo "<tr><th>ID</th><th>Nimi</th><th>Merkki</th><th>Malli</th><th>Sarjanumero</th><th>Kategoria</th><th>Omistaja</th><th>Toimipaikka</th><th>Kuvaus</th></tr>";
		
		foreach($data as $row)
		{
			$id = $row["laite_id"];
			echo "<tr>";
			echo "<td>". $row["laite_id"]. "</td>";
			echo "<td>". $row["Nimi"]. "</td>";
			echo "<td>". $row["Merkki"]. "</td>";
			echo "<td>". $row["Malli"]. "</td>";
			echo "<td>". $row["Sarjanumero"]. "</td>";
			echo "<td>". $row["Kategoria"]. "</td>";
			echo "<td>". $row["Omistaja"]. "</td>";
			echo "<td>". $row["Postitmp"]. "</td>";
			echo "<td>". $row["Kuvaus"]. "</td>";
			echo "<td> <button class=\"poistaButton\" onclick=\"poista_laite($id)\">Poista </button> </td>";
			echo "</tr>";
		}
		
		echo "</table>";
	}

	?>
	

