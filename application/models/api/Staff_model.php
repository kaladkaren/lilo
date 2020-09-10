<?php

class Staff_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'staffs';
    }
    public function get_all()
    {
        return $this->db->query("
          SELECT {$this->table}.id, 
          		 {$this->table}.fullname
          FROM {$this->table}
          WHERE {$this->table}.is_active = 1
          ORDER BY {$this->table}.fullname ASC
          ")->result();
    }
}