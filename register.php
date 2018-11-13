<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Luo käyttäjä</title>
</head>
<body>	
	
	<p>Luo käyttäjätunnukset</p>
	<form action="register_handler.php" method="post">
		<table>
		<tr>
		<th><label for="tunnus">Tunnus</label></th>
		<th><input type="text" name="tunnus" /> </th>
		</tr>
		<tr>
		<th><label for="salasana">Salasana</label></th>
		<th><input type="text" name="salanana" /> </th>
		</tr>
		<tr>
		<th><label for="nimi">Nimi</label></th>
		<th><input type="text" name="nimi" /> </th>
		</tr>
		<tr>
		<th><label for="osoite">Osoite</label></th>
		<th><input type="text" name="osoite" /> </th>
		</tr>
		<tr>
		<th><label for="postinumero">Postinumero</label></th>
		<th><input type="text" name="postinumero" /> </th>
		</tr>
		<tr>
		<th><label for="postitmp">Postinumero</label></th>
		<th><input type="text" name="postitmp" /> </th>
		</tr>
		<tr><th/><th><input type="submit" name="login" value="Rekisteröidy" /></th></tr>
	</form>

</body>
</html>