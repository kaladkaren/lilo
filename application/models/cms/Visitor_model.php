<?php

class Visitor_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->guest_visitors = 'guest_visitors';
        $this->table = 'cesbie_visitors';
        $this->staffs = 'staffs';
        $this->division = 'division';
        $this->per_rows = 5;
    }
    public function all()
  	{
  		$where = ' WHERE 1=1 ';
  		$where2 = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	      $where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	      $where2 .= "AND {$this->guest_visitors}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;

	    if (isset($_GET['origin']) && $_GET['origin'] != ''):
	    	$where .= "AND {$this->table}.place_of_origin = '{$_GET['origin']}' ";
	    	$where2 .= "AND {$this->guest_visitors}.place_of_origin = '{$_GET['origin']}' ";
	    endif;

	    if ((isset($_GET['from']) && $_GET['from'] != '') || (isset($_GET['to']) && $_GET['to'] != '')):
	       	if((isset($_GET['from']) && $_GET['from'] != '') && (!isset($_GET['to']) || $_GET['to'] == '' )): #from only
	       		$from = $_GET['from'] .' 00:00:00';
		    	$to = $_GET['from'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
		    	$where2 .= "AND {$this->guest_visitors}.created_at >= '{$from}' AND {$this->guest_visitors}.created_at <= '{$to}'";
	    	elseif((isset($_GET['to']) && $_GET['to'] != '') && (!isset($_GET['from']) || $_GET['from'] == '' )): #to only
	    		$from = $_GET['to'] .' 00:00:00';
		    	$to = $_GET['to'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
		    	$where2 .= "AND {$this->guest_visitors}.created_at >= '{$from}' AND {$this->guest_visitors}.created_at <= '{$to}'";
	    	else: #from and to
		    	if ($_GET['from'] <= $_GET['to']) {
			    	$from = $_GET['from'] .' 00:00:00';
			    	$to = $_GET['to'] .' 23:59:59';
		    	}else{
		    		$from = $_GET['to'] .' 00:00:00';
		    		$to = $_GET['from'] .' 23:59:59';
		    	}
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
		    	$where2 .= "AND {$this->guest_visitors}.created_at >= '{$from}' AND {$this->guest_visitors}.created_at <= '{$to}'";
	    	endif;
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

	    $order_str2 = '';

	    $order_by2 = "{$this->guest_visitors}.created_at";
	    if (isset($_GET['order_by']) && $_GET['order_by']):
	      switch ($_GET['order_by']) {
	        case 'name':
	          $order_by2 = "{$this->guest_visitors}.fullname";
	          break;
	        case 'date_reg':
	        default:
	          $order_by2 = "{$this->guest_visitors}.created_at";
	          break;
	          break;
	      }
	    endif;

	    $order2 = 'DESC';
	    if (isset($_GET['order']) && $_GET['order']):
	      if ($_GET['order'] == 'asc'):
	        $order2 = 'ASC';
	      endif;
	    endif;

	    $order_str2 = "ORDER BY {$order_by2} {$order2}";

	    $limit_str = '';
	    $limit_str2 = '';

	    $limit = 0;
	    if($this->uri->segment(4) !== NULL){
	      $limit = $this->uri->segment(4);
	    }
	    $limit_str = "LIMIT {$this->per_rows} OFFSET {$limit}";	

	    $limit2 = 0;
	    if($this->uri->segment(4) !== NULL){
	      $limit2 = $this->uri->segment(4);
	    }
	    $limit_str2 = "LIMIT {$this->per_rows} OFFSET {$limit2}";

	    if ((isset($_GET['v_type']) && strtolower($_GET['v_type']) == 'all') || !isset($_GET['v_type'])) {
	    	$sql = "
				SELECT {$this->table}.id,
	      			   {$this->table}.temperature, 
	      			   {$this->table}.place_of_origin,
	      			   {$this->staffs}.fullname as staff_fullname,
	      			   {$this->table}.created_at,
	      			   {$this->table}.pin_code,
	      			   DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'CESBIE' as visitor_type
	      		FROM {$this->table} 
	      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
				{$where}

				UNION 

				SELECT {$this->guest_visitors}.id, 
					   {$this->guest_visitors}.temperature, 
					   {$this->guest_visitors}.place_of_origin, 
					   {$this->guest_visitors}.fullname as staff_fullname, 
					   {$this->guest_visitors}.created_at,
					   {$this->guest_visitors}.pin_code,
	      			   DATE_FORMAT({$this->guest_visitors}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'GUEST' as visitor_type
				FROM {$this->guest_visitors}
				{$where2}

				ORDER BY created_at DESC
				{$limit_str}
      	";
	    }elseif(strtolower($_GET['v_type']) == 'cesbie'){
	    	$sql = "
				SELECT {$this->table}.id,
	      			   {$this->table}.temperature, 
	      			   {$this->table}.place_of_origin,
	      			   {$this->staffs}.fullname as staff_fullname,
	      			   {$this->table}.created_at,
	      			   {$this->table}.pin_code,
	      			   DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'CESBIE' as visitor_type
	      		FROM {$this->table} 
	      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
				{$where} {$order_str} {$limit_str}";
	    }else{
	    	$sql = "
				SELECT {$this->guest_visitors}.id, 
					   {$this->guest_visitors}.temperature, 
					   {$this->guest_visitors}.place_of_origin, 
					   {$this->guest_visitors}.fullname as staff_fullname, 
					   {$this->guest_visitors}.created_at,
					   {$this->guest_visitors}.pin_code,
	      			   DATE_FORMAT({$this->guest_visitors}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'GUEST' as visitor_type
				FROM {$this->guest_visitors}
				{$where2} {$order_str2} {$limit_str2}";
	    }
    	$res = $this->db->query($sql)->result();
    	return $res;
  	}
  	public function all_total()
  	{
  		$where = ' WHERE 1=1 ';
  		$where2 = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	      $where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	      $where2 .= "AND {$this->guest_visitors}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;

	    if (isset($_GET['origin']) && $_GET['origin'] != ''):
	    	$where .= "AND {$this->table}.place_of_origin = '{$_GET['origin']}' ";
	    	$where2 .= "AND {$this->guest_visitors}.place_of_origin = '{$_GET['origin']}' ";
	    endif;

	    if ((isset($_GET['from']) && $_GET['from'] != '') || (isset($_GET['to']) && $_GET['to'] != '')):
	       	if((isset($_GET['from']) && $_GET['from'] != '') && (!isset($_GET['to']) || $_GET['to'] == '' )): #from only
	       		$from = $_GET['from'] .' 00:00:00';
		    	$to = $_GET['from'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
		    	$where2 .= "AND {$this->guest_visitors}.created_at >= '{$from}' AND {$this->guest_visitors}.created_at <= '{$to}'";
	    	elseif((isset($_GET['to']) && $_GET['to'] != '') && (!isset($_GET['from']) || $_GET['from'] == '' )): #to only
	    		$from = $_GET['to'] .' 00:00:00';
		    	$to = $_GET['to'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
		    	$where2 .= "AND {$this->guest_visitors}.created_at >= '{$from}' AND {$this->guest_visitors}.created_at <= '{$to}'";
	    	else: #from and to
		    	if ($_GET['from'] <= $_GET['to']) {
			    	$from = $_GET['from'] .' 00:00:00';
			    	$to = $_GET['to'] .' 23:59:59';
		    	}else{
		    		$from = $_GET['to'] .' 00:00:00';
		    		$to = $_GET['from'] .' 23:59:59';
		    	}
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
		    	$where2 .= "AND {$this->guest_visitors}.created_at >= '{$from}' AND {$this->guest_visitors}.created_at <= '{$to}'";
	    	endif;
	    endif;

	    if ((isset($_GET['v_type']) && strtolower($_GET['v_type']) == 'all') || !isset($_GET['v_type'])) {
	    	$sql = "
				SELECT {$this->table}.id,
	      			   {$this->table}.temperature, 
	      			   {$this->table}.place_of_origin,
	      			   {$this->staffs}.fullname as staff_fullname,
	      			   {$this->table}.created_at,
	      			   {$this->table}.pin_code,
	      			   DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'CESBIE' as visitor_type
	      		FROM {$this->table} 
	      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
				{$where}

				UNION 

				SELECT {$this->guest_visitors}.id, 
					   {$this->guest_visitors}.temperature, 
					   {$this->guest_visitors}.place_of_origin, 
					   {$this->guest_visitors}.fullname as staff_fullname, 
					   {$this->guest_visitors}.created_at,
					   {$this->guest_visitors}.pin_code,
	      			   DATE_FORMAT({$this->guest_visitors}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'GUEST' as visitor_type
				FROM {$this->guest_visitors}
				{$where2}

				ORDER BY created_at DESC
				-- LIMIT 0,10
      	";
	    }elseif(strtolower($_GET['v_type']) == 'cesbie'){
	    	$sql = "
				SELECT {$this->table}.id,
	      			   {$this->table}.temperature, 
	      			   {$this->table}.place_of_origin,
	      			   {$this->staffs}.fullname as staff_fullname,
	      			   {$this->table}.created_at,
	      			   {$this->table}.pin_code,
	      			   DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'CESBIE' as visitor_type
	      		FROM {$this->table} 
	      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
				{$where}";
	    }else{
	    	$sql = "
				SELECT {$this->guest_visitors}.id, 
					   {$this->guest_visitors}.temperature, 
					   {$this->guest_visitors}.place_of_origin, 
					   {$this->guest_visitors}.fullname as staff_fullname, 
					   {$this->guest_visitors}.created_at,
					   {$this->guest_visitors}.pin_code,
	      			   DATE_FORMAT({$this->guest_visitors}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
	      			   'GUEST' as visitor_type
				FROM {$this->guest_visitors}
				{$where2}";
	    }

    	return $this->db->query($sql)->num_rows();
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
  	public function get_cities()
	{
		$sql = "SELECT {$this->table}.place_of_origin
	      		FROM {$this->table}

				UNION 

				SELECT {$this->guest_visitors}.place_of_origin
				FROM {$this->guest_visitors}

				GROUP BY place_of_origin
      			ORDER BY place_of_origin ASC
				";
		return $this->db->query($sql)->result();
	}
}