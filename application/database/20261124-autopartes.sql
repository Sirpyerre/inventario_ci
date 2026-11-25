ALTER TABLE ubicacion
  ADD COLUMN numero_parte VARCHAR(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  ADD COLUMN descripcion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  ADD INDEX idx_ubicacion_numero_parte (numero_parte);
