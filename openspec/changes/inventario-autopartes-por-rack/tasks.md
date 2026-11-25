## 1. Datos

- [x] 1.1 Agregar número de parte, descripción e índice mediante una migración aditiva. Verificar que los racks y los conteos de una base existente se conservan.
- [x] 1.2 Implementar la validación de pieza y unidades y el alta transaccional del rack. Verificar que una escritura fallida revierte el alta y que texto, fracciones y negativos se rechazan.

## 2. Flujo de almacén

- [x] 2.1 Enlazar los racks del listado a una cuadrícula de detalle. Verificar que un rack previo conserva sus coordenadas y que se muestra «Sin pieza asignada».
- [x] 2.2 Reutilizar el editor de posición para alta y edición. Verificar en el navegador que se precargan los valores y que una pieza guardada con cero unidades se conserva al recargar.
- [x] 2.3 Agregar búsqueda exacta por número de parte normalizado. Verificar resultados en dos posiciones, enlaces a los racks y el estado sin coincidencias.

## 3. Verificación del ejercicio

- [x] 3.1 Recorrer en el entorno Herd y Docker el alta, la búsqueda y la corrección de existencias con la pieza FIL-001. Actualizar el README con el flujo implementado y comprobar cada paso.
