<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ubicacion_model extends CI_Model
{
    public function crearVarias($rack, array $filas)
    {
        $fecha = date('Y-m-d H:i:s');
        foreach ($filas as &$fila) {
            $fila += ['id_rack' => $rack, 'fecha_creacion' => $fecha];
        }
        unset($fila);

        return $this->db->insert_batch('ubicacion', $filas, null, count($filas)) === count($filas);
    }

    public function porRack($rack)
    {
        return $this->db->where('id_rack', $rack)->order_by('nivel', 'DESC')
            ->order_by('CHAR_LENGTH(ubicacion)', 'ASC', false)->order_by('ubicacion')
            ->get('ubicacion')->result();
    }

    public function obtener($rack, $id)
    {
        return $this->db->get_where('ubicacion', ['id_rack' => $rack, 'id_ubicacion' => $id])->row();
    }

    public function actualizar($rack, $id, array $datos)
    {
        return $this->db->where(['id_rack' => $rack, 'id_ubicacion' => $id])->update('ubicacion', $datos);
    }

    public function buscar($numero)
    {
        return $this->db->select('ubicacion.*, rack.nombre')
            ->join('rack', 'rack.id_rack = ubicacion.id_rack')
            ->where('ubicacion.numero_parte', $numero)
            ->order_by('ubicacion.id_rack')->order_by('ubicacion.nivel', 'DESC')
            ->order_by('CHAR_LENGTH(ubicacion.ubicacion)', 'ASC', false)->order_by('ubicacion.ubicacion')
            ->get('ubicacion')->result();
    }
}
