<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Lister groupe")){

            header('Location:index.php');

        }else{


            $insert1 = "SELECT * FROM groupe where lisible=1 AND typegroupe = 'anciens' ORDER BY nomgroupe ASC";
            $res=$db->query($insert1);

        }

    }else{
        header('Location:login.php');
    }

?>


    <section class="wrapper">
        
            <div class="row">
                        <div class="col-lg-12">
                            <ol class="breadcrumb">

                                <li> <i class="material-icons text-primary">home</i><a href="index.php" class="col-blue"> Accueil</a></li>
                                <li> <i class="material-icons text-primary">school</i><a href="#" class="col-blue"> Conseils des Anciens</a></li>
                                <li> <i class="material-icons text-primary">group</i><a href="#" class="col-blue">Groupes</a></li>

                                <li style="float: right;">
                                    <a class="col-blue" href="#" data-toggle="modal" data-target="#modalgroupe" title="Nouveau groupe"><i class="material-icons">plus_one</i>Nouveau groupe</a>
                                </li>

                            </ol>
                        </div>
                    </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="card">

                    <div class="header text-center h4">
                            
                         Liste de groupe de type "Ancien"
                        <!-- <button type="button" data-toggle="modal" data-target="#mymodal" class="btn btn-primary">Nouveau</button> -->
                            
                    </div>
                    <div class="body">
                                        <div class="table-responsive">        
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable tableau_dynamique">
                                                <thead>
                                                    <tr>
                                                        <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                                        <th><i class="material-icons iconposition">person</i> Nom</th>
                                                        <th><i class="material-icons iconposition">merge_type</i> Type</th>
                                                        <th><i class="material-icons iconposition">event</i>Date de création</th>
                                                        <th><i class="material-icons iconposition">settings</i> Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th><i class="material-icons iconposition">confirmation_number</i> Numéro</th>
                                                        <th><i class="material-icons iconposition">person</i> Nom</th>
                                                        <th><i class="material-icons iconposition">merge_type</i> Type</th>
                                                        <th><i class="material-icons iconposition">event</i>Date de création</th>
                                                        <th><i class="material-icons iconposition">settings</i> Action</th>
                                                    </tr>
                                                </tfoot>
                                            <tbody>    
                                                <?php
                                                $n=0;
                                                while($id=$res->fetch(PDO::FETCH_ASSOC)){  ?>
                                                        <tr>
                                                            <td><?php echo ++$n;?></td>
                                                            <td><?php echo $id['nomgroupe'];?></td>
                                                            <td><?php echo $id['typegroupe'];?></td>
                                                            <td><?php echo $id['datecreation'];?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <a class="btn btn-primary afficher" href="listeMembreGroupe.php?id=<?php echo($id['idgroupe']); ?>" title="Voir les membres"><i class="material-icons col-blue">loupe</i></a>
                                                                    <a class="btn btn-success afficher" href="modifiergroupe.php?param=<?php echo($id['idgroupe']); ?>" title="Modifier"><i class="material-icons col-green">border_color</i></a>
                                                                    <a class="btn btn-danger" href="supprimergroupe.php?id=<?php echo($id['idgroupe']); ?>" title="Supprimer"><i class="material-icons col-red">delete</i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                             ?>
                                            </tbody>
                                             <?php
                                                $db=NULL;
                                                ?>
                                        </table>

                                    </div>
                                </div>
                              </div>  
                            </section>
                        </div>
                    </div>

<div class="modal" id="modalgroupe">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4>Ajouter un groupe</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        <div class="modal-body">
            <form action="ajoutergroupeancien.php" method="POST" id="fom">
                <div class="form-group">
                    <label for="nomgroupe">Nom</label>
                    <input type="text" name="nomgroupe" class="form-control" placeholder="Entrez le nom du groupe">
                </div>
                <div class="form-group">
                    <label for="typegroupe">Type</label>
                    <select name="typegroupe" id="" class="form-control">
                        <option value="">Selectionnez le type de groupe</option>
                        <option value="anciens">Anciens</option>
                        <option value="CHORALE">Chorales</option>
                        <option value="MOUVEMENT">Mouvement</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="affiche btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        
    </div>
</div>
                    

  <script>

    $('#chargement').hide();
  
    $('.btn-danger').on('click', function(e){

        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('href');
        if(window.confirm('Voulez-vous supprimer cette zone?')){
            $.ajax(url, {

                success: function(){
                    $a.parents('tr').remove();
                },

                error: function(){

                    alert("Une erreur est survenue lors de la suppresion du malade");
                }
            });
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

    $(".tableau_dynamique").DataTable();
    $('.loader').hide();

    $('#fom').on('submit', function(e){
        $('.envoi_en_cours').show();
        e.preventDefault();
        var $param = $(this);
        var formdata = (window.FormData) ? new FormData($param[0]) : null;
        var data = (formdata !== null) ? formdata : $param.serialize();
        console.log(data);
        $.ajax({
            url: 'ajoutergroupeancien.php',
            type: 'POST',
            contentType: false,
            processData: false,
            data: data,
            success: function(response) {
                console.log(response);
               $('#modalgroupe').modal('hide');
               $('#main-content').load("groupeAnciens.php");

            }
        });
        });

</script>
