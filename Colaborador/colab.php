<?php

	require 'database.php';
   session_start();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Colaborador</title>
        <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>

        <h1>Colaborador</h1>

        <form>
            <input type="buttom" value="Ingresar Asiento" OnClick = "location.href='asientoContable.php'">
        </form>

        <form>
            <input type="buttom" value="Ver Libro Diario" OnClick = "location.href='librodiario.php'">
        </form>

        <form>
            <input type="buttom" value="Ver Plan de Cuenta" OnClick = "location.href='verPlanDeCuenta.php'">
        </form>
        <form>
            <input type ="buttom" value="Cerrar Sesion" OnClick= "location.href='/php-login/logout.php'">
        </form>

    </body>
</html>