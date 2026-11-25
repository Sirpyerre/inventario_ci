## Why

El listado actual confirma que existe un rack, pero el personal de un taller o almacén de refacciones necesita saber dónde encontrar una pieza y cuántas unidades quedan. El ejercicio convierte las posiciones de la cuadrícula en ubicaciones consultables de autopartes.

## What Changes

- Permitir abrir un rack desde el listado y consultar el contenido de cada ubicación.
- Registrar un número de parte, una descripción y una existencia entera por posición. Mostrar la etiqueta «Unidades» y precargar los datos al volver a editar.
- Buscar por número de parte y mostrar las coordenadas y existencias de todas las coincidencias.
- Conservar los racks y sus cantidades actuales. Mostrar las posiciones con datos previos como pendientes de identificar hasta que el operador asigne una pieza.
- Acotar el ejercicio al almacén de refacciones de un taller. Compras, órdenes de servicio y el historial de movimientos quedan fuera del alcance.

## Capabilities

### New Capabilities

- `inventario-por-ubicacion` — Consulta, edición y búsqueda de autopartes por posición de rack.

### Modified Capabilities

## Impact

Afecta las vistas de racks, el controlador `Welcome`, los modelos de racks y ubicaciones y la tabla `ubicacion`. Requiere una migración aditiva para las instalaciones existentes.
