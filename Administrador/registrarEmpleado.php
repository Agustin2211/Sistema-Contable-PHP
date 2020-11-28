<?php

    require('database.php');

    $message = '';

    if (!empty($_POST)){
        $stmt = $conn->prepare("INSERT INTO empleado (nombre, apellido, direccion, telefono, fechaDeNacimiento, dni, estadoCivil, cantidadHijos, CUIL) VALUES (:nombre, :apellido, :direccion, :telefono, :fechaDeNacimiento, :dni, :estadoCivil, :cantidadHijos, :CUIL)");
        $stmt->bindParam(':nombre', $_POST['nombre']);
        $stmt->bindParam(':apellido', $_POST['apellido']);
        $stmt->bindParam(':direccion', $_POST['direccion']);
        $stmt->bindParam(':telefono', $_POST['telefono']);
        $stmt->bindParam(':fechaDeNacimiento', $_POST['fechaDeNacimiento']);
        $stmt->bindParam(':dni', $_POST['dni']);
        $stmt->bindParam(':estadoCivil', $_POST['estadoCivil']);
        $stmt->bindParam(':cantidadHijos', $_POST['cantidadHijos']);
        $stmt->bindParam(':CUIL', $_POST['CUIL']);
        if ($stmt->execute()) {
            $message = 'Empleado agregado correctamente';
        }
    }

?>

<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Administrador</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>
        <div class="container">
            <form action="registrarEmpleado.php" class="form-inline" role="form" method="POST">

                <p>
                    <label>Nombre: </label><input name="nombre" type="text" id="nombre" required pattern="[a-z A-Z]{1,}" title="El nombre de la cuenta solamente debe contener letras.">
                </p>

                <p>
                    <label>Apellido: </label><input name="apellido" type="text" id="apellido" required pattern="[a-z A-Z]{1,}" title="El nombre de la cuenta solamente debe contener letras.">
                </p>

                <p>
                    <label>D.N.I: </label><input step="any" type="number" step="0.01" name="dni" id="dni" min='0' required>
                </p>

                <p>
                    <label>C.U.I.L: </label><input step="any" type="number" step="0.01" name="cuil" id="cuil" min='0' required>
                </p>

                <p>
                    <label>Direccion: </label><input name="direccion" type="text" id="direccion" required pattern="[a-z A-Z]{1,}" title="El nombre de la cuenta solamente debe contener letras.">
                </p>

                <p>
                    <label>Telefono: </label><input step="any" type="number" step="0.01" name="telefono" id="telefono" min='0' required>
                </p>

                <p>
                    <label>Fecha de Nacimiento: </label><input step="any" type="date" name="fechaDeNacimiento" id="fechaDeNacimiento" required>
                </p>

                <p>
                    <label>Estado Civil: </label><select name="estadoCivil" id="estadoCivil" this.options[this.selectedIndex].innerHTML required>
                                                    <option value='Soltero'>Soltero</option>
                                                    <option value='Casado'>Casado</option>
                                                    <option value='EnPareja'>En Pareja</option>
                                                </select>
                </p>

                <p>
                    <label>Cantidad de Hijos: </label><input step="any" type="number" step="0.01" name="cantidadHijos" id="cantidadHijos" min='0' required>
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