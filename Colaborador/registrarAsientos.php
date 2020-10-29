<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    $message = '';

    date_default_timezone_set('America/Argentina/Buenos_Aires');

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
        if (isset($_SESSION['user_id'])) {
            $records = $conn->prepare('SELECT id, email, password FROM users WHERE id = :id');
            $records->bindParam(':id', $_SESSION['user_id']);
            $records->execute();
            $results = $records->fetch(PDO::FETCH_ASSOC);
            $usuario = $results['id'];
            if (count($results) > 0) {
                $user = $results;
            }
        }

        $fecha = date("Y-m-d");

        $sql2 = "SELECT idCuenta FROM tablapost";
        $result2 = db_query($sql2);
        $ver=mysqli_fetch_array($result2);
        $idcuenta = $ver[0];
        $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idUsuario, idcuenta) VALUES ('$fecha', $saldo1, $saldo2, $usuario, $idcuenta)");
        $records3->bindParam('fecha', $fecha);
        $records3->bindParam('debe', $saldo1);
        $records3->bindParam('haber', $saldo2);
        $records3->bindParam('idUsuario', $usuario);
        $records3->bindParam('idcuenta', $idcuenta);
        $records3->execute();


        echo "ASIENTOS CARGADOS EXITOSAMENTE";
    }else{
        echo "El DEBE Y EL HABER NO DAN LO MISMO";
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

        <form>
            <input type="buttom" value="Atras" onclick="location.href='nuevoAsiento.php'">
        </form>

        <form>
            <input type="buttom" value="Menu Principal" onclick="location.href='colab.php'">
        </form>

    </body>
    
</html>