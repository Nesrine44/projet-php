<script src="text.js"></script>
<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher activite")){

            header('Location:index.php');

        }

}else{
    header('Location:login.php');
}
?>


    <section class="wrapper">        
        
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                    <li> <i class="material-icons text-primary">school</i><a href="#" class="col-blue"> Conseils des Anciens</a></li>
                    <li> <i class="material-icons text-primary">save</i><a href="#" class="col-blue">Enregistrer un conseil</a></li>
                </ol>
            </div>
        </div>
         <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                            
                         Enregistrement d'un conseil d'anciens
                            
                            
                    </div>

                    <div class="body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="form-addConseil" method="POST" enctype="multipart/form-data" action="ajoutFideleConseil.php">

                                  
                        
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h2 class="card-inside-title">Type de conseil</h2>
                                <select class="form-control " name="typeconseil" required>
                                    <option disabled selected>Choisir un type de conseil</option>
                                    <option value="conseil mensuel" id="conseilmensuel"> Mensuel</option>
                                    <option value="conseil extraordinaire" id="conseilextraordinaire"> Extraordinaire</option>
                                    <option value="conseil elargi" id="conseilelargi"> Elargi</option>
                                    <option value="consistoire" id="consistoire">Consistoire</option>
                                    <option value="synode regional" id="sinoderegionale">Synode Régional</option>
                                    <option value="synode national" id="sinoderegionale">Synode National</option>
                                </select>
                            </div>
                        

                        
                       
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                             <h2 class="card-inside-title">Date d'enregistrement</h2>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons text-primary">event</i> 
                                    </span>
                                    
                                    <div class="form-line">
                                        <input type="text" class="form-control datepicker" id="cdateEnregistrement" type="text" name="date"  
                                        pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="Choisir la date: YYYY-MM-DD">
                                    </div>
                                </div>
                            </div>
                        </div>
                               

                                <!-- div -->

                                <h2 class="card-inside-title">Résolution</h2>
                                <div class="row clearfix">
                                    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                                        <div class="input-group">
                                             <textarea class="form-control textarea" id="editor1" name="rapport"> </textarea>
                                            
                                        </div>
                                    </div>
                                </div>
                               
            



                                <!-- div -->
                                <br>
                                <hr>

                                
                                 
                                <div style="text-align:center "><h4><b>  Fiche de presence</b></h4></div>


                                <div id="cochetou" style="text-align:right;width:82%" class="checkboxes">
                                    <input id="checkAll"  onclick="CocheTout(this)" type="checkbox"  class="filled-in chk-col-teal">
                                    <label class="label_check" for="checkAll" id="modifiertext">Cochez tous </label>
                               </div>

                                <div class="body">
                                        <div class="table-responsive">        
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">
                                                <thead>
                                                    <tr>
                                                        <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                                        <th><i class="material-icons iconposition">person</i> Nom et prénom</th>
                                                        <th><i class="material-icons iconposition">fullscreen</i> Choix</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                                        <th><i class="material-icons iconposition">person</i> Nom et prénom</th>
                                                        <th><i class="material-icons iconposition">fullscreen</i> Choix</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                <?php
                                            // require_once("includes/connexionbd.php");
                                                $req3 = "SELECT nom, prenom, idfidele FROM personne as pers, fidele as fil WHERE pers.lisible=1 AND fil.lisible=1 AND idpersonne=personne_idpersonne AND statut='ancien' ;";
                                                $result=$db->query($req3);
                                                $n = 0;
                                                while($identi=$result->fetch(PDO::FETCH_ASSOC)){ 
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ++$n; ?></td>
                                                        <td><?php echo($identi['nom'].' '.$identi['prenom']);?></td>
                                                        <td>
                                                            <div class="checkboxes">
                                                                    
                                                                    <input name="choix[]" class="magazine" id="checkbox-01<?php echo $n;?>"                                                 
                                                                    value="<?php echo($identi['idfidele']);?>" type="checkbox" />
                                                                    <label class="label_check" for="checkbox-01<?php echo $n;?>"></label>
                                                                    
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                
                                                ?>
                                                </tbody>

                                            </table>
                                            <?php
                                            $db=NULL;
                                            ?>
                                            <div class="form-group">
                                                <div class="col-lg-offset-5 col-lg-10">
                                                <a class="btn btn-warning annuler" href="groupeAnciens.php">Annuler</a>
                                                <button class="btn btn-primary" name="submit" type="submit">Enregistrer</button>
                                                
                                                
                                        </form>
                                    </div>
                                    

                                </div>
                            </section>
                        </div>
                    </div>
                
                                        
                                            
                                                    <input type="checkbox"  value=" "  id="md_checkbox_0115145" class="filled-in chk-col-teal" checked />
                                                    <label for="m_checkbox_0115145">tgiuyfiuyi</label>
                                            
                            
                                
                           
   
                                    





    </section>
<script type="text/javascript">

            $('#chargement').hide();

            $('#checkAll').click(function() {
                // on cherche les checkbox à l'intérieur de l'id  'magazine'
                //var magazines = $("#magazine").find(':checkbox');
                var test='Cocher Tous';
                var test1='Decocher Tous';
                if(this.checked){ // si 'checkAll' est coché
                    $(":checkbox").attr('checked', true);
                    $('#modifiertext').html(test1);
                    //magazines.prop('checked', true);
                }else{ // si on décoche 'checkAll'
                    $(":checkbox").attr('checked', false);
                    $('#modifiertext').html(test);
                    //magazines.prop('checked', false);
                }
            });

            $('.annuler').on('click', function(e){

                    e.preventDefault();
                    var $link = $(this);
                    target = $link.attr('href');
                    if(window.confirm("Voulez-vous vraiment annuler?")){

                        $('#main-content').load(target);
                    }
                    
                });      

            $('#form-addConseil').on('submit', function(e){

                              e.preventDefault();
                                var $form = $(this);
                                $form.find('button').text('Traitement');
                             
                                url = $form.attr('action');

                               
                $.post(url, $form.serializeArray())
 
                    .done(function(data, text, jqxhr){  
                        
                        alert('Conseil enrégistré avec succès!');
                        $('#main-content').load('enregistrerUnConseil.php');
                        
                    })
                    .fail(function(jqxhr){
                        alert(jqxhr.responseText);
                    })
                    .always(function(){
                        $form.find('button').text('Enregistrer');
                    });
                                
            });

              CKEDITOR.replace('editor1');
                //bootstrap WYSIHTML5 - text editor
              //$(".textarea").wysihtml5();

             $(".datepicker" ).datepicker({});
             $(".tableau_dynamique").DataTable();
	         $('.loader').hide();
        </script>
 

 