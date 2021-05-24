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
          ORDER BY {$this->table}.name ASC
          ")->result();
    }

    public function getByDivision($division_id)
    {
      $this->db->select('id,name');
      $this->db->where('division_id', $division_id);
      return $this->db->get('services')->result();
    }
}