

<?php
    session_start();
    if(isset($_SESSION['login'])){
       $idUser = $_SESSION['login'];
       $annee = $_SESSION['annee'];
        //require_once('layout.php');
       require_once('includes/connexionbd.php');
       require_once('includes/function_role.php');


        if(!has_Droit($idUser, "Liste des depenses")){
            header('Location:index.php');
        }else{

           
            //nombre de fidele a afficher par page
            $nbeParPage=100;
            //total de fideles enregistrés list
            $total = 0;

            //selection du nombre de fideles enregistrés
            $selectNombreFidele = $db->prepare("SELECT COUNT(id) AS nbretotalfidele FROM traveaux WHERE lisible=1");
            $selectNombreFidele->execute();
            
            while($idselectNombreFidele=$selectNombreFidele->fetch(PDO::FETCH_OBJ)){
                $total = $idselectNombreFidele->nbretotalfidele;
            }

            //calcul du nombre de pages
            $nbDePage = ceil($total/$nbeParPage);

            //navigation dans le paginator
            if(isset($_GET['p']) && !empty($_GET['p']) && ctype_digit($_GET['p'])==1){
                if($_GET['p'] > $nbDePage){
                    $pageCourante = $nbDePage;
                }else{
                    $pageCourante = $_GET['p'];
                }

            }else{
                $pageCourante = 1;
            }

            $premierElementDeLaPage = ($pageCourante - 1) * $nbeParPage;


            //selection des information sur les fideles
            $selectAllFidele= $db->prepare("SELECT *
                                            
                                        FROM
                                            traveaux
                                        WHERE lisible = 1
                                        ORDER BY id DESC");
            $selectAllFidele->execute();

        }

    }else{
        header('Location:login.php');
    }
?>

    <section class="wrapper">
         
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li class="col-blue"><i class="material-icons">home </i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li class="col-blue"><i class="material-icons">assistant </i> Sainte Cène</li>
                    <li class="col-blue"><i class="material-icons">assistant</i><a href="#" class="col-blue"> Liste des projets</a></li>


	                <li style="float: right;"> 
	                    <a class="" href="#" title="Imprimer la lla liste des projets" target="_blank"><i class="material-icons">print</i> Imprimer</a>
	                    <a class="" href="#" title="Imprimer la lla liste des projets" target="_blank"><i class="material-icons">print</i> CSV</a>
	                </li>
                   
                </ol>
            </div>
        </div>
        
            <div class="row clearfix card">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card">
                   

                        <div class="header text-center h4">
                              
                                <?php echo $total; ?> Traveaux enregistrés
                                 
                        </div>

                        <div class="body">
                            <div class="table-responsive" id="old_table">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">  
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons iconposition"></i>Nr.ID</th>
                                            <th><i class="material-icons iconposition"></i>Intitulé</th>
                                            <th><i class="material-icons iconposition"></i>Budget Total</th>
                                            <th><i class="material-icons iconposition"></i> Durée en
											Mois</th>
                                            <th><i class="material-icons iconposition"></i>Type</th>
                                            <th><i class="material-icons iconposition"></i>Personne en Charge</th>
                                            <th><i class="material-icons iconposition">settings</i>Action</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                        <?php
                                            $n=0;
                                            $reside = "";
                                            $total = 0;                                        
                                            while($liste=$selectAllFidele->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                        <tr>
                                            <td><?php 
                                           
 
                                            echo $liste->idp; ?></td>
                                            <td><?php echo $liste->intitule; ?></td>
                                            <td><?php echo $liste->budget; ?></td>
                                            <td>
                                               <?php echo $liste->duree; ?>
                                            </td>
                                            <td>
                                               <?php echo $liste->type; ?>
                                            </td>
                                            <td>
                                               <?php echo $liste->responsable; ?>
                                            </td>
                                            <td width="15%">
                                            <i onclick="onDelete(<?php echo $liste->id; ?>)" class="material-icons">delete</i>     
                                            </td>

                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        
                                    </tbody>
                                </table>
                                

                            </div>
                            <br>
                            
                        </div>
                </div>
            </div>                
                            

                       
                    

<script>


function onDelete(arg){
   if(confirm("Etes vous vraiment sure de vouloir l'éffacer ? vous ne pourrez plus faire chemin retour !"))
        {

            console.log('delProjet.php?id='+arg);
            $.ajax({
                url: 'delProjet.php?id='+arg,
                type: 'GET',
                contentType: false,
                processData: false,
                data: arg,
                success: function(response) {
                    $('#main-content').load("listeProjet.php");
                   
                }
        });
        }
}
                    
                     
                        $('.afficher').on('click', function(af){
                            $('.loader').show();
                            af.preventDefault();
                           var $b = $(this);
                            url = $b.attr('href');
                           $('#main-content').load(url, function(){
                                $('.loader').hide();
                           });
                        });

                        $('.item').on('click', function(i){

                            i.preventDefault();
                            $('#modifiertext').html('Chargement...');
                            var $i = $(this);                            
                            url = $i.attr('href');

                             $('#main-content').load(url);

                            $('#modifiertext').html('');
                        });

                        
                        $('.loader').hide();

                        $(".tableau_dynamique").DataTable();
                    </script>


    </section>
