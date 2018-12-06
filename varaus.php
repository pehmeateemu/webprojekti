<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Sis‰‰nkirjautuminen</title>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	//
    <title>Laitteiden k‰sittely</title>
	
	<script>
		// 2-tason vaatimukset
		// Laitteen voi varata kuka tahansa kirjautunut k‰ytt‰j‰
		// Laitteen varauksen voi PURKAA admin-k‰ytt‰j‰ tai laitteen varannut k‰ytt‰j‰ 
		// Laitteen varausta voi MUUTTAA admin-k‰ytt‰j‰ tai laitteen varannut k‰ytt‰j‰ 

		// 3-tason vaatimukset
		// Laitetta ei voi varata, jos laite on jo varattu tai lainattu
		// Laitteen varauksen voi poistaa vain, jos varauksesta ei ole tehty lainausta
		// Laitteen varausta voi muuttaa vain, jos varauksesta ei ole tehty lainausta
		// K‰ytt‰j‰n t‰ytyy n‰hd‰ helposti omat varaukset/lainat

		// 4-tason vaatimukset
		// Laitteen voi lainata vain jos siihen liittyy varaus. Lainaaminen voidaan hoitaa niin, ett‰ varauksen tila muutetaan lainatuksi. 
		// Vain admin-k‰ytt‰j‰ voi merkata laitteen lainatuksi (tyypillisesti silloin kun varaaja tulee noutamaan laitetta)
		// Jos varattu laite merkataan vahingossa lainatuksi, on se voitava purkaa (vain admin-k‰ytt‰j‰), jolloin laina muuttuu takaisin varaukseksi
		// Lainattu laite palautetaan muuttamalla lainan tila palautetuksi. Vain admin-k‰ytt‰j‰ voi palauttaa laitteen
		// Kustakin laitteesta t‰ytyy n‰hd‰ helposti ko. laitteen vanhat ja tulevat varaukset/lainaukset

		// 5-tason vaatimukset
		// Kun laite palautetaan, antaa admin-k‰ytt‰j‰ pvm:n, jolloin laite palautettiin -> samalla muutetaan varauksen loppumispvm:‰‰, jolloin laite on taas varattavissa annetusta loppupvm:st‰ alkaen. Eli jos k‰ytt‰j‰ palauttaakin laitteen ennen loppumispvm:‰‰
		// Jos laite on lainattu, voi admin-k‰ytt‰j‰ muuttaa lainan loppumispvm:‰‰(varaaja soittaa admin:lleaiheesta). Lainaa voi muuttaa vain jos laitteelle ei ole varausta, joka menee uuden loppumispvm:np‰‰lle. 
		// Admin-k‰ytt‰j‰ voi merkata laitteelle esim. huoltoja, jolloin laite ei ole varattavissa. T‰m‰ voidaan toteuttaa niin, ett‰ admin-k‰ytt‰j‰ voi antaa varaukselle tyypin. Ota huomioon, ett‰ admin-k‰ytt‰j‰n on voitava muuttaa/poistaa n‰it‰ varauksia.

		$(function(){
			


			$("#hae").button();
			$("#varaa").button();
			$("#muokkaa").button();
			$("#poista").button();
		
			$("#hae").click(function(){				
				haeVaraukset();
			});

			$("#varaa").click(function(){
				$("#dialogi_varaa").dialog("open");
			});

			$("#muokkaa").click(function() {
				muokkaaVaraus();
			});

			$("#poista").click(function(){
				poistaVaraus();
			});

			$( function() {
					$( "#aloitus_lisays" ).datepicker();
			});

			$( function() {
					$( "#lopetus_lisays" ).datepicker();
			});

			$( function() {
					$( "#aloitus_muokkaus" ).datepicker();
			});

			$( function() {
					$( "#lopetus_muokkaus" ).datepicker();
			});

			$("#dialogi_varaa").dialog({
                    autoOpen: false,
                    buttons: [
                        {
                            text: "Varaa",
                            click: function() {
                                if ($.trim($("#laite_lisays").val()) === "" ||
									$.trim($("#asiakas_lisays").val()) === "" ||
									$.trim($("#aloitus_lisays").val()) === "" ||
									$.trim($("#lopetus_lisays").val()) === "" ) 
								{                                   
									   alert('Anna arvo kaikki kenttiin!');
									   return false;
								}
								else if (Date.parse($("#aloitus_lisays").val()) >= Date.parse($("#lopetus_lisays").val())) 
								{
									alert('Alkamispvm ei voi olla sama tai suurempi kuin loppumispvm!');
									return false;								
                                } 
								else 
								{
                                    var varauslauseke = $("#varauslomake").serialize();
                                    console.log("Varauslauseke: " + varauslauseke);
                                    lisaaVaraus(varauslauseke);
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

		function lisaaVaraus(lisayslauseke) {
            $.post(
                "http://localhost:8081/pohjia/php/varausHandler.php?varaa",
                varauslauseke
            ).done(function (data, textStatus, jqXHR) {
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("lisaaAsiakas: status=" + textStatus + ", " + errorThrown);
            });
        }
		


	</script>
</head>
<body>
	
	
	
	<button id="varaa">Varaa laite</button>


	<div id="dialogi_varaa" title="Varaa laite">
	
        <form id="varauslomake">			
            <input type="hidden" name="teevaraus" />
			<input type="text" id="laite_lisays" name="laite" placeholder="Laite" readonly="readonly">
			<input type="password" id="asiakas_lisays" name="asiakas" placeholder="Asiakas" readonly="readonly"> 
			<input type="text" id="aloitus_lisays" name="aloitus" placeholder="Varauksen alkamispvm"> 
            <input type="text" id="lopetus_lisays" name="lopetus" placeholder="Varauksen loppumispvm">
            <select id="tila_lisays" name="tila" readonly="readonly">
                <option value="0"></option>
            </select>

			
        </form>
    </div>
	
</body>
</html>