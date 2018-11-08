<?php
	session_start();

	// Tarkistetaan, onko käyttäjä jo kirjautunut järjestelmään, jos ei -> heitetään login-sivulle
	require_once("login_utils.inc");
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
		
			$("#hae").click(function(){
				hae_asiakkaat();
			});
			
			$("#lisaa").click(function(){
				$("#dialogi_lisaa").dialog("open");
			});
			
			$("#dialogi_lisaa").dialog({
                    autoOpen: false,
                    buttons: [
                        {
                            text: "Lisää",
                            click: function() {
                                if ($.trim($("#nimi_lisays").val()) === "" || $.trim($("#osoite_lisays").val()) === "" || $.trim($("#postinro_lisays").val()) === "" || $.trim($("#postitmp_lisays").val()) === "" || $.trim($("#asty_avain_lisays").val()) === "") {
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
                "http://localhost:8081/pohjia/php/asiakasHandler.php",
                lisayslauseke
            ).done(function (data, textStatus, jqXHR) {
                hae_asiakkaat();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("lisaaAsiakas: status=" + textStatus + ", " + errorThrown);
            });
        }

		function haeAsiakastyypit() {
            $.get(
                "http://localhost:8081/pohjia/php/asiakasHandler.php?hae_asiakastyyppi_json" 
            ).done(function (data, textStatus, jqXHR) {
                $.each(data, function (index, tyyppi) {

                    $('#asty')
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
	
	<div id="asiakkaat"></div>

    <div id="dialogi_lisaa" title="Lisää uusi asiakas">
        <form id="lisayslomake">			
                <input type="hidden" name="lisaa" />
			    <input type="text" id="tunnus" name="tunnus" placeholder="Tunnus">
			    <input type="password" id="salasana" name="salasana" placeholder="Salasana"> 
            <input type="text" id="nimi_lisays" name="nimi" placeholder="Nimi">
            <input type="text" id="osoite_lisays" name="osoite" placeholder="Osoite">
            <input type="text" id="postinro_lisays" name="postinro" placeholder="Postinumero">
            <input type="text" id="postitmp_lisays" name="postitmp" placeholder="Postitoimipaikka">
            <select id="asty_avain_lisays" name="asty_avain">
                <option value="1"></option>
            </select>
        </form>
    </div>
    
	
	<body>

</body>
</html>