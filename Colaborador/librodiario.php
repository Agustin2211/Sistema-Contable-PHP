<!DOCTYPE html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Libro Diario</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Asientos</h1>
    </head>

    <body>

        <?php
            include("funciones.php");
        ?>

        <table>
            <tr>
                <th width="30%">Cuenta</th>
                <th width="30%">Debe</th>
                <th width="30%">Haber</th>
            </tr>

            <?php 
                $sql = "select * from tablapost";
                $result = db_query($sql);
                while($row = mysqli_fetch_object($result)){
            ?>

            <tr>
                <td><?php echo $row->idCuenta;?></td>
                <td><?php echo $row->debe;?></td>
                <td><?php echo $row->haber;?></td>

            </tr>
            <?php } ?>

        </table>

        <p>
            <form>
                <input type="buttom" value="Registrar Nuevo Asiento" onclick="location.href = 'nuevoAsiento.php'">
            </form>
    
	        <form>
		        <input type="buttom" value ="Atras" onclick="location.href = 'admin.php'">
	        </form>
        </p>
    </body>

</html>