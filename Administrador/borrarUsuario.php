<?php

    include("funciones.php");
    $id = $_GET['id'];
    delete('users','id',$id);
    header("administrarRoles.php"); 
    
?>