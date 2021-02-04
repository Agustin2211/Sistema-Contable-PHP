<?php

    include('funciones.php');

    session_start();

    clearstatcache();

    require('database.php');

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if(!empty($_POST)){

        /*LO PRIMERO QUE SE HACE ES REGISTRAR LAS HORAS EXTRAS Y FERIADOS QUE TRABAJO EL EMPLEADO*/

        $id = $_POST['id'];

        $importeDeHorasExtras = $_POST['horasExtras'] * $_POST['ValorDeHorasExtras'];
        $importeFeriadosTrabajados = $_POST['feriadosTrabajados'] * $_POST['valorDeFeriadosTrabajados'];
        $bono = $_POST['bono'];

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

        /*UNA VEZ QUE SE TIENE EL SUELDO, SE HACEN TODOS LAS RESTAS Y SUMAS A SU SUELDO*/

        $sueldo = $sueldo + $importeDeHorasExtras + $importeFeriadosTrabajados + $bono;

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

        /*SE REALIZAN LOS ASIENTOS CORRESPONDIENTES A LA TABLAPOST*/
        $cuenta = 530;
        $haber  = 0;
        $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$sueldo', '$haber')");
        $stmt->bindParam('cuenta', $cuenta);
        $stmt->bindParam('debe', $sueldo);
        $stmt->bindParam('haber', $haber);
        $stmt->execute();

        $tipoPago = $_POST['formaPago'];

        /*SI SE PAGA TODO CON DINERO, SE ENTRA A ESTE IF*/
        if($tipoPago == 'caja'){
            $cuenta = 111;
            $haber = $sueldo;
            $debe = 0;  
            $stmt = $conn->prepare("INSERT INTO tablapost (cuenta, debe, haber) VALUES ('$cuenta', '$debe', '$haber')");
            $stmt->bindParam('cuenta', $cuenta);
            $stmt->bindParam('debe', $debe);
            $stmt->bindParam('haber', $haber);
            $stmt->execute();
        
        /*SI SE PAGA TODO CON LO DEL BANCO, SE ENTRA A ESTE IF*/
        }elseif ($tipoPago == 'banco') {
            $cuenta = 113;
            $haber = $sueldo;
            $debe = 0;  
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

        /*ACA ES DONDE EXPLOTA TODO, COMO NO DA CON EXACTITUD 0 EL SALDO, NUNCA REALIZA EL INGRESO DE DATOS EN EL LIBRO DIARIO*/
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

            <p>
                <label>Bono: </label><select class="form-control input-sm" name="bono" id="bono" this.options[this.selectedIndex].innerHTML>
                                                                    <option value= <?php echo 0 ?> >Ninguno</option>
                                                                <?php 
                                                                    $sql="SELECT * FROM bono";
                                                                    $result=db_query($sql);
                                                                ?>
        
                                                                <?php while($row=mysqli_fetch_row($result)): ?>
                                                                    <option value= <?php echo $row[3] ?> > <?php echo $row[1];?> </option>
                                                                <?php endwhile ?>
                                                            </select>
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
                <label>Forma de Pago: </label> <select class="form-control input-sm" name="formaPago" id="formaPago" this.options[this.selectedIndex].innerHTML>
                                                                    <option value="caja">Caja</option>
                                                                    <option value="banco">Banco Cuenta Corriente</option>
                                                            </select>
            </p>

            <input type="submit" value="Pagar Sueldo">

        </form>

        <form>
            <input type="buttom" value="Atras" onclick="location.href='pagarSueldo.php'">
        </form>

    </body>

</html>