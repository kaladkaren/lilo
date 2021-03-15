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

  /**
   * get City OR Province
   * @param  [type]  $region  [description]
   * @param  integer $is_city [description]
   * @return [type]           [description]
   */
  function get_cp($region)
  {
    // if ($is_city) {
    // $this->db->select('CONCAT(cities.name, " City") as name');
    // } else {
      $this->db->select('cities.name as name');
    // }
    $this->db->order_by('cities.name', 'asc');
    $this->db->where('provinces.region', $region);
    // $this->db->where('cities.is_city', $is_city);
    $this->db->join('provinces', 'cities.province_of = provinces.key_abbr', 'left');
    return $this->db->get('cities')->result();
  }

  function get_provinces($region)
  {
    $this->db->select('name');
    $this->db->order_by('name', 'asc');
    $this->db->where('region', $region);
    return $this->db->get('provinces')->result();
  }

  public function get_cities($province)
  {
    $this->db->where('name', $province);
    $key_abbr = $this->db->get('provinces')->row()->key_abbr;

    $this->db->select('name');
    $this->db->order_by('name', 'asc');
    $this->db->where('province_of', $key_abbr);
    return $this->db->get('cities')->result();
    // return $this->db->query("
    //   SELECT  {$this->provinces}.name,
    //           {$this->provinces}.region,
    //           {$this->provinces}.key_abbr,
    //           CASE WHEN {$this->table}.is_city = 1 THEN CONCAT({$this->table}.name, ' City')
    //           ELSE CONCAT({$this->table}.name, ', ', {$this->provinces}.name)
    //           END as name
    //   FROM {$this->provinces}
    //   JOIN {$this->table} ON {$this->table}.province_of = {$this->provinces}.key_abbr
    //   WHERE {$this->provinces}.region = '$region'
    //   ORDER BY name ASC
    // ")->result();
  }

  public function get_provinces_and_cities()
  {
    $this->db->select('cities.id as city_id, provinces.id as province_id, CONCAT(provinces.name, ", ", cities.name) as province_and_city, provinces.name as province_name, cities.name as cities_name');
    $this->db->join('provinces', 'cities.province_of = provinces.key_abbr', 'left');
    $this->db->order_by('provinces.order_no', 'asc');
    $res = $this->db->get('cities')->result();
    return $res;

    // $this->db->where('name', $province);
    // $key_abbr = $this->db->get('provinces')->row()->key_abbr;
    //
    // $this->db->select('name');
    // $this->db->order_by('name', 'asc');
    // $this->db->where('province_of', $key_abbr);
    // $res = $this->db->get('cities')->result();
  }


}
