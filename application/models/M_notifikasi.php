<?php

class M_notifikasi extends CI_Model
{
    private $table = 'notifikasi';

    public function getData($id = null)
    {
        if ($id === null)
            return $this->db->get($this->table)->result_array();
        else
            return $this->db->get_where($this->table, ['id_notifikasi' => $id])->result_array();
    }

    public function insert_data($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows();
    }

    public function update_data($data, $id)
    {
        $this->db->update($this->table, $data, ['id_notifikasi' => $id]);
        return $this->db->affected_rows();
    }

    public function delete_data($id)
    {
        $this->db->delete($this->table, ['id_notifikasi' => $id]);
        return $this->db->affected_rows();
    }
}