<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Sisäänkirjautuminen</title>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	
    <title>Laitteiden käsittely</title>
	
	<script>
		$(function(){
			
			$("#lisaa").click(function(){
				$("#dialogi_lisaa").dialog("open");
			});

			$("#dialogi_lisaa").dialog({
                    autoOpen: false,
                    buttons: [
                        {
                            text: "Lisää",
                            click: function() {
                                if ($.trim($("#tunnus_lisays").val()) === "" ||
									$.trim($("#salasana_lisays").val()) === "" ||
									$.trim($("#nimi_lisays").val()) === "" ||
									$.trim($("#osoite_lisays").val()) === "" ||
									$.trim($("#postinro_lisays").val()) === "" ||
									$.trim($("#postitmp_lisays").val()) === "" ||
									$.trim($("#asty_lisays").val()) === "" ||
									$.trim($("#salasana_lisays").val()) != $.trim($("#salasana_tarkistus").val())) {
                                   
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
		function lisaaAsiakas(lisayslauseke) {
            $.post(
                "http://localhost:8081/pohjia/php/asiakasHandler.php?lisaa",
                lisayslauseke
            ).done(function (data, textStatus, jqXHR) {
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log("lisaaAsiakas: status=" + textStatus + ", " + errorThrown);
            });
        }


	</script>
</head>
<body>
	
	
	<button id="lisaa">Rekisteröidy</button>
	<p>Kirjaudu sisään järjestelmään</p>
	<form action="login_handler.php" id="login" method="post">
		<table>
		<tr>
		<td><label for="tunnus">Tunnus</label></td>
		<td><input type="text" name="tunnus" /></td> </tr>
		<tr>
		<td><label for="ss">Salasana</label></td>
		<td><input type="password" name="ss" /> </td>
		<td><input type="submit" name="kirjaudu" value="Kirjaudu"/></td></tr>	
		
	</form>



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
            <input type="hidden" id="asty_lisays" name="asty" value="1">
            </select>

			
        </form>
    </div>
	
</body>
</html>