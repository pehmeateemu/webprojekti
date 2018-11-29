<?php
	session_start();

	// Tarkistetaan, onko käyttäjä jo kirjautunut järjestelmään, jos ei heitetään login-sivulle
    require_once("login_utils.inc");
    
    $tunnus = $_SESSION['tunnus'];
    echo "<p> Kirjautuneena: ".$tunnus ."</p>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";

	
	check_session();
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
    <title>Asiakkaiden käsittely</title>
	
	<script>
		$(function(){
			
			haeAsiakastyypit()
			
			$("#hae").button();
			$("#lisaa").button();
			$("#muokkaa").button();
            $("#logout").button();
		
            
            $("#logout").click(function(){
                location.href="logout.php";
			});
		
			$("#hae").click(function(){
				hae_asiakkaat();
			});
			
			$("#lisaa").click(function(){
				$("#dialogi_lisaa").dialog("open");
			});
            $("#muokkaa").click(function(){                
                $tunnus = "<?php echo $_SESSION['tunnus'];?>";
				muokkaa_asiakas($tunnus);
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
										$.trim($("#tunnus_muokkaus").val()) === "" || 
										$.trim($("#salasana_muokkaus").val()) === "" || 
										$.trim($("#nimi_muokkaus").val()) === "" || 
										$.trim($("#osoite_muokkaus").val()) === "" || 
										$.trim($("#postinro_muokkaus").val()) === "" || 
										$.trim($("#postitmp_muokkaus").val()) === "" || 
										$.trim($("#asty_muokkaus").val()) === "")
									{
										alert('Anna arvo kaikki kenttiin!');
										return false; 
									} 

								else 
								{
                                    var muokkauslauseke = $("#muokkauslomake").serialize();
                                    console.log("muokkauslauseke: " + muokkauslauseke);
                                    tallennaAsiakas(muokkauslauseke);
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

			$("#dialogi_lisaa").dialog({
                    autoOpen: false,
                    buttons: [
                        {
                            text: "Lisää",
                            click: function() {
                                if ($.trim($("#tunnus_lisays").val()) === "" || $.trim($("#salasana_lisays").val()) === "" || $.trim($("#nimi_lisays").val()) === "" || $.trim($("#osoite_lisays").val()) === "" || $.trim($("#postinro_lisays").val()) === "" || $.trim($("#postitmp_lisays").val()) === "" || $.trim($("#asty_lisays").val()) === "" || $.trim($("#salasana_lisays").val()) != $.trim($("#salasana_tarkistus").val())) {
                                   
								   alert('Anna arvo kaikki kenttiin!');
                                    return false;
                                } else {
                                    var lisayslauseke = $("#lisayslomake").serialize();
                                    console.log("Lisäyslauseke: " + lisayslauseke);
                                    lisaaAsiakas(lisayslauseke);
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
		
		function hae_asiakkaat()
		{
		haku = $("#haku").serialize();
			$("#asiakkaat").load("http://localhost:8081/pohjia/php/asiakasHandler.php?hae=asiakas", haku, function(){
				$(".poistaButton").button();	// Pakko laittaa tänne, koska poista-buttoneita ei ole selaimessa ennenkuin data on haettu
                $(".muokkaaButton").button();
            });
		}
		
		function poista_asiakas(avain)
		{
		    $.get(
                "http://localhost:8081/pohjia/php/asiakasHandler.php?poista=" + avain
            ).done(function (data, textStatus, jqXHR) {
				hae_asiakkaat();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("poista_asiakas: status=" + textStatus + ", " + errorThrown);
            });
		}
		
		function lisaaAsiakas(lisayslauseke) {
            $.post(
                "http://localhost:8081/pohjia/php/asiakasHandler.php?lisaa",
                lisayslauseke
            ).done(function (data, textStatus, jqXHR) {
                hae_asiakkaat();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("lisaaAsiakas: status=" + textStatus + ", " + errorThrown);
            });
        }

        function muokkaa_asiakas(tunnus)
		{
			$.get(
			"http://localhost:8081/pohjia/php/asiakasHandler.php?muokkaa=" + tunnus
			).done(function (data, textStatus, jqXHR) {
					//console.log(data);
					var asiakas = $.parseJSON(data);
					document.getElementById("id_muokkaus").value=asiakas[0]['kayttaja_id'];
					document.getElementById("tunnus_muokkaus").value=asiakas[0]['Tunnus'];
					document.getElementById("salasana_muokkaus").value=asiakas[0]['Salasana'];
					document.getElementById("nimi_muokkaus").value=asiakas[0]['Nimi'];
					document.getElementById("osoite_muokkaus").value=asiakas[0]['Osoite'];
					document.getElementById("postinro_muokkaus").value=asiakas[0]['Postinro'];
					document.getElementById("postitmp_muokkaus").value=asiakas[0]['Postitmp'];
					document.getElementById("asty_muokkaus").value=asiakas[0]['asty'];
					//console.log(asiakas[0]);
					$("#dialogi_muokkaa").dialog("open");
			}).fail(function (jqXHR, textStatus, errorThrown) {
					console.log("muokkaaLaite: status=" + textStatus + ", " + errorThrown);					
			});
		}
            
        function tallennaAsiakas(muokkauslauseke) 
		{
			$.post("http://localhost:8081/pohjia/php/asiakasHandler.php?tallenna",
                muokkauslauseke
            ).done(function (data, textStatus, jqXHR) {
				hae_asiakkaat();

            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("tallennaAsiakas: status=" + textStatus + ", " + errorThrown);
            });

		}

		function haeAsiakastyypit() {
            $.get(
                "http://localhost:8081/pohjia/php/asiakasHandler.php?hae_asiakastyyppi_json" 
            ).done(function (data, textStatus, jqXHR) {
                $.each(data, function (index, tyyppi) {

                    $('#asty_avain')
                        .append($("<option></option>")
                            .attr("value", tyyppi.Avain)
                            .text(tyyppi.Lyhenne + " - " + tyyppi.Selite));

					$('#asty_avain_lisays')
                        .append($("<option></option>")
                            .attr("value", tyyppi.Avain)
                            .text(tyyppi.Lyhenne + " - " + tyyppi.Selite));

                });
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("status=" + textStatus + ", " + errorThrown);
            });
        }

	</script>
</head>
<body>	
	<a href="http://localhost:8081/pohjia/php/asiakas.php?">Tiedot</a>
	<a href="http://localhost:8081/pohjia/php/laitehaku.php">Laitteet</a>
	<a href="http://localhost:8081/pohjia/php/logout.php">Kirjaudu ulos</a>
	<p>Anna hakuehdot</p>
	<form id="haku">
		NIMI
		<input type="text" id="nhimi" name="hnimi" /> 
		<select id="asty_avain" name="Asty_avain">
			<option value=""></option>
        </select>

		<br />
	</form>
	<button id="hae">Hae asiakkaat</button>
	<button id="lisaa">Lisää uusi asiakas</button>
    <button id="muokkaa">Muokkaa omia tietoja</button>
	
	<div id="asiakkaat"></div>

    <div id="dialogi_lisaa" title="Lisää uusi asiakas">
        <form id="lisayslomake">			
                <input type="hidden" name="lisaa" />
			<input type="text" id="tunnus_lisays" name="tunnus" placeholder="Tunnus">
			<input type="password" id="salasana_lisays" name="salasana" placeholder="Salasana"> 
			<input type="password" id="salasana_tarkistus" name="salasana2" placeholder="Salasana"> 
            <input type="text" id="nimi_lisays" name="nimi" placeholder="Nimi">
            <input type="text" id="osoite_lisays" name="osoite" placeholder="Osoite">
            <input type="text" id="postinro_lisays" name="postinro" placeholder="Postinumero">
            <input type="text" id="postitmp_lisays" name="postitmp" placeholder="Postitoimipaikka">
            <select id="asty_lisays" name="asty">
                <option value="0"></option>
            </select>
        </form>
    </div>

    <div id="dialogi_muokkaa" title="Muokkaa asiakastietoja">
    <form id='muokkauslomake'>
            <input type='hidden' name='tallenna' />
			<input type='text' id='id_muokkaus' name='mid' placeholder='ID'>
			<input type='text' id='tunnus_muokkaus' name='mtunnus' placeholder='Tunnus'>
			<input type='password' id='salasana_muokkaus' name='msalasana' placeholder='Salasana'> 
            <input type='text' id='nimi_muokkaus' name='mnimi' placeholder='Nimi'>
            <input type='text' id='osoite_muokkaus' name='mosoite' placeholder='Osoite'>
            <input type='text' id='postinro_muokkaus' name='mpostinro' placeholder='Postinumero'>
            <input type='text' id='postitmp_muokkaus' name='mpostitmp' placeholder='Postitoimipaikka'>
            <select id='asty_muokkaus' name='masty'>
                <option value="0">Käyttäjä</option>
				<option value="1">Admin</option>
            </select>
        </form>
    </div>
	
	<body>

</body>
</html>