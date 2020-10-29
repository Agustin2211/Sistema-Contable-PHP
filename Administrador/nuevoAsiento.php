<?php

    session_start();

    require('database.php');

    $message = '';


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

    if(!empty($_POST['monto'])){
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = date("Y-m-d");
        $stmt = $conn->prepare("INSERT INTO asiento (fecha, idUsuario) VALUES ('$fecha', '$usuario')");
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam('idUsuario', $usuario);
        $stmt->execute();
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
        <form action="nuevoAsiento.php" class="form-inline" role="form" method="POST">

                <p>
                    <label>Fecha: </label> <input type="datetime" name="fecha" id="fecha"required readonly value="<?php echo date("Y-m-d");?>">
                </p>

            <div>
                <footer>
                    Seleccionar Cuenta:
                    <select name="cuenta">
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
                </footer>
    
                <form>
                    <input type="buttom" value="Agregar Cuenta" OnClick = "location.href='agregarCuenta.php'">
                    <input type="buttom" value="Ver Plan de Cuenta" onclick = "location.href='verPlanDeCuenta.php'">
                </form>

            </div>

            <div>
               <label>Monto: </label><input type="number" name="monto" min='0' required><label> </label><select id="dondeVa" required>
                                                                                <option value ="debe">Debe</option>
                                                                                <option value ="haber">Haber</option>
                                                                             </select>
            </div>
    
            <form>
                <input type="submit" value="Cargar Asiento">
            </form>

            <table class="table table-hover table-condensed table-bordered" style="text-align: center;">
	            <caption><label>Asientos Cargados</label></caption>
	            <tr>
		            <td>Id</td>
		            <td>Fecha</td>
		            <td>IdUsuario</td>
                </tr>
                
            <?php 
                $sql = "SELECT * FROM asiento";
                $result= db_query($sql);
                while($ver=mysqli_fetch_object($result)): 
            ?>

	            <tr>
		            <td><?php echo $ver->id; ?></td>
		            <td><?php echo $ver->fecha; ?></td>
		            <td><?php echo $ver->idUsuario; ?></td>
	            </tr>
            <?php endwhile; ?>
            </table>
        </form>
        
        <p>
            <form>
                <input type="buttom" value="Registrar Asientos" onclick="registrarAsientos()">
            </form>

            <form>
                <input type="buttom" value ="Atras" onclick="location.href = 'admin.php'">
            </form>
        </p>

    </body>

    <script>
        function registrarAsientos(){
            
        }
    </script>

</html>