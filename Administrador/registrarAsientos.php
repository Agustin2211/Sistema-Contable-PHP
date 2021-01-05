<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    $message = '';

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if(!empty($_POST)){

        $fecha = date("Y-m-d");
        $detalle = $_POST['detalle'];
        $records = $conn->prepare("INSERT INTO asiento (fecha, detalle) VALUES ('$fecha', '$detalle')");
        $records->bindParam('fecha', $fecha);
        $records->bindParam('detalle', $detalle);
        $records->execute();

        $saldo1 = 0;
        $saldo2 = 0;
        $sql = "SELECT * FROM tablapost";
        $result= db_query($sql);
        while($ver=mysqli_fetch_row($result)){ 
            $saldo1 = $saldo1 + ($ver[2]);
            $saldo2 = $saldo2 + ($ver[3]);
            $saldo = $saldo1 - $saldo2;
        }

        if($saldo == 0){
            $sql2 = "SELECT cuenta FROM tablapost";
            $result2 = db_query($sql2);
            $ver=mysqli_fetch_array($result2);
            $idcuenta = $ver[0];
            $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ($fecha, $saldo1, $saldo2, $idcuenta, $idasiento)");
            $records3->bindParam('fecha', $fecha);
            $records3->bindParam('debe', $saldo1);
            $records3->bindParam('haber', $saldo2);
            $records3->bindParam('idCuenta', $idcuenta);
            $records3->bindParam('idAsiento', $idasiento);
            $records3->execute();
                
            echo "ASIENTOS CARGADOS EXITOSAMENTE";
        }else{
            echo "EL METODO DE LA PARTIDA DOBLE NO SE CUMPLE";
        }
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Registrar Asiento</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <form action="registrarAsientos.php" class="form-inline" role="form" method="POST">            
            <p>
                <label>Fecha: </label> <input type="datetime" name="fecha" required readonly value="<?php echo date("Y-m-d");?>">
            </p>

            <p>
                <label>Detalle: </label><input name="detalle" type="text" id="detalle" required>
            </p>
            <input type="submit" value="Terminar Carga de Asiento">
        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='nuevoAsiento.php'">
        </form>

        <form>
            <input type="buttom" value="Menu Principal" onclick="location.href='admin.php'">
        </form>

    </body>

</html>