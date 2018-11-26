<?php
	//session_start();

	// Tarkistetaan, onko käyttäjä jo kirjautunut järjestelmään, jos ei heitetään login-sivulle
	require_once("login_utils.inc");
	
	//check_session();
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
				$("#dialogi_muokkaa").dialog("open");	
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
										$.trim($("#tunnus_muokkaa").val()) === "" || 
										$.trim($("#salasana_muokkaa").val()) === "" || 
										$.trim($("#nimi_muokkaa").val()) === "" || 
										$.trim($("#osoite_muokkaa").val()) === "" || 
										$.trim($("#postinro_muokkaa").val()) === "" || 
										$.trim($("#postitmp_muokkaa").val()) === "" || 
										$.trim($("#asty_muokkaa").val()) === "")
									{
										alert('Anna arvo kaikki kenttiin!');
										return false; 
									} 

								else 
								{
                                    var muokkauslauseke = $("#muokkauslomake").serialize();
                                    console.log("muokkauslauseke: " + muokkauslauseke);
                                    //tallennaLaite(muokkauslauseke);       KAAAAAAAAAIPAAAAAAAAAA MUUUUUUUTIOOOOOOOOOSTAAAAAAAAAAAAA
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

			$("#dialogi_lisaa").dialog({
                    autoOpen: false,
                    buttons: [
                        {
                            text: "Lisää",
                            click: function() {
                                if ($.trim($("#tunnus_lisays").val()) === "" || $.trim($("#salasana_lisays").val()) === "" || $.trim($("#nimi_lisays").val()) === "" || $.trim($("#osoite_lisays").val()) === "" || $.trim($("#postinro_lisays").val()) === "" || $.trim($("#postitmp_lisays").val()) === "" || $.trim($("#asty_lisays").val()) === "") {
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
			$("#asiakkaat").load("http://localhost:8081/pohjia/php/asiakasHandler.php?hae=asiakas", function(){
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

        function muokkaa_asiakas(avain)
		{
            $.get(
			"http://localhost:8081/pohjia/php/laiteHandler.php?haeMuutokseen=" + avain
			).done(function (data, textStatus, jqXHR) {
					if (data != ""){
                        console.log("Jotain löytyy");
                    }else
                    {
                        console.log("Ja data oli niin tyhjää. Niin tyhjää");
                    }
                    if(data["tunnus"] != ""){
                        console.log("Tunnus ei tyhjä");
                    }else
                    {
                        console.log("Ja data oli niin tyhjää. Niin tyhjää");
                    }
                    if(data["salasana"] != ""){
                        console.log("salasana ei tyhjä");
                    }else
                    {
                        console.log("Ja data oli niin tyhjää. Niin tyhjää");
                    }
                    if(data["tunnus"] != null){
                        console.log("Tunnus ei tyhjä");
                    }else
                    {
                        console.log("Ja data oli niin tyhjää. Niin tyhjää");
                    }
                    if(data["tunnus"] == "undefined"){
                        console.log("Tunnus ei tyhjä");
                    }else
                    {
                        console.log("Ja data oli niin tyhjää. Niin tyhjää");
                    }


					console.log(avain, data);
                    
					document.getElementById("tunnus_muokkaa").value=data["tunnus"];
					document.getElementById("salasana_muokkaa").value=data["tunnus"];
					document.getElementById("nimi_muokkaa").value=data["tunnus"];
					document.getElementById("osoite_muokkaa").value=data["tunnus"];
					document.getElementById("postinro_muokkaa").value=data["tunnus"];
					document.getElementById("postitmp_muokkaa").value=data["tunnus"];
                    

					
					$("#dialogi_muokkaa").dialog("open");
				}).fail(function (jqXHR, textStatus, errorThrown) {
					console.log("muokkaaAsiakas: status=" + textStatus + ", " + errorThrown);
					
				});
            
		}
        function tallennaAsiakas(muokkauslauseke) 
		{
			$.post("http://localhost:8081/pohjia/php/laiteHandler.php?tallenna",
                muokkauslauseke
            ).done(function (data, textStatus, jqXHR) {
                hae_laitteet();
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
	
	<p>Anna hakuehdot</p>
	<form>
		NIMI
		<input type="text" name="nimi" /> 
		<select id="asty_avain" name="Asty_avain">
			<option value=""></option>
        </select>

		<br />
	</form>
	<button id="hae">Hae asiakkaat</button>
	<button id="lisaa">Lisää uusi asiakas</button>
    <button id="logout">Kirjaudu ulos</button>
	
	<div id="asiakkaat"></div>

    <div id="dialogi_lisaa" title="Lisää uusi asiakas">
        <form id="lisayslomake">			
                <input type="hidden" name="lisaa" />
			<input type="text" id="tunnus_lisays" name="tunnus" placeholder="Tunnus">
			<input type="password" id="salasana_lisays" name="salasana" placeholder="Salasana"> 
            <input type="text" id="nimi_lisays" name="nimi" placeholder="Nimi">
            <input type="text" id="osoite_lisays" name="osoite" placeholder="Osoite">
            <input type="text" id="postinro_lisays" name="postinro" placeholder="Postinumero">
            <input type="text" id="postitmp_lisays" name="postitmp" placeholder="Postitoimipaikka">
            <select id="asty_lisays" name="asty">
                <option value="1"></option>
            </select>
        </form>
    </div>

    <div id="dialogi_muokkaa" title="Muokkaa asiakastietoja">
    <form id='muokkauslomake'>
            <input type='hidden' name='tallenna' />
			<input type='text' id='tunnus_muokkaa' name='mtunnus' placeholder='Tunnus'>
			<input type='password' id='salasana_muokkaa' name='msalasana' placeholder='Salasana'> 
            <input type='text' id='nimi_muokkaa' name='mnimi' placeholder='Nimi'>
            <input type='text' id='osoite_muokkaa' name='mosoite' placeholder='Osoite'>
            <input type='text' id='postinro_muokkaa' name='mpostinro' placeholder='Postinumero'>
            <input type='text' id='postitmp_muokkaa' name='mpostitmp' placeholder='Postitoimipaikka'>
            <select id='asty_muokkaa' name='asty'>
                <option value='1'></option>
            </select>
        </form>
    </div>
	
	<body>

</body>
</html>