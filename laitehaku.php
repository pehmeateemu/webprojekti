<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<?php
	session_start();

	// Tarkistetaan, onko käyttäjä jo kirjautunut järjestelmään, jos ei heitetään login-sivulle
    require_once("login_utils.inc");
    
    $tunnus = $_SESSION['tunnus'];
   // echo "<p> Kirjautuneena: ".$tunnus ."</p>";
   // echo "<pre>";
   // print_r($_SESSION);
   // echo "</pre>";

	
	check_session();
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
    <title>Laitteiden käsittely</title>
	
	<script>
		$(function(){

			
			$("#hae").button();
			$("#lisaa").button();
			$("#muokkaa").button();
			$("#poista").button();
			$("#varaa").button();
		
			$("#hae").click(function(){				
				hae_laitteet();
			});
			
			$("#lisaa").click(function(){
				$("#dialogi_lisaa").dialog("open");
			});
			
			$("#muokkaa").click(function(){
				$("#dialogi_muokkaa").dialog("open");	
			});

			$("#varaa").click(function(){
				varaaLaite($(this).closest('tr').find('td:nth-child(1)').val());
			});

			$("#poista").click(function(){
				poistaLaite($(this).closest('tr').find('td:nth-child(1)').val());
			});
			

			$("#dialogi_lisaa").dialog({
                    autoOpen: false,
                    buttons: 
					[
                        {
                            text: "Lisää",
                            click: function() 
							{
                                if (
										$.trim($("#nimi_lisays").val()) === "" || 
										$.trim($("#merkki_lisays").val()) === "" || 
										$.trim($("#malli_lisays").val()) === "" || 
										$.trim($("#sarjanumero_lisays").val()) === "" || 
										$.trim($("#kategoria_lisays").val()) === "" || 
										$.trim($("#omistaja_lisays").val()) === "" || 
										$.trim($("#osoite_lisays").val()) === "" ||
										$.trim($("#postinro_lisays").val()) === "" || 
										$.trim($("#postitmp_lisays").val()) === "" || 
										$.trim($("#kuvaus_lisays").val()) === "") 
										{
										alert('Anna arvo kaikki kenttiin!');
										return false; 
								} 

								else 
								{
                                    var lisayslauseke = $("#lisayslomake").serialize();
                                    //console.log("Lisäyslauseke: " + lisayslauseke);
                                    lisaaLaite(lisayslauseke);
                                    //$("#lisayslomake")[0].reset();
                                    //$("#asty_avain_lisays").prop('selectedIndex', 0);
                                    $(this).dialog("close");
                                }
                            },
                        },
                        {
                            text: "Peruuta",
                            click: function() {
                                //$("#lisayslomake")[0].reset();
                                //$("#asty_avain_lisays").prop('selectedIndex', 0);
                                $(this).dialog("close");
                            },
                        }
                    ],
                    closeOnEscape: false,
                    draggable: false,
                    modal: true,
                    resizable: false
            });

			$("#dialogi_muokkaa").dialog({
                    autoOpen: false,
                    buttons: 
					[
                        {
                            text: "Tallenna muutokset",
                            click: function() 
							{
                                if (
										$.trim($("#nimi_muokkaus").val()) === "" || 
										$.trim($("#merkki_muokkaus").val()) === "" || 
										$.trim($("#malli_muokkaus").val()) === "" || 
										$.trim($("#sarjanumero_muokkaus").val()) === "" || 
										$.trim($("#kategoria_muokkaus").val()) === "" || 
										$.trim($("#omistaja_muokkaus").val()) === "" || 
										$.trim($("#osoite_muokkaus").val()) === "" ||
										$.trim($("#postinro_muokkaus").val()) === "" || 
										$.trim($("#postitmp_muokkaus").val()) === "" || 
										$.trim($("#kuvaus_muokkaus").val()) === "" || 
										$.trim($("#tila_muokkaus").val()) === ""
									)
									{
										alert('Anna arvo kaikki kenttiin!');
										return false; 
									} 

								else 
								{
                                    var muokkauslauseke = $("#muokkauslomake").serialize();
                                    //console.log("muokkauslauseke: " + muokkauslauseke);
                                    tallennaLaite(muokkauslauseke);
                                    //$("#lisayslomake")[0].reset();
                                    //$("#asty_avain_lisays").prop('selectedIndex', 0);
                                    $(this).dialog("close");
                                }
                            },
                        },
                        {
                            text: "Peruuta",
                            click: function() {
                                //$("#lisayslomake")[0].reset();
                                //$("#asty_avain_lisays").prop('selectedIndex', 0);
                                $(this).dialog("close");
                            },
                        }
                    ],
                    closeOnEscape: false,
                    draggable: false,
                    modal: true,
                    resizable: false
            });

		});
		
		function hae_laitteet()
		{
			haku = $("#haku").serialize();
			$("#laitteet").load("http://localhost:8081/pohjia/php/laiteHandler.php?hae", haku, function(){
				$(".varaaButton").button();
				$(".muokkaaButton").button();
				$(".poistaButton").button();	// Pakko laittaa tänne, koska poista-buttoneita ei ole selaimessa ennenkuin data on haettu
			});
		}
		
		function varaaLaite(avain)
		{
			$.get(
                "http://localhost:8081/pohjia/php/laiteHandler.php?varaa=" + avain
            )
			.done(function (data, textStatus, jqXHR) {
			location.href = "varaus.php";
				
            })
			.fail(function (jqXHR, textStatus, errorThrown) {
                console.log("poista_laite: status=" + textStatus + ", " + errorThrown);
            });
		}

		function poistaLaite(avain)
		{
		    $.get(
                "http://localhost:8081/pohjia/php/laiteHandler.php?poista=" + avain
            )
			.done(function (data, textStatus, jqXHR) {

				hae_laitteet();
            })
			.fail(function (jqXHR, textStatus, errorThrown) {
                console.log("poista_laite: status=" + textStatus + ", " + errorThrown);
            });
		}
		
		function lisaaLaite(lisayslauseke) {
            $.post(
                "http://localhost:8081/pohjia/php/laiteHandler.php?lisaa",
                lisayslauseke
            ).done(function (data, textStatus, jqXHR) {
                hae_laitteet();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("lisaaLaite: status=" + textStatus + ", " + errorThrown);
            });
			}
		function muokkaaLaite(avain)
		{
			$.get(
			"http://localhost:8081/pohjia/php/laiteHandler.php?muokkaa=" + avain
			).done(function (data, textStatus, jqXHR) {
					//console.log(data);
					//alert(data);
					var laite = $.parseJSON(data);
					document.getElementById("nimi_muokkaus").value=laite[0]['Nimi'];
					document.getElementById("merkki_muokkaus").value=laite[0]['Merkki'];
					document.getElementById("malli_muokkaus").value=laite[0]['Malli'];
					document.getElementById("sarjanumero_muokkaus").value=laite[0]['Sarjanumero'];
					document.getElementById("kategoria_muokkaus").value=laite[0]['Kategoria'];
					document.getElementById("omistaja_muokkaus").value=laite[0]['Omistaja'];
					document.getElementById("osoite_muokkaus").value=laite[0]['Osoite'];
					document.getElementById("postinro_muokkaus").value=laite[0]['Postinro'];
					document.getElementById("postitmp_muokkaus").value=laite[0]['Postitmp'];
					document.getElementById("kuvaus_muokkaus").value=laite[0]['Kuvaus'];
					document.getElementById("laite_id_muokkaus").value=laite[0]['laite_id'];
					document.getElementById("tila_muokkaus").value=laite[0]['Tila'];
					//console.log(laite[0]);
					$("#dialogi_muokkaa").dialog("open");
			}).fail(function (jqXHR, textStatus, errorThrown) {
					console.log("muokkaaLaite: status=" + textStatus + ", " + errorThrown);					
			});
		}

		function tallennaLaite(muokkauslauseke) 
		{
			$.post("http://localhost:8081/pohjia/php/laiteHandler.php?tallenna",
                muokkauslauseke
            ).done(function (data, textStatus, jqXHR) {
                hae_laitteet();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("muokkaaLaite: status=" + textStatus + ", " + errorThrown);
            });

		}
	</script>
