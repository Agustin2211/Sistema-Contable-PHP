<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Lista de Empleados</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Lista de Empleados</h1>
    </head>

    <body>
        <?php
	        include("funciones.php");
	    ?>

	    <table>
		    <tr>
			    <th width="30%">Nombre</th>
			    <th width="30%">Apellido</th>
			    <th width="30%">C.U.I.L.</th>
                <th width="30%">D.N.I.</th>
                <th width="30%">Direccion</th>
                <th width="30%">Telefono</th>
                <th width="30%">Fecha de Nacimiento</th>
                <th width="30%">Estado Civil</th>
                <th width="30%">Cantidad de Hijos</th>
		    </tr>
	
	    <?php 
		    $sql = "SELECT * 
                    FROM empleado";
		    $result = db_query($sql);
		    while($row = mysqli_fetch_object($result)){
        ?>
	
		    <tr>
			    <td><?php echo $row->nombre;?></td>
                <td><?php echo $row->apellido;?></td>
                <td><?php echo $row->cuil;?></td>
                <td><?php echo $row->dni;?></td>
                <td><?php echo $row->direccion;?></td>
                <td><?php echo $row->telefono;?></td>
                <td><?php echo $row->fechadenacimiento;?></td>
                <td><?php echo $row->estadocivil;?></td>
                <td><?php echo $row->cantidadhijos;?></td>

		    </tr>
	    <?php } ?>

        </table>

        <form>
            <input type="buttom" value="Atras" OnClick = "location.href='empleados.php'">
        </form>

    </body>

</html>