<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if(!empty($_POST)){

        $id = $_POST['id'];

        $importeDeHorasExtras = $_POST['horasExtras'] * $_POST['ValorDeHorasExtras'];
        $importeFeriadosTrabajados = $_POST['feriadosTrabajados'] * $_POST['valorDeFeriadosTrabajados'];


        $connection = mysqli_connect("localhost", "root", "", "php_login_database");
        $sql = ("SELECT * FROM empleado WHERE id like '$id'");
        $result = db_query($sql);
        $row = mysqli_fetch_array($result);

        $puesto = $row[10];

        $connection2 = mysqli_connect("localhost", "root", "", "php_login_database");
        $sql2 = ("SELECT * FROM puestoempleado WHERE id like '$puesto'");
        $result2 = db_query($sql2);
        $row2 = mysqli_fetch_array($result2);

        $sueldo = $row2[3];

        $sueldo = $sueldo + $importeDeHorasExtras + $importeFeriadosTrabajados;

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


        $cuenta = 530;
        $debe = $sueldo;
        $haber = 0;
        $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
        $stmt->bindParam('cuenta', $cuenta);
        $stmt->bindParam('debe', $debe);
        $stmt->bindParam('haber', $haber);
        $stmt->execute();

        $porcentajeCaja = $_POST['porcentajeCaja'];
        $porcentajeBanco = $_POST['porcentajeBanco'];

        if($porcentajeCaja == 100){
            $cuenta = 111;
            $haber = $sueldo;
            $debe = 0;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();
        }elseif ($porcentajeBanco == 100) {
            $cuenta = 113;
            $haber = $sueldo;
            $debe = 0;  
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

        $saldo1 = 0.0;
        $saldo2 = 0.0;
        $sql = "SELECT * FROM tablapost";
        $result= db_query($sql);

        while($ver=mysqli_fetch_row($result)){
            $saldo1 = $saldo1 + ($ver[2]);
            $saldo2 = $saldo2 + ($ver[3]);
            $saldo = ($saldo1) - ($saldo2);
        }
    
            if($saldo == 0.0){
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
                    
                    if($saldo1 != 0.0){
                        
                        $cero = 0.0;

                        $records3 = $conn->prepare("INSERT INTO cuentaasiento (fecha, debe, haber, idCuenta, idAsiento) VALUES ('$fecha', '$saldo1', '$cero', '$idcuenta', '$idasiento')");
                        $records3->bindParam('fecha', $fecha);
                        $records3->bindParam('debe', $saldo1);
                        $records3->bindParam('haber', $cero);
                        $records3->bindParam('idCuenta', $idcuenta);
                        $records3->bindParam('idAsiento', $idasiento);  
                        $records3->execute();

                    }else{

                            $cero = 0.0;

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

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Pagar Sueldo</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <form action="pagarSueldoEmpleado.php" class="form-inline" role="form" method="POST">       

            <p>
                <label>Pago de Sueldo al Empleado: </label><input step="any" type="number" step="0.01" name="id" id="id" min='0' required>
            </p>

            <p>
                <label>Horas Extras Trabajadas: </label><input step="any" type="number" step="0.01" name="horasExtras" value='0' id="horasExtras" min='0' required><label> </label><label>Valor de 1 hora Trabajada: </label><input step="any" type="number" step="0.01" name="ValorDeHorasExtras" value='0' id="ValorDeHorasExtras" min='0' required>
            </p>

            <p>
                <label>Feriados Trabajados: </label><input step="any" type="number" step="0.01" name="feriadosTrabajados" value='0' id="feriadosTrabajados" min='0' required><label> </label><label>Valor de un Dia Trabajado: </label><input step="any" type="number" step="0.01" name="valorDeFeriadosTrabajados" value='0' id="valorDeFeriadosTrabajados" min='0' required>
            </p>

            <textarea readonly>Los aportes y retenciones son los siguientes:
                o Aporte Personal Jubilación: 11%
                o Aporte Personal O. Social: 3%
                o Aporte Personal Sindicato: 2.5%
                o Contribución Patronal Jubilación: 17.6%
                o Contribución Patronal O. Social: 5.4%
                o Ley de Riesgo de Trabajo (A.R.T.): 1,5%
            </textarea>

            <p>
                <label>Detalle: </label><input name="detalle" type="text" id="detalle" required>
            </p>

            <p>
                <label>Fecha: </label> <input type="datetime" name="fecha" required readonly value="<?php echo date("Y-m-d");?>">
            </p>

            <p>
                <label>Porcentaje de Pago por Caja: </label><input step="any" type="number" step="0.01" name="porcentajeCaja" value='0' id="porcentajeCaja" min='0' required>
            </p>

            <p>
                <label>Porcentaje de Pago por Banco Cuenta Corriente: </label><input step="any" type="number" step="0.01" name="porcentajeBanco" value='0' id="porcentajeBanco" min='0' required>
            </p>


            <input type="submit" value="Pagar Sueldo">

        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='pagarSueldo.php'">
        </form>

    </body>

</html>