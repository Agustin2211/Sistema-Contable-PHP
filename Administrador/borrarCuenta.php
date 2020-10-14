<?php

    include("funciones.php");
    $id = $_GET['id'];
    delete('cuentas','id',$id);
    header("verPlanDeCuenta.php"); 

?>