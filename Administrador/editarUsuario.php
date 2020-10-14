<?php

    include("funciones.php");
    $id = $_GET['id'];
    edit('users','id',$id);
    header("administrarRoles.php"); 
    

?>