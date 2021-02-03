<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $id = $_GET['id'];

    if(!empty($_POST)){

        $id = $_GET['id'];

        $importeDeHorasExtras = $_POST['horasExtras'] * $_POST['ValorDeHorasExtras'];
        $importeFeriadosTrabajados = $_POST['feriadosTrabajados'] * $_POST['valorDeFeriadosTrabajados'];


        $connection = mysqli_connect("localhost", "root", "", "php_login_database");
        $sql = ("SELECT * FROM empleado WHERE id like '$id'");
        $result = db_query($sql);
        $row = mysqli_fetch_object($result);

        $puesto = $row ->puesto;

        $connection2 = mysqli_connect("localhost", "root", "", "php_login_database");
        $sql2 = ("SELECT * FROM puestoempleado WHERE id like '$puesto'");
        $result2 = db_query($sql);
        $row2 = mysqli_fetch_object($result2);

        $sueldo = $row2->sueldo;

        $sueldo = $importeDeHorasExtras + $importeFeriadosTrabajados;

        /*Aporte Personal Jubilación: 11%*/
            $sueldo = $sueldo - (($sueldo * 11)/100);
        /*Aporte Personal O. Social: 3%*/
            $sueldo = $sueldo - (($sueldo * 3)/100);
        /*Aporte Personal Sindicato: 2.5%*/
            $sueldo = $sueldo - (($sueldo * 2.5)/100);
        /*Contribución Patronal Jubilación: 17.6%*/
            $sueldo = $sueldo - (($sueldo * 17.6)/100);
        /*Contribución Patronal O. Social: 5.4%*/
            $sueldo = $sueldo - (($sueldo * 5.4)/100);
        /*Ley de Riesgo de Trabajo (A.R.T.): 1,5%*/
            $sueldo = $sueldo - (($sueldo * 1.5)/100);

        /*MUCHAS CHANCES DE QUE UTILICE UNA TABLA LA CUAL TENGA LOS DESCUENTOS QUE SON FIJOS*/
        $cuenta = 530;
        $haber = 0;
        $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$sueldo', '$haber')");
        $stmt->bindParam('cuenta', $cuenta);
        $stmt->bindParam('debe', $sueldo);
        $stmt->bindParam('haber', $haber);
        $stmt->execute();

        if($porcentajeCaja == 100){
            $cuenta = 111;
            $haber = 0;
            $debe = $sueldo;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();
        }elseif ($porcentajeBanco == 100) {
            $cuenta = 113;
            $haber = 0;
            $debe = $sueldo;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();
        }else{
            $sueldoCaja = $sueldo - (($sueldo * $porcentajeCaja)/100);
            $sueldoBanco  = $sueldo - (($sueldo * $porcentajeBanco)/100);

            $cuenta = 111;
            $debe = 0;
            $haber = $sueldoCaja;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();

            $cuenta = 113;
            $haber = $sueldoBanco;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();
        }

        $saldo1 = 0;
        $saldo2 = 0;
        $sql = "SELECT * FROM tablapost";
        $result= db_query($sql);

        while($ver=mysqli_fetch_row($result)){
            $saldo1 = $saldo1 + ($ver[2]);
            $saldo2 = $saldo2 + ($ver[3]);
            $saldo = ($saldo1) - ($saldo2);
        }
    
            if($saldo == 0){
                /*ASIENTO INTRODUCIDO EN LIBRO DIARIO*/
                $fecha = date("Y-m-d");
                $detalle = $_POST['detalle'];
                $records = $conn->prepare("INSERT INTO asiento (fecha, detalle) VALUES ('$fecha', '$detalle')");
                $records->bindParam('fecha', $fecha);
                $records->bindParam('detalle', $detalle);
                $records->execute();

                $sqlAsiento = "SELECT * FROM tablapost";
                $resultAsiento= db_query($sqlAsiento);
                
                while($ver=mysqli_fetch_row($resultAsiento)){
                    $saldo1 = $ver[2];
                    $saldo2 = $ver[3];
                    $idcuenta = $ver[1];
                    
                    /*DE ACA OBTENGO EL IDASIENTO DE LA TABLA ASIENTO*/
                    $sql2 = "SELECT MAX(id) FROM asiento";
                    $result2 = db_query($sql2);
                    $ver2=mysqli_fetch_array($result2);
                    $idasiento = $ver2[0];
                    
                    if($saldo1 != 0){
                        
                        $cero = 0;

                        $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ('$fecha', '$saldo1', '$cero', '$idcuenta', '$idasiento')");
                        $records3->bindParam('fecha', $fecha);
                        $records3->bindParam('debe', $saldo1);
                        $records3->bindParam('haber', $cero);
                        $records3->bindParam('idCuenta', $idcuenta);
                        $records3->bindParam('idAsiento', $idasiento);  
                        $records3->execute();

                    }else{

                            $cero = 0;

                            $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ('$fecha', '$cero', '$saldo2', '$idcuenta', '$idasiento')");
                            $records3->bindParam('fecha', $fecha);
                            $records3->bindParam('debe', $cero);
                            $records3->bindParam('haber', $saldo2);
                            $records3->bindParam('idCuenta', $idcuenta);
                            $records3->bindParam('idAsiento', $idasiento);  
                            $records3->execute();

                        }
                
                }

                echo "SUELDO PAGADO EXITOSAMENTE";
            }else{
                echo "EL METODO DE LA PARTIDA DOBLE NO SE CUMPLE";
            }
    }   
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Editar Cuenta</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Pago de Sueldo Realizado de Forma Correcta</h1>
        <h2>Haga click en el boton de abajo para volver al menu principal</h2>
        
        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='admin.php'">
        </form>
 
    </body>
    
</html>