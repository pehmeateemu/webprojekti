<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Varaukset</title>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	<?php 

	session_start();

	require_once("login_utils.inc");
    
    $tunnus = $_SESSION['tunnus'];
    echo "<p> Kirjautuneena: ".$tunnus ."</p>";

	$_SESSION['laite_id'] = $_GET['laite']; 

	?>
	
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

		var laite_id = "<?php echo $_SESSION['laite_id'] ?>";

		console.log(laite_id);
		$(function(){
			

			$("#hae").button();
			$("#varaa").button();
			$("#lainaa").button();
			$("#palauta").button();
			$("#muokkaa").button();
			$("#poista").button();
		
			$("#hae").click(function(){				
				haeVaraukset();
			});

			$("#varaa").click(function(){
				$("#dialogi_varaa").dialog("open");
			});

			$("#muokkaa").click(function() {
				muokkaaVaraus($(this).closest('tr').find('td:nth-child(1)').val());
			});

			$("#lainaa").click(function() {
				lainaaVaraus($(this).closest('tr').find('td:nth-child(1)').val());
			});

			$("#palauta").click(function() {
				palautaLainaus($(this).closest('tr').find('td:nth-child(1)').val());

			});

			$("#poista").click(function(){
				poistaVaraus($(this).closest('tr').find('td:nth-child(1)').val());
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

			$( function() {
					$( "#lopetus_palautus" ).datepicker();
			});

				$("#dialogi_palauta").dialog({
                    autoOpen: false,
                    buttons: [
                        {
                            text: "Palauta",
                            click: function() {
                                if ($.trim($("#lopetus_palautus").val()) === "" )

								{                                   
									   alert('Anna palautusp‰iv‰m‰‰r‰!');
									   return false;
								}
								else 
								{
                                    var palautuslauseke = $("#palautuslomake").serialize();
                                    console.log("Palautuslauseke: " + palautuslauseke);
                                    tallennaPalautus(palautuslauseke);
									$(this).dialog("close");
                                }
                            },
                        },
                        {
                            text: "Peruuta",
                            click: function() {
                                $(this).dialog("close");
                            },
                        }
                    ],
                    closeOnEscape: false,
                    draggable: false,
                    modal: true,
                    resizable: false
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
									$(this).dialog("close");
                                    //$("#lisayslomake")[0].reset();
                                    //$("#asty_avain_lisays").prop('selectedIndex', 0);
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
                    buttons: [
                        {
                            text: "Varaa",
                            click: function() {
                                if ($.trim($("#laite_muokkaus").val()) === "" ||
									$.trim($("#asiakas_muokkaus").val()) === "" ||
									$.trim($("#aloitus_muokkaus").val()) === "" ||
									$.trim($("#lopetus_muokkaus").val()) === "" )

								{                                   
									   alert('Anna arvo kaikki kenttiin!');
									   return false;
								}
								else if (Date.parse($("#aloitus_muokkaus").val()) >= Date.parse($("#lopetus_muokkaus").val())) 
								{
									alert('Alkamispvm ei voi olla sama tai suurempi kuin loppumispvm!');
									return false;								
                                } 
								else 
								{
                                    var muokkauslauseke = $("#muokkauslomake").serialize();
                                    console.log("muokkauslauseke: " + muokkauslauseke);
                                    tallennaVaraus(muokkauslauseke);
									$(this).dialog("close");
                                    //$("#lisayslomake")[0].reset();
                                    //$("#asty_avain_lisays").prop('selectedIndex', 0);
                                    
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

		function muokkaaVaraus(avain)
		{
		$.get(
			"http://localhost:8081/pohjia/php/varausHandler.php?muokkaa=" + avain
			).done(function (data, textStatus, jqXHR) {
					console.log(data);
					var varaus = $.parseJSON(data);
					document.getElementById("varaus_muokkaus").value=avain;
					document.getElementById("laite_muokkaus").value=laite_id;
					//document.getElementById("maloitus").value=varaus['aloituspvm'];
					//document.getElementById("mlopetus").value=varaus['lopetuspvm'];
					console.log(varaus[0]);
					$("#dialogi_muokkaa").dialog("open");
			}).fail(function (jqXHR, textStatus, errorThrown) {
					console.log("muokkaaVaraus: status=" + textStatus + ", " + errorThrown);					
			});
		}

		function tallennaVaraus(muokkauslauseke) 
		{
			$.post("http://localhost:8081/pohjia/php/varausHandler.php?tallenna",
                muokkauslauseke
            ).done(function (data, textStatus, jqXHR) {
                haeVaraukset();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("tallennaVaraus: status=" + textStatus + ", " + errorThrown);
            });

		}

		function lisaaVaraus(varauslauseke) {
            $.post(
                "http://localhost:8081/pohjia/php/varausHandler.php?teevaraus", varauslauseke)
				.done(function (data, textStatus, jqXHR) 
				{

				var lisays = $.parseJSON(data);
				if (lisays[0] = false) {
				alert(lisays[1]);
				haeVaraukset();}
				else {
					alert(lisays);
				}
				})
			.fail(function (jqXHR, textStatus, errorThrown) 
				{
                console.log("lisaaAsiakas: status=" + textStatus + ", " + errorThrown);
				});
        }

		function haeVaraukset()
		{
			haku = laite_id;
			console.log(haku);
			$("#varaukset").load("http://localhost:8081/pohjia/php/varausHandler.php?hae", haku, function(){
				$(".lainaaButton").button();
				$(".palautaButton").button();
				$(".muokkaaButton").button();
				$(".poistaButton").button();	// Pakko laittaa t‰nne, koska poista-buttoneita ei ole selaimessa ennenkuin data on haettu
			});
		}

		function poistaVaraus(varaus_id)
		{
			
		    $.get(
                "http://localhost:8081/pohjia/php/varausHandler.php?poista=" + varaus_id
            )
			.done(function (data, textStatus, jqXHR) {

				haeVaraukset();
            })
			.fail(function (jqXHR, textStatus, errorThrown) {
                console.log("poista_laite: status=" + textStatus + ", " + errorThrown);
            });
		
		}

		function lainaaVaraus(varaus_id)
		{
			
		    $.get(
                "http://localhost:8081/pohjia/php/varausHandler.php?lainaa=" + varaus_id
            )
			.done(function (data, textStatus, jqXHR) {

				haeVaraukset();
            })
			.fail(function (jqXHR, textStatus, errorThrown) {
                console.log("lainaavaraus: status=" + textStatus + ", " + errorThrown);
            });
		
		}

		function palautaLainaus(avain)
		{
			$.get(
			"http://localhost:8081/pohjia/php/varausHandler.php?palauta=" + avain
			).done(function (data, textStatus, jqXHR) {
					console.log(data);
					var varaus = $.parseJSON(data);
					document.getElementById("varaus_palautus").value=avain;
					//document.getElementById("maloitus").value=varaus['aloituspvm'];
					//document.getElementById("mlopetus").value=varaus['lopetuspvm'];
					console.log(varaus[0]);
					$("#dialogi_palauta").dialog("open");
			}).fail(function (jqXHR, textStatus, errorThrown) {
					console.log("muokkaaVaraus: status=" + textStatus + ", " + errorThrown);					
			});	
		}
		function tallennaPalautus(palautuslauseke)
		{
			$.post(
                "http://localhost:8081/pohjia/php/varausHandler.php?teepalautus", palautuslauseke
            )
			.done(function (data, textStatus, jqXHR) {

				haeVaraukset();
            })
			.fail(function (jqXHR, textStatus, errorThrown) {
                console.log("palautalainaus: status=" + textStatus + ", " + errorThrown);
            });
		}



	</script>
</head>
<body>

	<a href="http://localhost:8081/pohjia/php/asiakas.php">Tiedot</a>
	<a href="http://localhost:8081/pohjia/php/laitehaku.php">Laitteet</a>
	<a href="http://localhost:8081/pohjia/php/logout.php">Kirjaudu ulos</a>
	<br/>
	<br/>
	<button id="hae">Hae varaukset</button>
	<button id="varaa">Varaa laite</button>

	<div id="varaukset"></div>

	<div id="dialogi_varaa" title="Varaa laite">
	
        <form id="varauslomake">			
            <input type="hidden" name="teevaraus" />
			<input type="hidden" id="laite_lisays" name="vlaite" value="<?php echo $_SESSION['laite_id'] ?>">
			
			<input type="radio" name="tila" id="tila_varaus" value="0" checked="checked">Varaus<br>
			
			<?php 
			$kid = $_SESSION["kid"];
			$nimi = $_SESSION["nimi"];
			if ($_SESSION["asty"] == 0) {
			echo "
			<input type=\"radio\" name=\"tila\" id=\"tila_lainaus\" value=\"1\">Lainaus<br>
			<input type=\"radio\" name=\"tila\" id=\"tila_huolto\" value=\"3\">Huolto<br>
			<input type=\"text\" id=\"asiakas_lisays\" name=\"asiakas\" placeholder=\"Asiakas\" value=\"$kid\">
			";
			}
			else {
			echo "<input type=\"hidden\" id=\"asiakas_lisays\" name=\"asiakas\" placeholder=\"Asiakas\" value=\"$kid\">";} 
			
			?>

			<input type="text" id="aloitus_lisays" name="aloitus" placeholder="Varauksen alkamispvm"> 
            <input type="text" id="lopetus_lisays" name="lopetus" placeholder="Varauksen loppumispvm">
            

			
        </form>
    </div>
	
	<div id="dialogi_muokkaa" title="Varaa laite">
	
        <form id="muokkauslomake">			
            <input type="hidden" name="tallenna" />
			<input type="hidden" id="varaus_muokkaus" name="mvaraus_id" value="">
			<input type="hidden" id="laite_muokkaus" name="mlaite_id" value="">
			
			<input type="radio" name="mtila" id="tila_varaus" value="0" checked="checked">Varaus<br>
			
			<?php 
			$kid = $_SESSION["kid"];
			$nimi = $_SESSION["nimi"];
			if ($_SESSION["asty"] == 0) {
			echo "
			<input type=\"radio\" name=\"mtila\" id=\"tila_lainaus\" value=\"1\">Lainaus<br>
			<input type=\"radio\" name=\"mtila\" id=\"tila_huolto\" value=\"3\">Huolto<br>
			<input type=\"text\" id=\"asiakas_muokkaus\" name=\"masiakas\" placeholder=\"Asiakas\" value=\"$kid\">
			";
			}
			else {
			echo "<input type=\"hidden\" id=\"asiakas_muokkaus\" name=\"masiakas\" placeholder=\"Asiakas\" value=\"$kid\">
				  <input type=\"text\" id=\"asiakas_muokkaus\" name=\"nimi\" placeholder=\"Asiakas\" value=\"$nimi\" readonly=\"readonly\">";} 
			
			?>

			<input type="text" id="aloitus_muokkaus" name="maloitus" placeholder="Varauksen alkamispvm"> 
            <input type="text" id="lopetus_muokkaus" name="mlopetus" placeholder="Varauksen loppumispvm">

        </form>
    </div>
	<div id="dialogi_palauta" title="Palauta laite">

		<form id="palautuslomake">
		<input type="hidden" name="teepalautus" />
		<input type="hidden" id="varaus_palautus" name="pvaraus_id" value="">
		<input type="text" id="lopetus_palautus" name="plopetus" placeholder="Palautusp‰iv‰m‰‰r‰">
		
	</form>
	</div>

</body>
</html>