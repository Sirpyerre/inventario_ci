<?php
class Ubicacion_model extends CI_Model {
    public $tabla = 'ubicacion';

    /**
     * Metodo para insertar
     * @param  [type]
     * @return [El id que se insertó]
     */
    public function crear($datos)
    {
        if (!empty($datos)) 
        {
            $fechaHoy = date('Y-m-d H:i:s');
            $datos['fecha_creacion'] = $fechaHoy;
            $this->db->insert($this->tabla, $datos);

            return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
        }


    }
}

