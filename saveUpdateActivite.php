
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Modifier activite")){
            header('Location:index.php');
        }else{
			if( !isset($_GET['activite']) || empty($_GET['activite']) ||
				!isset($_GET['groupe']) || empty($_GET['groupe']) ||
				!isset($_GET['valeur'])  || empty($_GET['valeur'])
				){
					header('Location:index.php');
			}else{
				$idactivite = $_GET['activite'];
				$idgroupe = $_GET['groupe'];
				$valeur = $_GET['valeur'];
				$exist = false;
				$select = $db->prepare("SELECT idgroupeactivite from groupeactivite where activite_idactivite=$idactivite and groupe_idgroupe=$idgroupe");
				$select->execute();
				if($select->fetch(PDO::FETCH_OBJ)){
					$exist = true;
				}else{
					$exist = false;
				}
				if($exist){
					if($valeur == "true"){
						$insert2 = $db->prepare("UPDATE groupeactivite SET lisible = 1 WHERE activite_idactivite = $idactivite AND groupe_idgroupe = $idgroupe");
						$insert2->execute();	
					}else{
						$insert3 = $db->prepare("UPDATE groupeactivite SET lisible = 0 WHERE activite_idactivite = $idactivite AND groupe_idgroupe = $idgroupe");
						$insert3->execute();	
					}			   		
				}else{
					$insert = $db->prepare("INSERT INTO groupeactivite values('', $idgroupe, $idactivite, true)");
					$insert->execute();	
				}
				$db=NULL;
			}
		}
	}else{
		header('Location:login.php');
    }
?>
	