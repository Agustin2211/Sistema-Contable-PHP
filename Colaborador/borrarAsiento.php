<?php
    include("funciones.php");
    $id = $_GET['id'];
    delete('tablapost','id',$id);
    header("agregarCuenta.php"); 
?>