<?php
$conexion = null;
$codigoSalida = 0;
try {
    require __DIR__ . '/conexion_pruebas.php';
    require_once __DIR__ . '/../src/productos.php';
    echo "Conectado: Se conectó a la base de pruebas." . PHP_EOL;
    $conexion->beginTransaction();
    $nombre = 'Prueba integración válida';
    $cantidad = 5;
    $estado = 'Disponible';
    $error = guardarProducto($conexion, $nombre, $cantidad, $estado);

    if ($error !== null) {
        throw new RuntimeException('El producto válido fue rechazado.');
    }

    $id = $conexion->lastInsertId();
    echo "Correcto: inserción realizada. ID: $id" . PHP_EOL;
    $consulta = $conexion->prepare(
        'SELECT nombre, cantidad, estado
         FROM productos
         WHERE id = :id'
    );

    $consulta->execute(['id' => $id]);
    $producto = $consulta->fetch();

    if (
        $producto !== false
        && $producto['nombre'] === $nombre
        && (int) $producto['cantidad'] === $cantidad
        && $producto['estado'] === $estado
    ) {
        echo "CORRECTO: nombre, cantidad y estado coinciden." . PHP_EOL;
    } else {
        echo "ERROR: producto no encontrado o datos diferentes."
            . PHP_EOL;
        $codigoSalida = 1;
    }
    $nombreInvalido = 'Prueba integración inválida';
    $error = guardarProducto(
        $conexion,
        $nombreInvalido,
        $cantidad,
        'Reservado'
    );
    $consulta = $conexion->prepare(
        'SELECT COUNT(*) FROM productos WHERE nombre = :nombre'
    );
    $consulta->execute(['nombre' => $nombreInvalido]);
    $registrosInvalidos = (int) $consulta->fetchColumn();

    if ($error === 'estado_invalido' && $registrosInvalidos === 0) {
        echo "CORRECTO: estado inválido rechazado sin crear registro."
            . PHP_EOL;
    } else {
        echo "ERROR: estado inválido no fue rechazado correctamente."
            . PHP_EOL;
        $codigoSalida = 1;
    }
} catch (PDOException | RuntimeException $error) {
    echo "ERROR: " . $error->getMessage() . PHP_EOL;
    $codigoSalida = 1;
} finally {
    if ($conexion !== null && $conexion->inTransaction()) {
        $conexion->rollBack();
        echo "Limpieza: inserción eliminada." . PHP_EOL;
    }
}
exit($codigoSalida);