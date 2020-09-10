<?php

class Admin_model extends Admin_core_model
{

  function __construct()
  {
    parent::__construct();

    $this->table = 'admin'; # Replace these properties on children
    $this->upload_dir = 'admin'; # Replace these properties on children
    $this->per_rows = 10;
  }

  public function all()
  {
    $limit_str = '';

    $limit = 0;
    if($this->uri->segment(3) !== NULL){
      $limit = $this->uri->segment(3);
    }
    $limit_str = "LIMIT {$this->per_rows} OFFSET {$limit}";

    $query = $this->db->query("
      SELECT {$this->table}.*, 
             DATE_FORMAT({$this->table}.created_at, '%M %d, %Y') as f_created_at
      FROM {$this->table}
      WHERE is_deleted = 0
      {$limit_str}
      ");
    $res = $query->result();
    return $res;
  }

  public function all_total()
  {
    $query = $this->db->query("
      SELECT {$this->table}.*, 
             DATE_FORMAT({$this->table}.created_at, '%M %d, %Y') as f_created_at
      FROM {$this->table}
      WHERE is_deleted = 0
      ");
    $res = $query->num_rows();
    return $res;
  }
  public function displayPageData($total)
  {
      if ($total) {
        $from = 1;
        if($this->uri->segment(3))
        {
          $from = ($this->uri->segment(3)/$this->per_rows)+1;
        }
        $total = ceil($total/$this->per_rows);
      }else{
        $from = 0;
      }
      return 'Page '.$from.' of '. $total;

    }

    public function displayCountingData($total)
  {
      $from = 0;
      $to = 0;
      if ($total) {
        # code...
        if($this->uri->segment(3) !== FALSE)
        {
          $from = $this->uri->segment(3);
        }
        $to = $from + $this->per_rows;
        if ($to > $total) {
          $to = $total;
        }
        $from +=1;
      }
      return 'Displaying '.$from.'-'.$to.' of '.$total;
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
