<?php

class Division_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'division';
    }
    public function get_all()
    {
        return $this->db->query("
          SELECT {$this->table}.id, 
          		 {$this->table}.name
          FROM {$this->table}
          WHERE {$this->table}.is_active = 1
          ORDER BY {$this->table}.name ASC
          ")->result();
    }
}