<?php

class Service_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'services';
    }
    public function get_all()
    {
        return $this->db->query("
          SELECT {$this->table}.id, 
          		 {$this->table}.name
          FROM {$this->table}
          WHERE {$this->table}.is_active = 1
          ")->result();
    }
}