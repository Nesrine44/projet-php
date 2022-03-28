<?php
	session_start();

	$json = "";
	
   error_reporting(E_ALL); // Activer le rapport d'erreurs PHP

   $db_charset = "latin1"; /* mettre utf8 ou latin1 */
   $annee = $_SESSION['annee'];
   $db_server         = "localhost"; // Nom du serveur MySQL.  ex. mysql5-26.perso
   $db_server_remote = "151.236.62.132";
   $db_name           = "paroisse".$annee; // Nom de la base de données.  ex. mabase
   $db_username       = "paroisse"; // Nom de la base de données.  ex. mabase
   $db_password       = "paroisse#2016"; // Mot de passe de la base de données.

   $cmd_mysql = "C:\wamp\bin\mysql\mysql5.5.16\bin\mysqldump";

   $fichier = 'backup\\paroisse'.$annee.'_du_'.date('m-d-Y').'_a_'.date('H:m:s').'.sql';
   $fichier_remote = '/var/www/vhosts/system.eec-biyemassi.net/httpdocs/backup/paroisse'.$annee.'_du_'.date('m-d-Y').'_a_'.date('H:m:s').'.sql';
   $fichier=str_replace(':', '-', $fichier);
   $fichier_remote=str_replace(':', '-', $fichier_remote);

  // echo " Sauvegarde de la base $db_name par mysqldump dans le fichier $fichier\n";
   $commande = $cmd_mysql." --host=$db_server --user=$db_username --password=$db_password --default-character-set=$db_charset  $db_name > $fichier ";

   //$commande_remote = $cmd_mysql." --host=$db_server --user=$db_username --password=$db_password --default-character-set=$db_charset  $db_name > $fichier_remote ";

   try {
   		
   		$CR_exec = system($commande);
   		//$CR_exec_remote = system($commande_remote);
   		$path = "C:\\wamp\\www\\Church\\$fichier";
   		$dest = ".\\report\\backup";
   		move_uploaded_file($path, $dest);

   		
   		//$source = $fichier;
   		//$file = fopen("/var/www/vhosts/system.eec-biyemassi.net/httpdocs/backup/test.txt", 'a');

   		//$cible = fopen($file, "a");
   		//if(!copy($source, $cible)){

   		//	$json = $fichier;
   		//} 

   			echo $path;

   		$json = 'Opération effectuée avec succès!!!';

   } catch (Exception $e) {
   	
   		$json = "$e->getMessage()";

   }

   echo json_encode($json);
   
?>
	
	


