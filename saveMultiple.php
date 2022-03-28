
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Inscrire a un groupe")){

            header('Location:index.php');

        }else{
       	
	       	if( !isset($_GET['fidele']) || empty($_GET['fidele']) ||
	       		!isset($_GET['groupe']) || empty($_GET['groupe']) ||
	       		!isset($_GET['valeur'])  || empty($_GET['valeur'])
	       	 ){
	       		 header('Location:index.php');
	       		
	       	}else{

			   $idfidele = $_GET['fidele'];
			   $idgroupe = $_GET['groupe'];
			   $valeur = $_GET['valeur'];
			   $exist = false;

			   $select = $db->prepare("SELECT idfidelegroupe from fidelegroupe where fidele_idfidele=$idfidele and groupe_idgroupe=$idgroupe");
			   $select->execute();

			   if($select->fetch(PDO::FETCH_OBJ)){
			   		$exist = true;
			   }else{

			   		$exist = false;
			   }

			   if($exist){

			   		if($valeur == "true"){

				   		$insert2 = $db->prepare("UPDATE fidelegroupe SET lisible = 1 WHERE fidele_idfidele = $idfidele AND groupe_idgroupe = $idgroupe");
				   		$insert2->execute();	

			   		}else{

				   		$insert3 = $db->prepare("UPDATE fidelegroupe SET lisible = 0 WHERE fidele_idfidele = $idfidele AND groupe_idgroupe = $idgroupe");
				   		$insert3->execute();	
			   		}			   		

			   }else{

			   		$date_inscription = date('Y-m-d');

			   		$insert = $db->prepare("INSERT INTO fidelegroupe values('',true, $idfidele, $idgroupe, '$date_inscription')");
			   		$insert->execute();	

			   }
			  
				$db=NULL;
				
			}

		}
		
	}else{
			header('Location:login.php');
    }
	
?>
	