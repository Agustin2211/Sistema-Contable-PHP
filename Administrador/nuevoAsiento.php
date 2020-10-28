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

    $fecha = date("Y-m-d");
    $stmt = $conn->prepare("INSERT INTO asiento (fecha, idUsuario) VALUES ('$fecha', '$usuario')");
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam('idUsuario', $usuario);
    $stmt->execute();

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
            
        <?php date_default_timezone_set('America/Argentina/Buenos_Aires');?>
            
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
                Monto: <input type="number" min='0' required><label> </label><select id="dondeVa" required>
                                                                                <option value ="debe">Debe</option>
                                                                                <option value ="haber">Haber</option>
                                                                             </select>
            </div>
    
            <form>
                <input type="submit" value="Cargar Asiento">
            </form>

           

            </form>

            <input type="buttom" value="Registrar Asientos" onclick="registrarAsientos()">
        
        <p>
            <form>
                <input type="buttom" value ="Atras" onclick="location.href = 'admin.php'">
            </form>
        </p>

    </body>

    <table class="table table-hover table-condensed table-bordered" style="text-align: center;">
	<caption><label>Articulos</label></caption>
	<tr>
		<td>Id</td>
		<td>Fecha</td>
		<td>IdUsuario</td>

	</tr>

    <?php 
        $server = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'php_login_database';
        $conn = mysqli_connect($server, $username, $password, $database);
        $records = $conn->prepare('SELECT * FROM asiento');
        mysqli_query($conn, $records);
        $result = $records->fetch(PDO::FETCH_ASSOC);
        while($ver=mysqli_fetch_row($result)): ?>

	<tr>
		<td><?php echo $ver[0]; ?></td>
		<td><?php echo $ver[1]; ?></td>
		<td><?php echo $ver[2]; ?></td>

		<td>
			<?php 
			$imgVer=explode("/", $ver[4]) ; 
			$imgruta=$imgVer[1]."/".$imgVer[2]."/".$imgVer[3];
			?>
			<img width="80" height="80" src="<?php echo $imgruta ?>">
		</td>
		<td><?php echo $ver[5]; ?></td>
		<td>
			<span  data-toggle="modal" data-target="#abremodalUpdateArticulo" class="btn btn-warning btn-xs" onclick="agregaDatosArticulo('<?php echo $ver[6] ?>')">
				<span class="glyphicon glyphicon-pencil"></span>
			</span>
		</td>
		<td>
			    <span class="btn btn-danger btn-xs" onclick="eliminaArticulo('<?php echo $ver[6] ?>')">
				    <span class="glyphicon glyphicon-remove"></span>
			    </span>
		    </td>
	    </tr>
    <?php endwhile; ?>
    </table>



    <script>
        function registrarAsientos(){
            
        }
    </script>

</html>