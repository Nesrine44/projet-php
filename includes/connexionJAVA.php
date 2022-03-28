

<?php
	
	$annee = $_SESSION['annee'];

	$base = "paroise"; //paroisse".$annee;
	
	//var_dump($base);

  $db = "jdbc:mysql://localhost/".$base."?user=church1&password=church";
?>
