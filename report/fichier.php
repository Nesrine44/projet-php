<?php
	
	session_start();

	if(isset($_SESSION['login'])){

		 if(isset($_SESSION['link'])){

		 	$file = $_SESSION['link'];

		 	echo'
		 		<object style="width:100%; height:100%;" data="'.$file.'.pdf" type="text/html" codetype="application/pdf" ></object>
		 	';

		 }else{
		 	header('Location:index.php');
		 }

	}else{

		header('Location:login.php');
	}
?>
