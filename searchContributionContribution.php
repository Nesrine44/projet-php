<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];

    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Lister fidele")){

        header('Location:index.php');

    }else{

        $output = "";
        $sql = null;

        if(!isset($_GET['search'])){

            echo 'Aucun resultat';

        }else{

            $txt = addslashes($_GET['search']);
            $sql = $db->prepare("SELECT nom, prenom, codeFidele, idfidele FROM fidele, personne where fidele.personne_idpersonne = personne.idpersonne and fidele.lisible=1 and personne.lisible = 1  AND (nom LIKE '%".$txt."%' OR prenom LIKE '%".$txt."%' OR CONCAT(nom, ' ', prenom) LIKE '%".$txt."%' OR codefidele LIKE '%".$txt."%') ORDER BY nom ASC LIMIT 0, 10");
            $sql->execute();

            if($sql){
                $output.='<div class="table_responsive"> <table class="table table-bordered table-striped table-hover col-lg-12">';
                $n = 0;
                while($row=$sql->fetch(PDO::FETCH_OBJ)){
                    $output.='
                        <tr>
                            
                            <td>'.$row->codefidele.'</td>
                            <td>'.$row->nom.' '.$row->prenom.'</td>	
                            <td style="text-align: right;">
                                <button  class="col-green choix" value="'.$row->idfidele.'"  type="button" style="border:0px solid transparent; background-color:white;">
                                    <i class="material-icons">check_box</i>
                                </button> 
                            </td>
                            
                        </tr>
                    ';
                }
                $output.='</table></div>';
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
    $('.afficher').on('click', function(af){
        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');
        $('#main-content').load(url);
    });

    $('.btn-danger').on('click', function(e){
        e.preventDefault();
        var $a = $(this);
        var url = $a.attr('href');
        if(window.confirm('Voulez-vous supprimer ce fidèle?')){
            $.ajax(url, {
                success: function(){
                    $a.parents('tr').remove();
                },
                error: function(){
                    alert("Une erreur est survenue lors de la suppresion du fidèle");
                }
            });
        }
    });

    $('.ordre_rangement').on('click', function(e){
        e.preventDefault();
        var z = $(this);
        target = z.attr('href');
        $('#main-content').load(target);
    });

    $('.listeSexe').on('click', function(e){
        e.preventDefault();
        var z = $(this);
        target = z.attr('href');
        $('#main-content').load(target);
    });

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
            }
        });
        
        $('#recherche').val("");
        $('#result').html("");
    });
    $('.loader').hide();
</script>

