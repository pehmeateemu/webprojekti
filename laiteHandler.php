<?php
	require_once("db_utils.inc");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: PUT, GET, POST");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	require_once("login_utils.inc");
	session_start();
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
		$data = editLaite($laite_id);
			
			return $data;

	}

	if ( isset($_GET["poista"]))
	{
		$laite_id = parseGet("poista");
		$result = poistaLaite($laite_id);
		echo json_encode($result);
	}

if ( isset($_POST["tallenna"]))
	{
		$m["laite_id"] = parsePost("mlaite_id");
		$m["tila"] = parsePost("mtila");
		$m["nimi"] = parsePost("mnimi");
		$m["merkki"] = parsePost("mmerkki");
		$m["malli"] = parsePost("mmalli");
		$m["sarjanumero"] = parsePost("msarjanumero");
		$m["kategoria"] = parsePost("mkategoria");
		$m["omistaja"] = parsePost("momistaja");
		$m["osoite"] = parsePost("mosoite");
		$m["postinro"] = parsePost("mpostinro");
		$m["postitmp"] = parsePost("mpostitmp");
		$m["kuvaus"] = parsePost("mkuvaus");

		$result = updateLaite($m);
		echo $result;
	}


if ( isset($_GET["hae"]) )
    {
		$hlaite["laite_id"] = parseGet("hlaite_id");
		$hlaite["nimi"] = parseGet("hnimi");
		$hlaite["merkki"] = parseGet("hmerkki");
		$hlaite["malli"] = parseGet("hmalli");
		$hlaite["sarjanumero"] = parseGet("hsarjanumero");
		$hlaite["kategoria"] = parseGet("hkategoria");
		$hlaite["omistaja"] = parseGet("homistaja");
		$hlaite["osoite"] = parseGet("hosoite");
		$hlaite["postinro"] = parseGet("hpostinro");
		$hlaite["postitmp"] = parseGet("hpostitmp");
		$hlaite["kuvaus"] = parseGet("hkuvaus");
		$hlaite["tila"] = parseGet("htila");
		//print_r($hlaite);
		$data = fetchLaite($hlaite);
		
		// $data sis�lt�� datan indeksoidussa taulukkossa, jossa jokainen alkio on assosiatiivinen taulukko
		echo "<table id=\"laitteet\">";
		echo "<tr><th>ID</th><th>Nimi</th><th>Merkki</th><th>Malli</th><th>Sarjanumero</th><th>Kategoria</th><th>Omistaja</th><th>Osoite</th><th>Postinumero</th><th>Toimipaikka</th><th>Kuvaus</th></tr>";
		
		foreach($data as $row)
		{
			if ($row["Tila"] < 5){
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
			echo "<td> <button class=\"varaaButton\" onclick=\"window.location.href='varaus.php?laite=$id'\">Varaa </button> </td>";
			if ($_SESSION["asty"] == 0) {
			echo "<td> <button class=\"muokkaaButton\" onclick=\"muokkaaLaite($id)\">Muokkaa </button> </td>";
			echo "<td> <button class=\"poistaButton\" onclick=\"poistaLaite($id)\">Poista </button> </td>";
			};
			echo "</tr>";
			}
		}
		
		
		echo "</table>";
	}

if ( isset($_GET["varaa"]))
	{
		$laite_id = parseGet("varaa");
		//laitteen id vienti varaus-sivulle
		$_SESSION["laite_id"] = json_encode($laite_id);
		echo $_SESSION["laite_id"];
	}

	?>
	

