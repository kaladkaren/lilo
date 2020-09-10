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

  public function get_regions()
  {
    $get_cities_link = base_url('api/get-cities/');
    return $this->db->query("
      SELECT {$this->provinces}.region as name
      FROM {$this->provinces} 
      GROUP BY {$this->provinces}.region
      ORDER BY {$this->provinces}.order_no, {$this->provinces}.region ASC
    ")->result();
  }

  public function get_cities($region)
  {
    return $this->db->query("
      SELECT  {$this->provinces}.name,
              {$this->provinces}.region, 
              {$this->provinces}.key_abbr, 
              CASE WHEN {$this->table}.is_city = 1 THEN CONCAT({$this->table}.name, ' City') 
              ELSE CONCAT({$this->table}.name, ', ', {$this->provinces}.name) 
              END as name 
      FROM {$this->provinces} 
      JOIN {$this->table} ON {$this->table}.province_of = {$this->provinces}.key_abbr 
      WHERE {$this->provinces}.region = '$region'
      ORDER BY name ASC
    ")->result();
  }
}