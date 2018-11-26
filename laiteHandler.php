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

	if ( isset($_GET["muokkaa"]))
	{		
		$laite_id = parseGet("muokkaa");
		$laite = editLaite($laite_id);

			echo json_encode($laite);
	}

	if ( isset($_GET["poista"]))
	{
		$laite_id = parseGet("poista");
		$result = poistaLaite($laite_id);
		echo $result;
	}

if ( isset($_POST["tallenna"]))
	{
		$m["laite_id"] = parsePost("laite_id");
		$m["tila"] = parsePost("tila");
		$m["nimi"] = parsePost("nimi");
		$m["merkki"] = parsePost("merkki");
		$m["malli"] = parsePost("malli");
		$m["sarjanumero"] = parsePost("sarjanumero");
		$m["kategoria"] = parsePost("kategoria");
		$m["omistaja"] = parsePost("omistaja");
		$m["osoite"] = parsePost("osoite");
		$m["postinro"] = parsePost("postinro");
		$m["postitmp"] = parsePost("postitmp");
		$m["kuvaus"] = parsePost("kuvaus");

		$result = muokkaaLaite($m);
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
		$tila = parseGet("tila");
		
		$data = fetchLaite($laite_id, $nimi, $merkki, $malli, $sarjanumero, $kategoria, $omistaja, $osoite, $postinro, $postitmp, $kuvaus, $tila);
		
		// $data sis�lt�� datan indeksoidussa taulukkossa, jossa jokainen alkio on assosiatiivinen taulukko
		echo "<table id=\"laitteet\">";
		echo "<tr><th>ID</th><th>Nimi</th><th>Merkki</th><th>Malli</th><th>Sarjanumero</th><th>Kategoria</th><th>Omistaja</th><th>Osoite</th><th>Postinumero</th><th>Toimipaikka</th><th>Kuvaus</th></tr>";
		
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
			echo "<td>". $row["Osoite"]. "</td>";
			echo "<td>". $row["Postinro"]. "</td>";
			echo "<td>". $row["Postitmp"]. "</td>";
			echo "<td>". $row["Kuvaus"]. "</td>";
			echo "<td>". $row["Tila"]. "</td>";
			echo "<td> <button class=\"muokkaaButton\"onclick=\"muokkaaLaite($id)\">Muokkaa </button> </td>";
			echo "<td> <button class=\"poistaButton\" onclick=\"poistaLaite($id)\">Poista </button> </td>";
			echo "</tr>";
		}
		
		
		echo "</table>";
	}

	?>
	