</head>
<body>	
	<a href="http://localhost:8081/pohjia/php/asiakas.php">Tiedot</a>
	<a href="http://localhost:8081/pohjia/php/laitehaku.php">Laitteet</a>
	<a href="http://localhost:8081/pohjia/php/logout.php">Kirjaudu ulos</a>
	<p>Anna hakuehdot</p>
	<form id="haku">
		<table>
		<tr><th>Nimi</th><th>Merkki</th><th>Malli</th></tr>
		<tr>
		<td><input type="text" id="nimi_haku" name="hnimi" /> </td>
		<td><input type="text" id="merkki_haku" name="hmerkki" /> </td>
		<td><input type="text" id="malli_haku" name="hmalli" /> </td></tr>

		<tr><th>Sarjanumero</th><th>Kategoria</th><th>Omistaja</th></tr>
		<tr>
		<td><input type="text" id="sarjanumero_haku" name="hsarjanumero" /> </td>
		<td><input type="text" id="kategoria_haku" name="hkategoria" /> </td>
		<td><input type="text" id="omistaja_haku" name="homistaja" /> </td></tr>

		<tr><th>Osoite</th><th>Postinumero</th><th>Toimipaikka</th></tr>
		<tr>
		<td><input type="text" id="osoite_haku" name="hosoite" /> </td>
		<td><input type="text" id="postinro_haku" name="hpostinro" /> </td>
		<td><input type="text" id="postitmp_haku" name="hpostitmp" /> </td></tr>
		</table>
		<br />
	</form>
	<button id="hae">Hae laitteet</button>
	<?php if($_SESSION["asty"] == 0) { 
	echo '<button id="lisaa">Lisää uusi laite</button>';
	} ?>
	
	<div id="laitteet"></div>

    <div id="dialogi_lisaa" title="Lisää uusi laite">
        <form id="lisayslomake">			
            <input type="hidden" name="lisaa" />
            <input type="text" id="nimi_lisays" name="nimi" placeholder="Nimi" size="32">
            <input type="text" id="merkki_lisays" name="merkki" placeholder="Merkki" size="32">
            <input type="text" id="malli_lisays" name="malli" placeholder="Malli" size="32">
            <input type="text" id="sarjanumero_lisays" name="sarjanumero" placeholder="Sarjanumero" size="32">
			<input type="text" id="kategoria_lisays" name="kategoria" placeholder="Kategoria" size="32">
            <input type="text" id="omistaja_lisays" name="omistaja" placeholder="Omistaja" size="32">
            <input type="text" id="osoite_lisays" name="osoite" placeholder="Osoite" size="32">
            <input type="text" id="postinro_lisays" name="postinro" placeholder="Postinumero" size="32">
            <input type="text" id="postitmp_lisays" name="postitmp" placeholder="Postitoimipaikka" size="32">
			<textarea form="lisayslomake" class="formInput" id="kuvaus_lisays" name="kuvaus" placeholder="Kuvaus" cols="30" rows="4" style="resize:none"></textarea>
			<select id="tila_lisays" name="tila">
            <option value="1"></option>
            </select>
        </form>
		
		    </div>


		<div id="dialogi_muokkaa" title="Muokkaa laitteen tietoja">
        <form id="muokkauslomake">			
			<input type="text" id="laite_id_muokkaus" name="mlaite_id" readonly="readonly"></label>
            <input type="hidden" name="tallenna" />
            <input type="text" id="nimi_muokkaus" name="mnimi" placeholder="Nimi" size="32">
            <input type="text" id="merkki_muokkaus" name="mmerkki" placeholder="Merkki" size="32">
            <input type="text" id="malli_muokkaus" name="mmalli" placeholder="Malli" size="32">
            <input type="text" id="sarjanumero_muokkaus" name="msarjanumero" placeholder="Sarjanumero" size="32">
			<input type="text" id="kategoria_muokkaus" name="mkategoria" placeholder="Kategoria" size="32">
            <input type="text" id="omistaja_muokkaus" name="momistaja" placeholder="Omistaja" size="32">
            <input type="text" id="osoite_muokkaus" name="mosoite" placeholder="Osoite" size="32">
            <input type="text" id="postinro_muokkaus" name="mpostinro" placeholder="Postinumero" size="32">
            <input type="text" id="postitmp_muokkaus" name="mpostitmp" placeholder="Postitoimipaikka" size="32">
			<textarea form="muokkauslomake" class="formInput" id="kuvaus_muokkaus" name="mkuvaus" placeholder="Kuvaus" cols="30" rows="4" style="resize:none"></textarea>
			<select id="tila_muokkaus" name="tila">
            <option value="1">VAPAA</option>
			<option value="2">VARATTU</option>
			<option value="3">LAINASSA</option>
			<option value="5">POISTETTU</option>
            </select>
        </form>
		
    </div>


</body>
</html>
