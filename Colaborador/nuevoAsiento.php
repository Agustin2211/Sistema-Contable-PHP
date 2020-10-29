<?php

    session_start();

    require('database.php');

    $message = '';
    
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if(!empty($_POST)){

        $dondeva = $_POST['dondeVa'];

        if("debe" == $dondeva){
            $idcuenta = $_POST['idCuenta'];
            $debe = $_POST['monto'];
            $haber = 0;
            $records = $conn->prepare("INSERT INTO tablapost (idCuenta, debe, haber) VALUES ($idcuenta, $debe, $haber)");
            $records->bindParam('idCuenta', $idcuenta);
            $records->bindParam('debe', $debe);
            $records->bindParam('haber', $haber);
            $records->execute();
        }else{
            $idcuenta = $_POST['idCuenta'];
            $debe = 0;
            $haber = $_POST['monto'];
            $records = $conn->prepare("INSERT INTO tablapost (idCuenta, debe, haber) VALUES ($idcuenta, $debe, $haber)");
            $records->bindParam('idCuenta', $idcuenta);
            $records->bindParam('debe', $debe);
            $records->bindParam('haber', $haber);
            $records->execute();
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Registrar Asiento</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Registrar Asiento</h1>
    </head>

<?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
<?php endif; ?>

    <body>
        <div class="container">
                <p>
                    <label>Fecha: </label> <input type="datetime" name="fecha" required readonly value="<?php echo date("Y-m-d");?>">
                </p>

                <form action="nuevoAsiento.php" class="form-inline" role="form" method="POST">
                    <p>
                    <label>Seleccionar Cuenta: </label> <select class="form-control input-sm" name="idCuenta" id="idCuenta" this.options[this.selectedIndex].innerHTML>
                                                        <?php include("funciones.php"); ?>
                                                        <?php 
                                                            $sql="SELECT id, cuenta
                                                            from cuentas
                                                            where recibeSaldo = '1'";
                                                        $result=db_query($sql);
                                                        ?>
        
                                                        <?php while($row=mysqli_fetch_row($result)): ?>
                                                            <option value= <?php echo $row[0] ?> > <?php echo $row[1];?> </option>
                                                        <?php endwhile ?>
                                                        </select>
                </p>
    
                <p>
                    <input type="buttom" value="Ver Plan de Cuenta" onclick = "location.href='verPlanDeCuenta.php'">
                </p>

            <p>
               <label>Monto: </label><input step="any" type="number" step="0.01" name="monto" id="monto" min='0' required><label> </label><select name="dondeVa" required>
                                                                                                                                            <option value ="debe">Debe</option>
                                                                                                                                            <option value ="haber">Haber</option>
                                                                                                                                        </select>
            </p>
    
            <p>
                <input type="submit" value="Cargar Asiento">
            </p>
        </form>
        </div>
        
        <table class="table table-hover table-condensed table-bordered" style="text-align: center;">
	            <caption><label>Asientos Cargados</label></caption>
	            <tr>
		            <td>Id</td>
		            <td>Cuenta</td>
                    <td>Debe</td>
                    <td>Haber</td>
                    <td>Eliminar</td>
                </tr>
                
            <?php 
                $sql = "SELECT * FROM tablapost";
                $result= db_query($sql);
                while($ver=mysqli_fetch_object($result)): 
            ?>

	            <tr>
		            <td><?php echo $ver->id; ?></td>
		            <td><?php echo $ver->idCuenta; ?></td>
                    <td><?php echo $ver->debe; ?></td>
                    <td><?php echo $ver->haber; ?></td>
                    <td>
                        <a href="borrarAsiento.php?id=<?php echo $ver->id; clearstatcache(); ?>"><img src='/php-login/images/eliminar.png' class='img-rounded'/></a>
        	        </td>
		    </tr>
	            </tr>
            <?php endwhile; ?>
            </table>


        <p>
            <form>
                <input type="buttom" value="Registrar Asientos" <?php clearstatcache(); ?> onclick="location.href='registrarAsientos.php'">
            </form>

            <form>
                <input type="buttom" value ="Atras" onclick="location.href = 'colab.php'">
            </form>
        </p>
        
    </body>
</html>