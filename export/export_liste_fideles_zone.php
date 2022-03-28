<?php
	session_start();
	$annee = $_SESSION['annee'];
	$base = "paroisse".$annee;

	if(!isset($_GET['zone'])){


	}else{
		$zone = $_GET['zone'];
		$db = new PDO('mysql:host=localhost;dbname='.$base, 'paroisse', 'paroisse#2016');
		//Requete SQL
	  $liste = $db->prepare("SELECT  nom, prenom, sexe, datenaiss, lieunaiss,telephone, nomzone, statut, codefidele, pere, pere_vivant, mere, mere_vivant, domaine, diplome, statut_pro, employeur, village, etablissement, niveau, serie, departement, arrondissement.arrondissement as arrondissement , situation_matri, conjoint, nombre_enfant, religion_conjoint, annee_enregistrement, idfidele  FROM personne, fidele, zone, arrondissement,  departement WHERE idpersonne=personne_idpersonne AND zone.idzone = personne.zone_idzone AND departement.iddepartement = arrondissement.departement_iddepartement AND arrondissement.idarrondissement = personne.arrondissement AND arrondissement.lisible = 1 AND departement.lisible=1 AND personne.lisible=1 AND fidele.lisible=1 AND idzone =$zone ORDER BY nom");
	    $liste->execute();

	    $baptises = $db->prepare("SELECT  datebaptise, lieu_baptise, fidele_idfidele FROM bapteme WHERE lisible = 1");
	    $baptises->execute();
	    
	    $id_B = array();
	    $date_B = array();
	    $lieu_B = array();

	  while($x=$baptises->fetch(PDO::FETCH_OBJ)){
	    	array_push($id_B, $x->fidele_idfidele);
	    	array_push($date_B, $x->datebaptise);
	    	array_push($lieu_B, $x->lieu_baptise);
	    }

	    $id_C = array();
	    $date_C = array();
	    $lieu_C = array();
	   $confirm = $db->prepare("SELECT  date_confirmation, lieu_confirmation, fidele_idfidele  FROM confirmation WHERE lisible = 1");
	    $confirm->execute();
	    
	    while($x=$confirm->fetch(PDO::FETCH_OBJ)){
	    	
	    	array_push($id_C, $x->fidele_idfidele);
	    	array_push($date_C, $x->date_confirmation);
	    	array_push($lieu_C, $x->lieu_confirmation);
	    }

	unset($db);

	/**
	 * PHPExcel
	 *
	 * Copyright (C) 2006 - 2014 PHPExcel
	 *
	 * This library is free software; you can redistribute it and/or
	 * modify it under the terms of the GNU Lesser General Public
	 * License as published by the Free Software Foundation; either
	 * version 2.1 of the License, or (at your option) any later version.
	 *
	 * This library is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	 * Lesser General Public License for more details.
	 *
	 * You should have received a copy of the GNU Lesser General Public
	 * License along with this library; if not, write to the Free Software
	 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
	 *
	 * @category   PHPExcel
	 * @package    PHPExcel
	 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
	 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
	 * @version    1.8.0, 2014-03-02
	 */

	/** Error reporting */
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');

	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test result file");


	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A1', 'Code')
	            ->setCellValue('B1', 'Nom et prénoms')
	            ->setCellValue('C1', 'Sexe')
	            ->setCellValue('D1', 'Date de naissance')
				->setCellValue('E1', 'Lieu de naissance')
				->setCellValue('F1', 'Nom du père')
				->setCellValue('G1', 'Père vivant?')
				->setCellValue('H1', 'Nom de la mère')			
				->setCellValue('I1', 'Mère vivante?')
				->setCellValue('J1', 'Dernier diplôme')
				->setCellValue('K1', 'Domaine d\'études')
				->setCellValue('L1', 'Activité menée')
				->setCellValue('M1', 'Employeur')
				->setCellValue('N1', 'Etablissement fréquentée (Pour élève et étudiant)')
				->setCellValue('O1', 'Classe ou niveau d\'études')
				->setCellValue('P1', 'Serie ou filière')
				->setCellValue('Q1', 'Quartier de résidence')
				->setCellValue('R1', 'Zone de résidence')
				->setCellValue('S1', 'Téléphone')
				->setCellValue('T1', 'Village d\'origine')
				->setCellValue('U1', 'Arrondissement d\'origine')
				->setCellValue('V1', 'Département d\'origine')
				->setCellValue('W1', 'Situation matrimoniale')
				->setCellValue('X1', 'Réligion conjoint')
				->setCellValue('Y1', 'Nombre d\'enfants')
				->setCellValue('Z1', 'Année 1ère inscription en paroisse')
				->setCellValue('AA1', 'Statut paroissial')
				->setCellValue('AB1', 'Baptisé le')
				->setCellValue('AC1', 'Baptisé à')
				->setCellValue('AD1', 'Confirmé le')
				->setCellValue('AE1', 'Confirmé à')
				->setCellValue('AF1', 'Membre commission');


	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);

	$bordures = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
		

		$n = 2;
		while($data = $liste->fetch(PDO::FETCH_OBJ)){
			set_time_limit(30);
			$pere_vivant = ($data->pere_vivant? "OUI":"NON");
			$mere_vivante = ($data->mere_vivant? "OUI":"NON");
			$date_bapteme ="-";
			$lieu_bapteme = "-";
			$date_confirm = "-";
			$lieu_confirm = "-";

			if(in_array($data->idfidele, $id_B)){

				$i = array_search($data->idfidele, $id_B);			
				$date_bapteme = date_format(date_create($date_B[$i]), 'd/m/Y');
				$lieu_bapteme = $lieu_B[$i];
			}
			if(in_array($data->idfidele, $id_C)){

				$j = array_search($data->idfidele, $id_C);			
				$date_confirm = date_format(date_create($date_C[$j]), 'd/m/Y');
				$lieu_confirm = $lieu_C[$j];
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$n.':AF'.$n)->applyFromArray($bordures);

			if($n%2 == 0){

				$objPHPExcel->getActiveSheet()->getStyle('A'.$n.':AF'.$n)->applyFromArray(
			array(
				
				'fill' => array(
		 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
		  			'rotation'   => 90,
		 			'startcolor' => array(
		 				'argb' => 'FFA0A0A0'
		 			),
		 			'endcolor'   => array(
		 				'argb' => 'FFFFFFFF'
		 			)
		 		)
			)
	);
			}
			$date_naiss = date_format(date_create($data->datenaiss), 'd/m/Y');
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$n, $data->codefidele)
	            ->setCellValue('B'.$n, $data->nom.' '.$data->prenom)
	            ->setCellValue('C'.$n, $data->sexe)
	            ->setCellValue('D'.$n, date_format(date_create($data->datenaiss), 'd/m/Y'))
				->setCellValue('E'.$n, $data->lieunaiss)
				->setCellValue('F'.$n, $data->pere)
				->setCellValue('G'.$n, $pere_vivant)
				->setCellValue('H'.$n, $data->mere)			
				->setCellValue('I'.$n, $mere_vivante)
				->setCellValue('J'.$n, $data->diplome)
				->setCellValue('K'.$n, $data->domaine)
				->setCellValue('L'.$n, $data->statut_pro)
				->setCellValue('M'.$n, $data->employeur)
				->setCellValue('N'.$n, $data->etablissement)
				->setCellValue('O'.$n, $data->niveau)
				->setCellValue('P'.$n, $data->serie)
				->setCellValue('Q'.$n, '-')
				->setCellValue('R'.$n, $data->nomzone)
				->setCellValue('S'.$n, $data->telephone)
				->setCellValue('T'.$n, $data->village)
				->setCellValue('U'.$n, $data->arrondissement)
				->setCellValue('V'.$n, $data->departement)
				->setCellValue('W'.$n, $data->situation_matri)
				->setCellValue('X'.$n, $data->religion_conjoint)
				->setCellValue('Y'.$n, $data->nombre_enfant)
				->setCellValue('Z'.$n, $data->annee_enregistrement)
				->setCellValue('AA'.$n, $data->statut)
				->setCellValue('AB'.$n, $date_bapteme)
				->setCellValue('AC'.$n, $lieu_bapteme)
				->setCellValue('AD'.$n, $date_confirm)
				->setCellValue('AE'.$n, $lieu_confirm)
				->setCellValue('AF'.$n, '-');

			$n++;
		}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('liste_fideles');

	// Set thick brown border outline around "Total"

	//$objPHPExcel->getActiveSheet()->getStyle('A1:AF200')->applyFromArray($bordures);
	$objPHPExcel->getActiveSheet()->getStyle('A1:AF1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:AF1')->applyFromArray($bordures);

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	//$objPHPExcel->setActiveSheetIndex(0);

		
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="liste_fideles_par_sexe"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
}
?>
