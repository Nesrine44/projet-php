<?php
    session_start();

    if(isset($_SESSION['login'])){
        $idUser = $_SESSION['login'];
        require_once('includes/connexionbd.php');
        require_once('includes/function_role.php');
        
        $json = array();

        $Activites = $db->prepare("SELECT idactivite as id, nomactivite as title, datedebut as start, datefin as end FROM activite where lisible=1");
        $Activites->execute();
        $i = 0;
        while($x = $Activites->fetch(PDO::FETCH_OBJ)){

        	//$json[$i]= $x;
        	$json[$i] = array('id'=>$x->id, 'title'=>$x->title, 'start'=>$x->start, 'end'=>$x->end, 'url' => "#");
        	$i++;
        }

        echo json_encode($json);




    }else{

    	header('Location:index.php');
    }
