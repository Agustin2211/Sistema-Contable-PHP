<?php

    session_start();

require('database.php');

    include("funciones.php"); 

    $message = '';

    date_default_timezone_set('America/Argentina/Buenos_Aires');

    if (!empty($_POST)){
        $stmt = $conn->prepare("INSERT INTO empleado (nombre, apellido, direccion, telefono, fechadenacimiento, dni, estadocivil, cantidadhijos, cuil, puesto) VALUES (:nombre, :apellido, :direccion, :telefono, :fechadenacimiento, :dni, :estadocivil, :cantidadhijos, :cuil, :puesto)");
        $stmt->bindParam(':nombre', $_POST['nombre']);
        $stmt->bindParam(':apellido', $_POST['apellido']);
        $stmt->bindParam(':direccion', $_POST['direccion']);
        $stmt->bindParam(':telefono', $_POST['telefono']);
        $stmt->bindParam(':fechadenacimiento', $_POST['fechadenacimiento']);
        $stmt->bindParam(':dni', $_POST['dni']);
        $stmt->bindParam(':estadocivil', $_POST['estadocivil']);
        $stmt->bindParam(':cantidadhijos', $_POST['cantidadhijos']);
        $stmt->bindParam(':cuil', $_POST['cuil']);
        $stmt->bindParam(':puesto', $_POST['puesto']);
        if ($stmt->execute()) {
            $message = 'Empleado agregado correctamente';
        }else{
            $message = 'Ups, algo a fallado';
        }
    }

?>

<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cargar Empleado</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
        <h1>Cargar Empleado</h1>
    </head>


<?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
<?php endif; ?>

    <body>
        <div class="container">
            <form action="registrarEmpleado.php" class="form-inline" role="form" method="POST">

                <p>
                    <label>Puesto de Trabajo: </label> <select class="form-control input-sm" name="puesto" id="puesto" this.options[this.selectedIndex].innerHTML>
                                                            <?php 
                                                                $sql="SELECT *
                                                                    FROM puestoempleado
                                                                    ";
                                                                $result=db_query($sql);
                                                            ?>
        
                                                            <?php while($row=mysqli_fetch_row($result)): ?>
                                                                <option value= <?php echo $row[0] ?> > <?php echo $row[1];?> </option>
                                                            <?php endwhile ?>
                                                        </select>
                </p>

                <p>
                    <label>Nombre: </label><input name="nombre" type="text" required pattern="[a-z A-Z]{1,}" title="El nombre del empleado solamente debe contener letras.">
                </p>

                <p>
                    <label>Apellido: </label><input name="apellido" type="text" required pattern="[a-z A-Z]{1,}" title="El apellido del empleado solamente debe contener letras.">
                </p>

                <p>
                    <label>D.N.I: </label><input step="any" type="number" step="0.01" name="dni" min='0' required>
                </p>

                <p>
                    <label>C.U.I.L: </label><input step="any" type="number" step="0.01" name="cuil" min='0' required>
                </p>

                <p>
                    <label>Direccion: </label><input name="direccion" type="text" required>
                </p>

                <p>
                    <label>Telefono: </label><input step="any" type="number" step="0.01" name="telefono" min='0' required>
                </p>

                <p>
                    <label>Fecha de Nacimiento: </label><input step="any" type="date" name="fechadenacimiento" required>
                </p>

                <p>
                    <label>Estado Civil: </label><select name="estadocivil" this.options[this.selectedIndex].innerHTML required>
                                                    <option value='Soltero'>Soltero</option>
                                                    <option value='Casado'>Casado</option>
                                                    <option value='EnPareja'>En Pareja</option>
                                                </select>
                </p>

                <p>
                    <label>Cantidad de Hijos: </label><input step="any" type="number" step="0.01" name="cantidadhijos" min='0' required>
                </p>

                <p>
                    <input type="Submit" value="Cargar Empleado">
                </p>

            </form>

            <form>
          
                <p>
                    <input type="buttom" value="Atras" onclick="location.href='empleados.php'">
                </p>
            </form>

        </div>
    
    </body>

</html>