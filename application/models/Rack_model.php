<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rack_model extends CI_Model
{
    public function crear()
    {
        if (!$this->db->insert('rack', ['nombre' => '', 'fecha_creacion' => date('Y-m-d H:i:s')])) {
            return false;
        }

        $id = $this->db->insert_id();
        return $this->db->where('id_rack', $id)->update('rack', ['nombre' => 'R'.$id]) ? $id : false;
    }

    public function obtener($id)
    {
        return $this->db->get_where('rack', ['id_rack' => $id])->row();
    }

    public function obtenerRacks()
    {
        return $this->db->order_by('id_rack')->get('rack')->result();
    }
}
