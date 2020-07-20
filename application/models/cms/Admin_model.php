<?php

class Admin_model extends Admin_core_model
{

  function __construct()
  {
    parent::__construct();

    $this->table = 'admin'; # Replace these properties on children
    $this->upload_dir = 'admin'; # Replace these properties on children
    $this->per_page = 15;
  }

  public function all()
  {
    $query = $this->db->query("
      SELECT {$this->table}.*, 
             DATE_FORMAT({$this->table}.created_at, '%M %d, %Y') as f_created_at
      FROM {$this->table}
      WHERE is_deleted = 0
      ");
    $res = $query->result();
    return $res;
  }

  public function delete($admin_id)
  {
  	return $this->db->update($this->table, array('is_deleted' => 1), array('id' => $admin_id));
  }

  public function check_if_email_exists($email, $id = '')
  {
    $where = '';
    if($id):
      $where = "AND id != '{$id}'";
    endif;

    return $query = $this->db->query("
      SELECT {$this->table}.*
      FROM {$this->table}
      WHERE email = '{$email}' AND is_deleted = 0 {$where}
      ")->num_rows();
  }


}
