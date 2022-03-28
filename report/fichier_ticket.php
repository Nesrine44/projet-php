<?php
	
	session_start();

	if(isset($_SESSION['login'])){

		 if(isset($_SESSION['link'])){

		 	$file = $_SESSION['link']."pdf";
		 	$idLogin = $_SESSION['login'];
		 	
		 		//require_once("../includes/header.php");
		 	
		 }else{
		 	header('Location:index.php');
		 }

	}else{

		header('Location:login.php');
	}
?>

	<script src="../js/jquery.js"></script>
	<script src="../js/printThis/printThis.js"></script>



	<object ID="PDF" onload="imprimer()" style="width:100%; height:100%;" data="ticket.pdf" type="text/html" codetype="application/pdf" ></object>





<script type="text/javascript">
  		function imprimer(){
  			setTimeOut(function(){
  			alert("dd");
  		},2000);
  		}
</script>
  
    
