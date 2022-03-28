<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];

    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Inscrire a un groupe")){

        header('Location:index.php');

    }else{

        $output = "";
        $sql = null;

        if(!isset($_GET['search'])){

            echo 'Aucun resultat';

        }else{

            $txt = addslashes($_GET['search']);

            $sql = $db->prepare("SELECT nom, prenom, codeFidele, idfidele FROM fidele, personne where fidele.personne_idpersonne = personne.idpersonne and fidele.est_decede=0 and fidele.lisible=1 and personne.lisible = 1  AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 30");

            $sql->execute();

            if($sql){
                
                $output.='<table class="table  table-advance table-hover col-lg-10">';                

                $n = 0;
                while($row=$sql->fetch(PDO::FETCH_OBJ)){

                    $output.='

	    					<tr>
	    						
	    						<td>'.$row->codefidele.'</td>
	    						<td>'.$row->nom.' '.$row->prenom.'</td>	
	    						<td style="text-align: right;">
                                    <button  class="col-green choix" value="'.$row->idfidele.'"  type="button" style="border:none; background-color:white;">
                                        <i class="material-icons">check_box</i>
                                    </button> 
                                </td>
                                
	    					</tr>
	    				';
                }

                $output.='</table>';


                echo $output;


            }else{

                echo 'Data  Not Found';
            }

        }

    }
    //return json data
    //   echo json_encode($data);

}else{

    header('Location:login.php');
}

?>

<script>


   
    $('.choix').on('click', function(e) {

        e.preventDefault();        
        var idfidele = $(this).val();
        
        $.ajax({

            url: 'getFidele.php',
            data:'id='+idfidele,
            dataType:'json',
            success:function(json){
                $('#fidele').val(json.codefidele+': '+json.nom+' '+json.prenom);
                $('#idfidele').val(idfidele);  
                $('#idpersonne').val(json.idpersonne);
                $('#dateNaissFidele').val(json.datenaiss);
                $('#dateInscriptionFidele').val(json.dateInscription)          
            }

        });
        
        $('#recherche').val("");
        $('#result').html("");
    });

    $('.loader').hide();

</script>

