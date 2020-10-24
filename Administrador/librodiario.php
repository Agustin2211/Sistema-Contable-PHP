<!DOCTYPE html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Libro Diario</title>
        <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Asientos</h1>
    </head>

    <body>

        <?php
            include("funciones.php");
        ?>

        <table>
            <tr>
                <th width="30%">Fecha</th>
                <th width="30%">Numero de Asiento</th>
                <th width="30%">Descripcion</th>
                <th width="30%"></th>
            </tr>

            <?php 
                $sql = "select * from cuentas order by codigo";
                $result = db_query($sql);
                while($row = mysqli_fetch_object($result)){
            ?>

            <tr>
                <td><?php echo $row->cuenta;?></td>
                <td><?php echo $row->codigo;?></td>
                <td><?php echo $row->tipo;?></td>
                <td><a href="editarCuenta.php?id=<?php echo $row->id;?>">Ver</a></td>
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