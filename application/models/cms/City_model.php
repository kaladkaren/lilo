<?php

class City_model extends Admin_core_model
{

  function __construct()
  {
    parent::__construct();

    $this->table = 'cities';
    $this->provinces = 'provinces';
  }

  public function get_all()
    {
    return $this->db->query("
    	SELECT 
               CASE WHEN {$this->table}.is_city = 1 THEN CONCAT({$this->table}.name, ' City') 
               ELSE CONCAT({$this->table}.name, ', ', {$this->provinces}.name) 
               END as name 
    	FROM {$this->table} 
      JOIN {$this->provinces} ON {$this->table}.province_of = {$this->provinces}.key_abbr 
    	ORDER BY {$this->table}.name ASC
    ")->result();
  }
}