<?php

class M_user extends CI_Model
{
    private $user = 'user';

    public function doLogin($username, $password)
    {
        $query = $this->db->get_where($this->user, ['username' => $username, 'password' => $password]);
        if ($query->num_rows() == 1)
            return $query->result();
        else return false;
    }
}