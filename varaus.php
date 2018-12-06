<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Sis‰‰nkirjautuminen</title>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	
    <title>Laitteiden k‰sittely</title>
	
	<script>
		$(function(){
			
			$("#varaa").click(function(){
				$("#dialogi_varaa").dialog("open");
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
									$.trim($("#lopetus_lisays").val()) === "" ) {
                                   
								   alert('Anna arvo kaikki kenttiin!');
                                    return false;
								}
								else if (Date.parse($("#aloitus_lisays").val()) >= Date.parse($("#lopetus_lisays").val())) {
									alert('Alkamispvm ei voi olla sama tai suurempi kuin loppumispvm!');
									return false;
								
                                } else {
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

	</script>
</head>
<body>
	
	
	
	<button id="varaa">Varaus</button>


	<div id="dialogi_varaa" title="Varaa laite">
	
        <form id="varauslomake">			
            <input type="hidden" name="teevaraus" />
			<input type="text" id="laite_lisays" name="laite" placeholder="Laite" value="j" readonly="readonly">
			<input type="password" id="asiakas_lisays" name="asiakas" placeholder="Asiakas" value="j" readonly="readonly"> 
			<input type="text" id="aloitus_lisays" name="aloitus" placeholder="Varauksen alkamispvm"> 
            <input type="text" id="lopetus_lisays" name="lopetus" placeholder="Varauksen loppumispvm">
            <select id="tila_lisays" name="tila" readonly="readonly">
                <option value="0"></option>
            </select>

			
        </form>
    </div>
	
</body>
</html>