<?php

require_once __DIR__ . '/validaciones.php';

function guardarProducto(
    PDO $conexion,
    string $nombre,
    mixed $cantidad,
    string $estado
): ?string {
    if (!validarNombreProducto($nombre)) {
        return 'nombre_invalido';
    }

    if (!validarCantidadProducto($cantidad)) {
        return 'cantidad_invalida';
    }

    if (!validarEstadoProducto($estado)) {
        return 'estado_invalido';
    }

    $sentencia = $conexion->prepare(
        'INSERT INTO productos (nombre, cantidad, estado)
         VALUES (:nombre, :cantidad, :estado)'
    );

    $sentencia->execute([
        'nombre' => $nombre,
        'cantidad' => (int) $cantidad,
        'estado' => $estado
    ]);

    return null;
}
