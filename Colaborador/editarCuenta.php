<?php

    include("funciones.php");
    $id = $_GET['id'];
    visibilidadCuenta('cuentas','id',$id);
    header("verPlanDeCuenta.php"); 
    
?>