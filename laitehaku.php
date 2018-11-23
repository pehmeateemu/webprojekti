<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
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
	
    <title>Laitteiden käsittely</title>
	
	<script>
		$(function(){

			
			$("#hae").button();
			$("#lisaa").button();
			$("#poista").button();
		
			$("#hae").click(function(){
				hae_laitteet();
			});
			
			$("#lisaa").click(function(){
				$("#dialogi_lisaa").dialog("open");
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
                                    console.log("Lisäyslauseke: " + lisayslauseke);
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
		});
		
		function hae_laitteet()
		{
			$("#laitteet").load("http://localhost:8081/pohjia/php/laiteHandler.php?hae=laite", function(){
				$(".poistaButton").button();	// Pakko laittaa tänne, koska poista-buttoneita ei ole selaimessa ennenkuin data on haettu
			});
		}
		
		function poista_laite(avain)
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
	</script>
</head>
<body>	
	
	<p>Anna hakuehdot</p>
	<form>
		<table>
		<tr><th>Nimi</th><th>Merkki</th><th>Malli</th></tr>
		<tr>
		<td><input type="text" name="nimi" /> </td>
		<td><input type="text" name="merkki" /> </td>
		<td><input type="text" name="malli" /> </td></tr>

		<tr><th>Sarjanumero</th><th>Kategoria</th><th>Omistaja</th></tr>
		<tr>
		<td><input type="text" name="sarjanumero" /> </td>
		<td><input type="text" name="kategoria" /> </td>
		<td><input type="text" name="omistaja" /> </td></tr>

		<tr><th>Osoite</th><th>Postinumero</th><th>Toimipaikka</th></tr>
		<tr>
		<td><input type="text" name="osoite" /> </td>
		<td><input type="text" name="postinro" /> </td>
		<td><input type="text" name="postitmp" /> </td></tr>
		</table>
		<br />
	</form>
	<button id="hae">Hae laitteet</button>
	<button id="lisaa">Lisää uusi laite</button>
	
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

</body>
</html>
