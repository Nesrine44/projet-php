<?php
session_start();
if(isset($_SESSION['login'])){
	
	if(isset($_GET['file']) && $_GET['param'] && $_GET['param2']){

		$file = $_GET['file'];
		$param = $_GET['param'];
		$param2 = $_GET['param2'];

		define('JAVA_INC_URL', 'http://localhost:8081/JavaBridge/java/Java.inc');
					
					require_once(JAVA_INC_URL);
					require_once('../includes/connexionJAVA.php');
					$System = new Java('java.lang.System');
					$class = new JavaClass("java.lang.Class");
					$class->forName("com.mysql.jdbc.Driver");
					$driverManager = new JavaClass("java.sql.DriverManager");
					$conn = $driverManager->getConnection($db);
					//compilation
					$compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
					$viewer = new JavaClass("net.sf.jasperreports.view.JasperViewer");
					$report = $compileManager->compileReport("church.kamer-center.net/report/".$file.".jrxml");
					//fill
					$fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
					$params = new Java("java.util.HashMap");

					$params->put("param",  $param);
					$params->put("param2",  $param2);
					$emptyDataSource = new Java("net.sf.jasperreports.engine.JREmptyDataSource");

					$jasperPrint = $fillManager->fillReport($report, $params, $conn);

					$exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
					$outputPath = realpath(".")."/".$file.".pdf";
					$exportManager->exportReportToPdffile($jasperPrint, $outputPath);

					//$_SESSION['link'] = $file;
					//header('Location: fichier_ticket.php');

					$file_print = NULL;
					$file_print = fopen($file.".pdf", "r");
					if($file_print == NULL) exit(-1);
					 
					$file_content = fread($file_print, filesize($file.".pdf"));

					$handle = printer_open();
					printer_write($handle, $file_content);
					printer_close($handle);



	}else{

		header('Location:index.php');
	}
}else{

	header('Location:../login.php');
}

?>