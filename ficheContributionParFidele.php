<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');

        if(!has_Droit($idUser, "Afficher contribution")){

            header('Location:index.php');

        }else{
            
            $select1 = "SELECT codeFidele FROM fidele WHERE lisible=1;";
            $res1=$db->query($select1);

        }
    }else{
        header('Location:login.php');
    }
?>


    <section class="wrapper">
    
        
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="index.php">Accueil</a></li>
                    <li><i class="icon_document_alt"></i>Finances</li>
                    <li><i class="fa fa-files-o"></i>Fiche Contribution</li>
                    <li><i class="fa fa-files-o"></i>Par Fidèle</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Voir les fiches de contribution d'un fidèle
                    </header>
                    <div class="panel-body">
                        <div class="form">
                            <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="traitementFicheParFidele.php">

                                <div class="form-group ">
                                    <label for="cstatut" class="control-label col-lg-2">Code du fidèle</label>
                                    <div class="col-lg-10">
                                        <select class="form-control " name="choixcode">
                                            <?php
                                            while($id=$res1->fetch(PDO::FETCH_ASSOC)){
                                                ?>
                                                <option value="<?php echo($id['codefidele']);?>"><?php echo($id['codefidele']);?></option>
                                            <?php
                                            }
                                            $db=NULL;
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-primary" name="submit" type="submit">Valider</button>
                                        <button class="btn btn-default" type="button">Annuler</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
<script type="text/javascript">
    $('.loader').hide();
</script>
