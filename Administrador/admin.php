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
    <title>Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/php-login/assets/css/style.css">
</head>
<body>
    <h1>Administrador</h1>
    <form>
        <input type="buttom" value="Ingresar Asiento" OnClick = "location.href='asientoContable.php'">
    </form>
    
    <form>
        <input type="buttom" value="Ver Libro Diario" onclick = "location.href='librodiario.php'">
    </form>
    
    <form>
        <input type="buttom" value="Ver Libro Mayor" onclick="location.href='libromayor.php'">
    </form>

    <form>
        <input type="buttom" value="Plan de Cuentas" onclick="location.href='plandecuenta.php'">
    </form>

    <form>
        <input type="buttom" value="Administrar Roles" OnClick = "location.href='administrarRoles.php'">
    </form>
    
    <form>
        <input type ="buttom" value="Cerrar Sesión" OnClick= "location.href='/php-login/logout.php'">
    </form>
</body>
</html>