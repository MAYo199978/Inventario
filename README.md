# Inventario

## 1. ¿Qué hace el proyecto?

- Agregar nuevos productos con nombre, cantidad, precio y estado.
- Corregir el precio de productos registrados.
- Corregir el nombre de productos registrados.
- Ver el listado de productos registrados.
- Mostrar la fecha de registro.
- Validar que los campos no queden vacíos.
- Registrar y consultar el estado del producto (Disponible o Sin existencia).
---
## 2. ¿Qué tecnología utiliza?
- PHP 8.1.10 o compatible
- MariaDB
- HTML5
- CSS3
- Apache (usualmente con Laragon en Windows)

## 3. ¿Qué se necesita instalar?
- Laragon o xampp
- PHP
- MySQL o MariaDB
- Un navegador web 

## 4.configuro y ejecuto?
-Clonar o copiar el proyecto
-Crear la base de datos
-Configurar la conexión

## 5. Agregar precio a una base existente

Si la base de datos ya fue creada antes de agregar el campo de precio, ejecuta
`migracion_precio.sql` una sola vez sobre la base `inventario`. Los productos
existentes conservarán sus datos y recibirán un precio inicial de `0.00`.
Las instalaciones nuevas ya incluyen la columna `precio` en `base_datos.sql`.

## 6. Agregar el estado a una base existente

Si la base de datos ya existía antes de agregar el estado del producto, ejecuta
`migracion_estado.sql` una sola vez sobre la base `inventario`. Los productos
existentes recibirán el estado “Disponible” cuando tengan existencia y “Sin
existencia” cuando su cantidad sea cero o menor. Las instalaciones nuevas ya
incluyen la columna `estado` en `base_datos.sql`.

## 7. Ejecutar las pruebas

Ejecuta `php tests/test_validaciones.php` para las pruebas unitarias. Las dos
pruebas de integración usan la base configurada mediante `DB_HOST`, `DB_NAME`,
`DB_USER` y `DB_PASSWORD` (por defecto, `pruebainventario2`) y requieren que
la tabla `productos` se haya creado con `base_datos.sql`. Las pruebas guardan
los registros dentro de una transacción y revierten los cambios al terminar.