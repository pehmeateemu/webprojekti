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
		
		$("#kirjaudu").button();

		function log_in()
		{
			kirjaudu = $("#login").serialize();
			console.log(kirjaudu);
		    $.get(
                "http://localhost:8081/pohjia/php/login_handler.php?login", kirjaudu
            )
			.done(function (data, textStatus, jqXHR) {
				var tiedot = $.parseJSON(data);
				console.log(tiedot);
            })
			.fail(function (jqXHR, textStatus, errorThrown) {
                console.log("login: status=" + textStatus + ", " + errorThrown);
				console.log(kirjaudu);
            });
		}


	</script>
</head>
<body>	
	
	<p>Kirjaudu sisään järjestelmään</p>
	<form id="login" method="post">
		<table>
		<tr>
		<td><label for="tunnus">Tunnus</label></td>
		<td><input type="text" id="tunnus" name="tunnus" /></td> </tr>
		<tr>
		<td><label for="ss">Salasana</label></td>
		<td><input type="text" id="tunnus" name="ss" /> </td>
		<td><input type="submit" name="kirjaudu" value="Kirjaudu" onclick="log_in()"/></td></tr>	
	</form>

</body>
</html>