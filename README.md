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

## 8. Evidencia de validación

`validarEstadoProducto()` acepta únicamente los valores exactos `Disponible` y
`Sin existencia`. `guardarProducto()` llama a esta validación antes del
`INSERT`; un valor inválido devuelve `estado_invalido` y no crea un registro.

Las cinco pruebas unitarias específicas del estado en
`tests/test_validaciones.php` cubren:

| Caso | Entrada | Resultado esperado |
|---|---|---|
| Válido | `Disponible` | Aceptado |
| Válido | `Sin existencia` | Aceptado |
| Vacío | `""` | Rechazado |
| No permitido | `Reservado` | Rechazado |
| Fuera del dominio permitido | 100 caracteres `x` | Rechazado |

Las dos pruebas de integración en `tests/test_integracion_productos.php`
ejercitan la misma función `guardarProducto()` que usa el formulario. La
primera registra un producto válido y consulta por ID para comprobar nombre,
cantidad y estado. La segunda intenta guardar el estado `Reservado`, comprueba
el error `estado_invalido` y verifica que el conteo del producto sea cero. Un
bloque `finally` revierte la transacción, incluso si una aserción falla.

**Fallo y corrección del entorno:** al intentar correr la integración en la
máquina local, MySQL no estaba iniciado y PDO devolvió
`SQLSTATE[HY000] [2002]` (conexión rechazada). No era un fallo de la validación
ni de la lógica de guardado: la base de pruebas no estaba disponible. La
corrección fue ejecutar la integración en GitHub Actions, que inicia un
servicio MySQL 8 y crea la tabla de pruebas desde `base_datos.sql` antes de
correr los tests.

**Evidencia del pipeline:** la ejecución
[#43815766135](https://github.com/MAYo199978/Inventario/actions/runs/43815766135)
terminó con `success`. El log muestra `18` pruebas unitarias correctas y `0`
fallidas; en integración muestra que el producto válido fue recuperado, que
el inválido no creó un registro y que la inserción de prueba fue revertida.
El workflow también verifica la sintaxis PHP de la aplicación, la lógica de
guardado, las validaciones y las pruebas.
