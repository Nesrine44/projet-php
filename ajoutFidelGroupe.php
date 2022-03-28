
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
       require_once('includes/function_role.php');
       require_once('includes/connexionbd.php');
       	if(!has_Droit($idUser, "Inscrire a un groupe")){

            header('Location:index.php');

        }else{

	        function isAjax(){
	            return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	        }

	        if(isAjax()){
	       	
		       	if( !isset($_POST['idfidele']) || empty($_POST['idfidele']) ||
		       		!isset($_POST['idgroupe']) || empty($_POST['idgroupe'])
		       	 ){
		       		 header('500 Internal Server Error', true, 500);
		                    die('Veuillez selectionner un fidèle!');
		       	}else{
				   

				   $idfidele = $_POST['idfidele'];
				   
				   foreach ($_POST['idgroupe'] as $idgroupe) {
			        			
					   $selectF = $db->prepare("SELECT idfidelegroupe from fidelegroupe where fidele_idfidele=$idfidele and groupe_idgroupe=$idgroupe");
							   	$selectF->execute();

							   if($selectF->fetch(PDO::FETCH_OBJ)){

							   		$update = $db->prepare("UPDATE fidelegroupe set lisible = true WHERE fidele_idfidele = $idfidele AND groupe_idgroupe = $idgroupe");
							   		$update->execute();
							   		
							   }else{

							   		$date_inscription = date('Y-m-d');

							   		$insert = $db->prepare("INSERT INTO fidelegroupe values('',true, $idfidele, $idgroupe, '$date_inscription')");
						        	$insert->execute();					   		
							   }
					}
					
				}
			}else{
	                header('Location:index.php');
	        }

      }

	}else{
			header('Location:login.php');
    }
	
?>
	