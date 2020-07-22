<?php

class Cesbie_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cesbie_visitors';
        $this->staffs = 'staffs';
    }
    public function all()
  	{
    	return $this->db->query("
      		SELECT {$this->table}.*, 
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->staffs}.fullname as staff_fullname
      		FROM {$this->table}
      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
      	")->result();
  	}

}