
<?php

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/productos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

//Obtener los datos del formulario
$nombre = $_POST['nombre'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$estadoProducto = $_POST['estado'] ?? '';

// Comprobar que los datos tengan el tipo esperado
if (
    !is_string($nombre)
    || !is_string($cantidad)
    || !is_string($estadoProducto)
) {
    header('Location: index.php?estado=incompleto');
    exit;
}

// Eliminar espacios innecesarios
$nombre = trim($nombre);
$cantidad = trim($cantidad);
$estadoProducto = trim($estadoProducto);

// Verificar campos vacíos
if ($nombre === '' || $cantidad === '' || $estadoProducto === '') {
    header('Location: index.php?estado=incompleto');
    exit;
}

// Validar y guardar el producto
$error = guardarProducto($conexion, $nombre, $cantidad, $estadoProducto);
if ($error !== null) {
    header('Location: index.php?estado=' . $error);
    exit;
}

// Regresar al formulario
header('Location: index.php?estado=guardado');
exit;
