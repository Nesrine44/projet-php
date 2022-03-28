<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');


        if(!has_Droit($idUser, "Lister malade")){
            header('Location:index.php');
        }else{


        }
    }
?>

<section class="wrapper">        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="icon_document_alt"></i>Santé</li>
                    <li><i class="fa fa-files-o"></i><a href="listeMalades.php" class="afficher">Liste malades</a></li> 
                    <li><i class="fa fa-files-o"></i>Communion des malades</li>                    
                    <li style="float: right;"> 
                        <a class="" href="report/imprimer.php?file=liste_fideles_malades" title="Imprimer la liste des malades" target="_blank"><i class="fa fa-print"></i> Imprimer</a>
                    </li>                    
                </ol>
            </div>
        </div>
</section>
<script type="text/javascript">
     $('.afficher').on('click', function(af){
        $('.loader').show();
        af.preventDefault();
        var $b = $(this);
        url = $b.attr('href');
        $('#main-content').load(url);
    });

     $('.loader').hide();
</script>