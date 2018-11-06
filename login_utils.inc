<?php
	
	function check_session()
	{
		if ( !isset($_SESSION["login"]))
		{
			header("Location: login.php?virhe=2");
			exit();
		}
	}
?>