<?php

class Agency_model extends Crud_model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'agency';
        $this->attached_agency = 'attached_agency';
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
    public function get_options($agency_id)
    {
        return $this->db->query("
          SELECT {$this->attached_agency}.id,
               {$this->attached_agency}.name
          FROM {$this->attached_agency}
          WHERE {$this->attached_agency}.is_active = 1 AND {$this->attached_agency}.agency_id = '{$agency_id}'
          ORDER BY {$this->attached_agency}.name ASC
          ")->result();
    }

    public function get_all_other_attached_agencies()
    {
      $this->db->select('attached_agency_others as name');
      $this->db->where('attached_agency_others != ""');
      $this->db->group_by('attached_agency_others');
      return $this->db->get('guest_visitors')->result();
    }
}
