<?php
class Rack_model extends CI_Model {
    public $tabla = 'rack';

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

    /**
     * Obtiene el valor máximo insertado
     * @return [type]
     */
    public function obtenerMaxId()
    {
        $sql = 'SELECT max(id_rack) AS max FROM ' . $this->tabla . ' WHERE 1=1';
    
        $query  = $this->db->query($sql);
        $result = $query->row_array();

        return isset($result['max']) ? $result['max'] : 0;
    }

    /**
     * Obtener todos los registros de la tabla
     * @return [type]
     */
    public function obtenerRacks()
    {
        $sql        = 'SELECT * FROM ' . $this->tabla . ' WHERE 1=1';
    
        $query  = $this->db->query($sql);
        $result = $query->result_object();

        return $result;
    }

}

