<?php
session_start();

if(isset($_SESSION['login'])){
    $idUser = $_SESSION['login'];
    require_once('includes/connexionbd.php');
    require_once('includes/function_role.php');

    if(!has_Droit($idUser, "Enregistrer contribution")){
        header('Location:index.php');
    }else{
        $saintescenes=$db->prepare("SELECT idsaintescene, mois, annee
                            FROM saintescene
                            WHERE lisible=1
                            AND valide=0");
        $saintescenes->execute();

        $contributions = $db->prepare("SELECT * from contribution where lisible = 1");
        $contributions->execute();

        $mois = date('m');
        if($mois < 10){
        	 $mois = str_replace(0, "", $mois);
        }
    }
}else{
    header('Location:login.php');
}
?>

<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class="col-blue"><i class="material-icons">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                <li class="col-blue"><i class="material-icons"> assistant</i><a class="afficher Accueil col-blue" title="Afficher toutes les Sainte Cène" href="saintecene.php">Sainte Cène</a></li>
                <li class="col-blue"><i class="material-icons">assistant</i> Nouvelle Contribution</li>
            </ol>
        </div>
    </div>
    
    <div class="row card clearfix inputTopSpace ">
            <div class="col-lg-12">
                <section class="panel ">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-lg-3 col-sm-3 col-md-3 panel-heading ">    
                            <label for="Nom">Rechercher un fidèle : <span class="required">*</span></label>  
                            <div class="input-group">                          
                                <div class="form-line"> 
                                    <input class="form-control col-black" id="recherche" type="text" name="search" placeholder="Rechercher un fidèle" style="height: 30px;" />                                    
                                </div>
                            </div>
                            </div>
                            <div class="panel-body">
                            <div id="result"></div>
                            <div>
                                <button class="btn btn-primary" onclick="onShow()" id="but1">Ajouter un type</button>
                                <form method="post" id="form1">
                                    <input  type="text" name="nameContribu" required>
                                    <input type="submit" name="Sauver" value="Sauver">
                                    <input type="button"  onclick="onAnnuler()" value="Annuller">
                                </form>
                            </div>





                            <br>
                              <div class="form">
                                <form class="form-validate form-horizontal" id="form-addContribution" method="POST" action="saveContributionSainteCene.php">
                              <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row clearfix inputTopSpace">           
                                      <!-- fideles <div class="col-md-10 col-lg-offset-1">-->
                                        <div class="col-md-6">
                                            <label for="Nom"> <span class="required"></span></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">lock</i>
                                                    </span>
                                                    <div class="form-line">
                                                      <input class="form-control" id="fidele" type="text" name="fidele" value="" required disabled/>
                                                       <input type="hidden" id="idfidele"; name="idfidele"/>
                                                    </div>
                                                </div>
                                            </div>          
                                      <!-- fideles  <div class="col-md-10 col-lg-offset-1">-->
                                        <div class="col-md-6">
                                            <label for="Nom"> <span class="required"></span></label>
                                             <select class="form-control" name="typesaintecene" id="cSaintecene" required>                                                    
                                                     <?php
                                                        while($liste = $saintescenes->fetch(PDO::FETCH_OBJ)){
                                                            if($mois==$liste->idsaintescene){
                                                    ?>
                                                               <option value="<?php echo $liste->idsaintescene; ?>" class="choixSainteC" selected>
                                                                    <?php echo $liste->mois.' '.$liste->annee; ?>
                                                                </option>

                                                    <?php            
                                                            }
                                                        }    
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br><br>

                                    <div class="row clearfix inputTopSpace" id="typecontribution">           
                                      <!-- fideles -->
                                      <?php  
                                                while($x=$contributions->fetch(PDO::FETCH_OBJ)){
                                            ?> 
                                        <div class="col-md-3" id="<?php echo $x->type; ?>">
                                            <label for="Nom"> <?php echo $x->type; ?><span class="required">*</span>
                                    <i onclick="onDelete(<?php echo $x->idcontribution; ?>)" class="material-icons col-red">delete</i></label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="material-icons">edite</i>
                                                    </span>
                                                    <div class="form-line">
                                                      <input class="form-control contribution" type="number" min="0" max="500000000" value="0" name="<?php echo $x->type; ?>">                                                  </div>
                                                </div>
                                            </div>
                                        <?php        
                                            }
                                         ?><br>
                                     <div class="form-group">
                                        <div class="col-lg-offset-5 col-lg-10">
                                            <a class="btn btn-warning annuler" href="saintecene.php">Annuler</a>
                                            <button class="btn btn-primary" name="submit" type="submit" id="submit" disabled>Enregistrer</button><span class="envoi_en_cours" style="margin-left: 10px; display: none;">Enregistrement en cours...</span>
                                        </div>
                                    </div>  
                                   </div>
                                   </div>

                                </div>  

                                </form>
                              </div>
                            </div>
                        </div>   
                   </div> 
                </section>
            </div>
        </div>



</section>














<script type="text/javascript">
    //window.location.reload();
    $('#chargement').hide();
    $('#form1').hide();
    $('#tab').hide();
    $('#t2').hide();

    function onDelete(arg){
    if(confirm("Etes vous vraiment sure de vouloir l'éffacer ? vous ne pourrez plus faire chemin retour !"))
    {

        console.log('delType.php?id='+arg);
        $.ajax({
            url: 'delType.php?id='+arg,
            type: 'GET',
            contentType: false,
            processData: false,
            data: arg,
            success: function(response) {
                //alert(response);
                $('#main-content').load("nouvelleContributionSainteCene.php");
               
            }
    });
    }
        
    }


    function onShow(){

        $('#but1').hide();
        $('#lister').hide();
        $('#form1').show();
    }
    function onAnnuler(){
        $('#form1').hide();
        $('#but1').show();
        $('#lister').show();

    }

    

    $('#form1').on('submit', function(e){ 
        e.preventDefault();
        var $param = $(this);
        var formdata = (window.FormData) ? new FormData($param[0]) : null;
        var data = (formdata !== null) ? formdata : $param.serialize();
        console.log(data);
        $.ajax({
            url: 'saveType.php',
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function(response) {
                    $('#main-content').load("nouvelleContributionSainteCene.php");
               
            }
        });
        });
    $('#form-addContribution').on('submit', function (e) {
        // On empêche le navigateur de soumettre le formulaire
        e.preventDefault();
    
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
        
        $('.envoi_en_cours').show();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'json', // selon le retour attendu
            data: data,
            success: function(reponse) {

                if(reponse != ''){

                    alert(reponse);
                    $('.envoi_en_cours').hide();

                }else{                                

                            var idfidele = $('#idfidele').val();
                            var idsaintescene = $('#cSaintecene option:selected').val();
                        // var url2 = 'report/imprimer_ticket.php?file=ticket&param='+idfidele+'&param2='+idsaintescene;
                        var url2 = 'report/imprimer_param2.php?file=ticket&param='+idfidele+'&param2='+idsaintescene;
                                                
                        window.open(url2);   
                    $('.envoi_en_cours').show();                              
                    $('#main-content').load("nouvelleContributionSainteCene.php", function(){
                        $('.envoi_en_cours').hide();
                    });
                    
                }
            }
        });
    });

    $( ".datepicker" ).datepicker({});

    $('#recherche').keyup(function(){
        $('.loader').show();
        var txt = $(this).val();

        // alert(txt);
        if(txt != ''){
            $.ajax({
                url:"searchContributionContribution.php",
                method:"get",
                data:{search:txt},
                dataType:"text",
                success:function(data) {
                    $('#old_table').hide();
                    $('#result').html(data);
                    $('#submit').removeAttr('disabled');
                    //alert(txt);
                }
            });
        }else{
            // alert(txt);
            $('#result').html(txt);
            $('#old_table').show();
            $('.loader').hide();
        }
    });

    $('.afficher').on('click', function(af){
        af.preventDefault();
        $('.loader').show();
        var $b = $(this);
        url = $b.attr('href');
        $('#main-content').load(url, function(){
            $('.loader').hide();
        });
    });

    $('.annuler').on('click', function(e){
        e.preventDefault();
        var $link = $(this);
        target = $link.attr('href');
        if(window.confirm("Voulez-vous vraiment annuler?")){
            $('.loader').show();
            $('#main-content').load(target, function(){
                $('.loader').hide();
            });
        }
    });           

    $('.loader').hide();
</script>

