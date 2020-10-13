<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ver Plan de Cuenta</title>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/php-login/assets/css/style.css">
    <h1>Plan de Cuentas</h1>
    
    <style>
       
        table{
	        background-color: white;
	        width: 80%;
            border-collapse: collapse;
            margin: auto;
        }
        
        td{
            background-color: white;
	        border: solid black;
            text-align: center;
            height: auto;
        }
        
    </style>

</head>

<body>


<?php

    $message = ' ';

    require("database.php");
    $sql=("SELECT * FROM cuentas");
    $mysqli=new MySQLI('localhost','root','','php_login_database');

    $query=mysqli_query($mysqli,$sql);

    echo "<table class='table'>";
        echo "<tr class='warning'>";
          echo "<td>Id</td>";
          echo "<td>Nombre de la Cuenta</td>";
          echo "<td>Codigo</td>";
          echo "<td>Tipo</td>";
            echo "<td>Saldo Actual</td>";
      echo "</tr>";

    while($arreglo=mysqli_fetch_array($query)){
        echo "<tr class='success'>";
        echo "<td>$arreglo[0]</td>";
        echo "<td>$arreglo[1]</td>";
        echo "<td>$arreglo[2]</td>";
        echo "<td>$arreglo[3]</td>";
        echo "<td>$arreglo[5]</td>";
        echo "</tr>";
    } 
    echo "</table>";
?>

    <form>
        <input type="buttom" value="Atras" OnClick = "location.href='plandecuenta.php'">
    </form>
</body>
</html>