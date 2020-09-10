<?php

class Agency_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'agency';
        $this->table_attached_agency = 'attached_agency';
        $this->per_rows = 10;
    }
    public function all_attached_agency()
    {
        $order_str = '';
        $where = ' WHERE 1=1 ';
        if (isset($_GET['name'])):
            $where .= "AND {$this->table_attached_agency}.name LIKE '%{$_GET['name']}%' ";
        endif;

        if (isset($_GET['cat_agency'])):
            $where .= "AND {$this->table_attached_agency}.agency_id = '{$_GET['cat_agency']}' ";
        endif;

        $order_by = "{$this->table_attached_agency}.name";
        if (isset($_GET['order_by']) && $_GET['order_by']):
          switch ($_GET['order_by']) {
            case 'date_reg':
              $order_by = "{$this->table_attached_agency}.created_at";
              break;
            case 'name':
            default:
              $order_by = "{$this->table_attached_agency}.name";
              break;
          }
        endif;

        $order = 'ASC';
        if (isset($_GET['order']) && $_GET['order']):
          if ($_GET['order'] == 'asc'):
            $order = 'ASC';
          else:
            $order = 'DESC';
          endif;
        endif;

        $order_str = "ORDER BY {$order_by} {$order}";

        $limit_str = '';

        $limit = 0;
        if($this->uri->segment(4) !== NULL){
          $limit = $this->uri->segment(4);
        }
        $limit_str = "LIMIT {$this->per_rows} OFFSET {$limit}"; 

        $sql = "
          SELECT {$this->table_attached_agency}.*, 
                 DATE_FORMAT({$this->table_attached_agency}.created_at, '%M %d, %Y') as f_created_at
          FROM {$this->table_attached_agency} 
          {$where} {$order_str} {$limit_str}
          ";
        $query = $this->db->query($sql);
        $res = $query->result();
        return $res;
    }
    public function all_attached_agency_total()
    {
        $where = ' WHERE 1=1 ';
        if (isset($_GET['name'])):
            $where .= "AND {$this->table_attached_agency}.name LIKE '%{$_GET['name']}%' ";
        endif;
        if (isset($_GET['cat_agency'])):
            $where .= "AND {$this->table_attached_agency}.agency_id = '{$_GET['cat_agency']}' ";
        endif;
        return $this->db->query("
          SELECT {$this->table_attached_agency}.*
          FROM {$this->table_attached_agency} 
          {$where}
          ")->num_rows();
    }
    public function all()
    {
        $order_str = '';
        $where = ' WHERE 1=1 ';
        if (isset($_GET['name'])):
            $where .= "AND {$this->table}.name LIKE '%{$_GET['name']}%' ";
        endif;


        $order_by = "{$this->table}.name";
        if (isset($_GET['order_by']) && $_GET['order_by']):
          switch ($_GET['order_by']) {
            case 'date_reg':
              $order_by = "{$this->table}.created_at";
              break;
            default:
            case 'name':
              $order_by = "{$this->table}.name";
              break;
          }
        endif;

        $order = 'ASC';
        if (isset($_GET['order']) && $_GET['order']):
          if ($_GET['order'] == 'asc'):
            $order = 'ASC';
          else:
            $order = 'DESC';
          endif;
        endif;

        $order_str = "ORDER BY {$order_by} {$order}";

        $limit_str = '';

        $limit = 0;
        if($this->uri->segment(4) !== NULL){
          $limit = $this->uri->segment(4);
        }
        $limit_str = "LIMIT {$this->per_rows} OFFSET {$limit}"; 

        $sql = "
          SELECT {$this->table}.*, 
                 DATE_FORMAT({$this->table}.created_at, '%M %d, %Y') as f_created_at
          FROM {$this->table} 
          {$where} {$order_str} {$limit_str}
          ";

        $query = $this->db->query($sql);
        $res = $query->result();
        return $res;
    }
    public function all_total()
    {
        $where = ' WHERE 1=1 ';
        if (isset($_GET['name'])):
            $where .= "AND {$this->table}.name LIKE '%{$_GET['name']}%' ";
        endif;
        return $this->db->query("
          SELECT {$this->table}.*
          FROM {$this->table} 
          {$where}
          ")->num_rows();
    }
    public function all_agency()
    {
        return $this->db->query("
          SELECT {$this->table}.*
          FROM {$this->table} 
          ORDER BY {$this->table}.name ASC
          ")->result();
    }
    public function add($post)
    {
        $post['is_active'] = 0;
        if(isset($post['is_active'])):
            $post['is_active'] = 1;
        endif;

        return $this->db->insert($this->table, $post);
    }

    public function add_attached_agency($post)
    {
        $post['is_active'] = 0;
        if(isset($post['is_active'])):
            $post['is_active'] = 1;
        endif;

        return $this->db->insert($this->table_attached_agency, $post);
    }
    public function update_attached_agency($post, $id)
    {
        if(isset($post['is_active'])):
            $post['is_active'] = 1;
        else:
            $post['is_active'] = 0;
        endif;


        $this->db->where('id', $id);
        return $this->db->update($this->table_attached_agency, $post);
    }
    public function update($post, $id)
    {
        if(isset($post['is_active'])):
            $post['is_active'] = 1;
        else:
            $post['is_active'] = 0;
        endif;


        $this->db->where('id', $id);
        return $this->db->update($this->table, $post);
    }
    public function displayPageData($total)
      {
        if ($total) {
          $from = 1;
          if($this->uri->segment(4))
          {
            $from = ($this->uri->segment(4)/$this->per_rows)+1;
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
          if($this->uri->segment(4) !== FALSE)
          {
            $from = $this->uri->segment(4);
          }
          $to = $from + $this->per_rows;
          if ($to > $total) {
            $to = $total;
          }
          $from +=1;
        }
        return 'Displaying '.$from.'-'.$to.' of '.$total;
      }
      public function strip_param_from_url( $url, $param, $current_page = '' )
      {
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $base_url = strtok($url, '?');              // Get the base url
        $parsed_url = parse_url($url);              // Parse it 
        $query = @$parsed_url['query'];              // Get the query string
        parse_str( $query, $parameters );           // Convert Parameters into array
        unset( $parameters[$param] );               // Delete the one you want
        unset( $parameters['srch'] );               // Delete the one you want
        $new_query = http_build_query($parameters); // Rebuilt query string
        return $current_page.'?srch=1&'.$new_query;            // Finally url is ready
      }
}