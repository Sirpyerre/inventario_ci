## Context

La tabla `ubicacion` guarda las coordenadas y la cantidad entera. El controlador `Welcome` crea las celdas desde un formulario de cuadrícula y el listado consulta únicamente los racks. El editor actual usa un campo de texto sin valor inicial al abrirse.

## Goals / Non-Goals

- Incorporar detalle y edición en la cuadrícula existente, con una búsqueda por pieza desde el listado.
- Mantener CodeIgniter y MariaDB. El ejercicio se centra en el conteo actual de cada posición.

## Decisions

- **Una pieza por posición.** Agregar a `ubicacion` un número de parte normalizado e indexado y una descripción, ambos inicialmente nulos. Un catálogo central serviría para compartir descripciones entre posiciones. Para este ejercicio, guardarlas por posición permite trabajar directamente con la estructura actual.
- **Consulta y edición explícitas.** Reutilizar la cuadrícula para el alta y el detalle guardado. Mostrar «Unidades» y precargar el editor con los datos actuales. Mantener una pieza asignada cuando su existencia llegue a cero para localizar su posición al reabastecerla.
- **Escrituras consistentes.** Validar los datos en servidor, comprobar que una posición editada pertenece al rack y agrupar el alta en una transacción. Mostrar el error junto a la captura y conservar las ediciones pendientes del usuario si falla el guardado.

## Risks / Trade-offs

- Las cantidades históricas pueden representar artículos distintos. Mostrar «Sin pieza asignada» hasta que el operador identifique cada celda.
- Una pieza almacenada en varias posiciones puede tener descripciones distintas. La búsqueda agrupa por número de parte normalizado y presenta cada ubicación con su propia descripción.

## Migration Plan

Agregar las columnas y el índice mediante una migración aditiva antes de activar la interfaz. Conservar los registros y las cantidades existentes. Para volver a la versión anterior, revertir la aplicación y conservar las columnas aditivas para preservar los datos capturados.
