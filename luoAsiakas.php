<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Sisäänkirjautuminen</title>
</head>
<body>	
	
	<p>Kirjaudu sisään järjestelmään</p>
	<form action="login_handler.php" method="post">
		<table>
		<tr>
		<td><label for="nimi">Nimi</label></td>
		<td><input type="text" name="nimi"/></td> </tr>
		<tr>
		<td><label for="ss">Salasana</label></td>
		<td><input type="text" name="ss" /> </td>
		<td><input type="submit" name="login" value="Kirjaudu" /></td></tr>	
	</form>

</body>
</html>