<?php

class Cesbie_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cesbie_visitors';
        $this->staffs = 'staffs';
        $this->division = 'division';
        $this->per_rows = 5;
    }
    public function all()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name'])):
	      $where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;
	    if (isset($_GET['cat'])):
	      $where .= "AND {$this->staffs}.division_id = '{$_GET['cat']}' ";
	    endif;

	    $order_str = '';

	    $order_by = "{$this->table}.created_at";
	    if (isset($_GET['order_by']) && $_GET['order_by']):
	      switch ($_GET['order_by']) {
	        case 'name':
	          $order_by = "{$this->staffs}.fullname";
	          break;
	        case 'date_reg':
	        default:
	          $order_by = "{$this->table}.created_at";
	          break;
	          break;
	      }
	    endif;

	    $order = 'DESC';
	    if (isset($_GET['order']) && $_GET['order']):
	      if ($_GET['order'] == 'asc'):
	        $order = 'ASC';
	      endif;
	    endif;

	    $order_str = "ORDER BY {$order_by} {$order}";

	    $limit_str = '';

	    $limit = 0;
	    if($this->uri->segment(5) !== NULL){
	      $limit = $this->uri->segment(5);
	    }
	    $limit_str = "LIMIT {$this->per_rows} OFFSET {$limit}";	
    	return $this->db->query("
      		SELECT {$this->table}.*, 
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->staffs}.fullname as staff_fullname,
            	{$this->staffs}.division_id as division,
            	{$this->division}.name as division_name
      		FROM {$this->table}
      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
      		{$where} {$order_str} {$limit_str}
      	")->result();
  	}
  	public function all_total()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name'])):
	      $where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;
	    if (isset($_GET['cat'])):
	      $where .= "AND {$this->staffs}.division_id = '{$_GET['cat']}' ";
	    endif;

    	return $this->db->query("
      		SELECT {$this->table}.*, 
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->staffs}.fullname as staff_fullname
      		FROM {$this->table}
      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
      		{$where}
      	")->num_rows();
  	}

  	public function displayPageData($total)
	{
	    if ($total) {
	      $from = 1;
	      if($this->uri->segment(5))
	      {
	        $from = ($this->uri->segment(5)/$this->per_rows)+1;
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
	      if($this->uri->segment(5) !== FALSE)
	      {
	        $from = $this->uri->segment(5);
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