<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Sisäänkirjautuminen</title>
</head>
<body>	
	
	<p>Kirjaudu sisään järjestelmään</p>
	<form action="login_handler.php" method="post">
		<label for="tunnus">Tunnus</label>
		<input type="text" name="tunnus" /> 
		<label for="ss">Tunnus</label>
		<input type="text" name="ss" /> 
		
		<input type="submit" name="login" value="Kirjaudu" />
	</form>

</body>
</html>