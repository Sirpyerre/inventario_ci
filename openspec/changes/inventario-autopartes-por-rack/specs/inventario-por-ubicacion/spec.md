## Purpose

Permitir que el personal de un almacén de refacciones ubique una autoparte por número de parte y consulte o corrija su existencia en una posición física.

## ADDED Requirements

### Requirement: Consulta del contenido de un rack

El sistema SHALL permitir abrir cada rack desde el listado y mostrar sus posiciones con coordenadas, número de parte, descripción y unidades cuando correspondan.

#### Scenario: Rack existente con cantidades sin identificar

- **WHEN** el operador abre un rack anterior al cambio
- **THEN** se muestran sus posiciones y cantidades originales con el estado «Sin pieza asignada»

### Requirement: Captura y edición por posición

El sistema SHALL permitir asociar una pieza por posición durante el alta o después de guardar el rack. Una posición con pieza requiere número de parte, descripción y unidades enteras no negativas. Una posición nueva puede quedar vacía con cero unidades. Los valores existentes SHALL aparecer en los campos al reabrir el editor.

#### Scenario: Editar una existencia guardada

- **WHEN** el operador abre la posición N2-B que contiene seis unidades de FIL-001 y cambia el conteo a cero
- **THEN** el editor muestra los datos previos y, al confirmar, la posición conserva la pieza con cero unidades incluso al recargar

#### Scenario: Rechazar una cantidad inválida

- **WHEN** el operador intenta guardar texto, una fracción o un número negativo como cantidad
- **THEN** el sistema muestra un error comprensible y conserva los datos guardados previamente

#### Scenario: Falla al crear un rack

- **WHEN** falla la escritura de alguna posición durante el alta
- **THEN** el sistema informa el error y no guarda el rack parcial

### Requirement: Búsqueda por número de parte

El sistema SHALL buscar por número de parte exacto ignorando espacios exteriores y diferencias de mayúsculas. Cada coincidencia SHALL mostrar descripción, rack, nivel, columna y unidades, incluidas las posiciones con cero unidades.

#### Scenario: Una pieza en dos posiciones

- **WHEN** el operador busca FIL-001 y la pieza está asignada a dos posiciones
- **THEN** el sistema muestra ambas filas y permite abrir sus racks

#### Scenario: Búsqueda sin coincidencias

- **WHEN** el operador busca un número de parte que no está asignado
- **THEN** el sistema muestra un mensaje de que no hay coincidencias
