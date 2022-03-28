<?php
	
	session_start();

	if(isset($_SESSION['login'])){

		 if(isset($_SESSION['link'])){

		 	$file = $_SESSION['link'];

		 	$file_print = $file.".pdf";
			$printer_name = "POS-58"; 
			$handle = printer_open($printer_name);
			printer_start_doc($handle, c);
			printer_start_page($handle);
			$font = printer_create_font("Arial", 100, 100, 400, false, false, false, 0);
			printer_select_font($handle, $font);
			printer_draw_text($handle, $file_print, 100, 400);
			printer_delete_font($font);
			printer_end_page($handle);
			printer_end_doc($handle);
			printer_close($handle);
		 }else{
		 	header('Location:index.php');
		 }

	}else{

		header('Location:login.php');
	}
?>

<script type="text/javascript">
	//window.print();
	//window.close();
</script>