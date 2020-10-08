<?php

require 'database.php';

$message = '';

if ((!empty($_POST['cuenta'])) && (!empty($_POST['codigo'])) && (!empty($_POST['tipo'])) && (!empty($_POST['recibeSaldo'])) && (!empty($_POST['saldoActual']))) {
    $sql = "INSERT INTO cuentas (cuenta, codigo, tipo, recibeSaldo, saldoActual) VALUES (cuenta, codigo, tipo, recibeSaldo, saldoActual)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam('cuenta', $_POST['cuenta']);
    $stmt->bindParam('codigo', $_POST['codigo']);
    $stmt->bindParam('tipo', $_POST['tipo']);
    $stmt->bindParam('recibeSaldo', $_POST['recibeSaldo']);
    $stmt->bindParam('saldoActual', $_POST['saldoActual']);
    
    if ($stmt->execute()) {
      $message = 'Cuenta agregada correctamente';
    } else {
      $message = 'Perdon, hubo un error al agregar la cuenta';
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Agregar Cuenta</title>
        <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/php-login/assets/css/style.css">
    </head>

    <body>
    
<?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
<?php endif; ?>

        <form action="agregarCuenta.php" method="POST">
            <input name="cuenta" type="text">
            <input name="codigo" type="text">
            <input name="tipo" type="text">
            <input name="recibeSaldo" type="text">
            <input name="saldoActual" type="text">
            <input type="submit" value="Agregar Cuenta">
        </form>

        <form>
            <input type="buttom" value="Atrás" OnClick="location.href='plandecuenta.php'">
        </form>

    </body>

</html>