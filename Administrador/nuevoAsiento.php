<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingresar Asiento</title>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/php-login/assets/css/style.css">
    <h1>Registrar Asiento</h1>
</head>

<body>
    <form action="nuevoAsiento.php" class="form-inline" role="form" method="POST">
        <?php date_default_timezone_set('America/Argentina/Buenos_Aires');?>
        <div>  
            <p>
                Fecha: <input type="datetime" name="fecha" required readonly value="<?php echo date("d-m-Y H:i a");?>">
            </p>

            <p>
                Descripcion: <input type="text" required pattern="[A-Z a-z]">
            </p>  
        </div> 
    
        <div>
            Seleccionar Cuenta: <select name="cuenta">
                <option value="cuenta" required></option>
                    <?php include("funciones.php"); ?>
                    <?php 
                        $sql="SELECT id, cuenta
                              from cuentas
                              where recibeSaldo = '1'";
                        $result=db_query($sql);
                    ?>
        
                    <?php while($row=mysqli_fetch_object($result)){ ?>
                            <option value="<?php echo $row->id ?>"><?php echo $row->cuenta; ?></option>
                    <?php } ?>
            </select>
        
    
            <form>
                <input type="buttom" value="Agregar Cuenta" OnClick = "location.href='agregarCuenta.php'">
                <input type="buttom" value="Ver Plan de Cuenta" onclick = "location.href='verPlanDeCuenta.php'">
            </form>

        </div>

        <div>
            Monto: <input type="number" min='0' required> <select id="dondeVa" required>
                                                            <option value ="debe">Debe</option>
                                                            <option value ="haber">Haber</option>
                                                          </select>
        </div>

    </form>
    
    <p>
        <form>
            <input type="buttom" value ="Atras" onclick="location.href = 'admin.php'">
        </form>
    </p>

</body>

</html>