<?php
	require_once("db_utils.inc");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: PUT, GET, POST");
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	require_once("login_utils.inc");
	session_start();
	//check_session();

	if ( isset($_GET["hae"]))
	{
		$id = $_SESSION["laite_id"];
		$data = fetchVaraus($id);
		echo "<table id=\"varaukset\">";
		echo "<tr><th>VarausID</th><th>LaiteID</th><th>Merkki</th><th>Malli</th><th>Varaaja</th><th>Aloituspvm</th><th>Lopetuspvm</th><th>Tila</th></tr>";
		//print_r($data);
		foreach($data as $row)
		{
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
			if ($row["varaus_tila"] == 1)	{	echo "<td> Lainaus </td> <td> <button class=\"palautaButton\" onclick=\"palautaLainaus($id)\">Palauta </button></td>";
				if ($_SESSION["asty"] == 0) {	echo "<td> <button class=\"muokkaaButton\" onclick=\"muokkaaVaraus($id)\">Muokkaa</button></td>";}}
			if ($row["varaus_tila"] == 2) {echo '<td> Palautettu </td>';}
			if ($row["varaus_tila"] == 3) {echo "<td> Huolto </td>  </td> <td> <button class=\"palautaButton\" onclick=\"palautaLainaus($id)\">Palauta </button></td><td> <button class=\"muokkaaButton\" onclick=\"muokkaaVaraus($id)\">Muokkaa</button></td>";}
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
		
		
		echo "</table>";
	}

	if ( isset($_GET["poista"]))
	{
		$varaus_id = parseGet("poista");
		$result = poistaVaraus($varaus_id);
		echo json_encode($result);
	}

	if ( isset($_GET["lainaa"]))
	{
		$varaus_id = parseGet("lainaa");
		$result = lainaaVaraus($varaus_id);
		echo json_encode($result);
	}

	if ( isset($_GET["palauta"]))
	{
		$varaus_id = parseGet("palauta");
		$result = palautaLainaus($varaus_id);
		echo json_encode($result);
	}

	if ( isset($_POST["teevaraus"]))
	{
		//asd
		$a["tila"] = parsePost("tila");
		$a["laite_id"] = parsePost("vlaite");
		$a["kayttaja_id"] = parsePost("asiakas");
		$a["aloituspvm"] = parsePost("aloitus");
		$a["lopetuspvm"] = parsePost("lopetus");

		$result = lisaaVaraus($a);
		echo $result;
	}

		if ( isset($_GET["muokkaa"]))
	{	
		
		$varaus_id = parseGet("muokkaa");
		$data = editVaraus($varaus_id);
			
			print_r($data);

	}

	if ( isset($_POST["tallenna"]))
	{
		$m["tila"] = parsePost("mtila");
		$m["varaus_id"] = parsePost("mvaraus_id");
		$m["aloituspvm"] = parsePost("maloitus");
		$m["lopetuspvm"] = parsePost("mlopetus");
		$result = updateVaraus($m);
		echo $result;
	}

	?>