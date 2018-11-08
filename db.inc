<?php

	try {
		$conn = new PDO("mysql:host=sql204.epizy.com;dbname=epiz_22963281_webprojekti", "epiz_22963281_webprojekti", "puveskissi666");
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected successfully";
		}
	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
?>