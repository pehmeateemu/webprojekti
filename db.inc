<?php

	try {
		$conn = new PDO("mysql:host=localhost:3308;dbname=webprojekti", "root", "");
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected successfully";
		}
	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
?>