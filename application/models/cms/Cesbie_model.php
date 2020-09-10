<?php

class Cesbie_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cesbie_visitors';
        $this->staffs = 'staffs';
        $this->division = 'division';
        $this->feedbacks = 'feedbacks';
        $this->per_rows = 10;
    }
    public function get($id)
    {
    	$limit_str = '';

	    $limit = 0;
	    if($this->uri->segment(6) !== NULL){
	      $limit = $this->uri->segment(6);
	    }
	    $limit_str = "LIMIT {$this->per_rows} OFFSET {$limit}";	

    	return $this->db->query("
      		SELECT {$this->staffs}.*, 
            	DATE_FORMAT({$this->staffs}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->division}.name as division_name,
            	{$this->table}.health_condition,
            	{$this->table}.temperature,
            	CASE
				    WHEN {$this->table}.place_of_origin != '' THEN {$this->table}.place_of_origin
				    ELSE CONCAT({$this->table}.region, ' - ', {$this->table}.city)
			   	END as place_of_origin,
            	{$this->table}.created_at as login,
            	{$this->table}.logout_at as logout,
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as login_timestamp,
            	DATE_FORMAT({$this->table}.logout_at, '%M %d, %Y <br>%l:%i:%S %p') as logout_timestamp
      		FROM {$this->staffs}
      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
      		LEFT JOIN {$this->table} ON {$this->table}.staff_id={$this->staffs}.id
      		WHERE {$this->staffs}.id = '{$id}' 
      		ORDER BY  {$this->table}.created_at DESC
      		{$limit_str}
      	")->result();
    }

    public function all_logs_total($id)
    {
    	return $this->db->query("
      		SELECT {$this->staffs}.*, 
            	DATE_FORMAT({$this->staffs}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->division}.name as division_name,
            	{$this->table}.temperature,
            	CASE
				    WHEN {$this->table}.place_of_origin != '' THEN {$this->table}.place_of_origin
				    ELSE CONCAT({$this->table}.region, ' - ', {$this->table}.city)
			   END as place_of_origin,
            	{$this->table}.created_at as login,
            	{$this->table}.logout_at as logout,
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y %l:%i:%S %p') as login_timestamp,
            	DATE_FORMAT({$this->table}.logout_at, '%M %d, %Y %l:%i:%S %p') as logout_timestamp
      		FROM {$this->staffs}

      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
      		LEFT JOIN {$this->table} ON {$this->table}.staff_id={$this->staffs}.id
      		WHERE {$this->staffs}.id = '{$id}'
      	")->num_rows();
    }
    public function all()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	    	$where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;

	    if (isset($_GET['cat']) && $_GET['cat'] != ''):
	    	$where .= "AND {$this->staffs}.division_id = '{$_GET['cat']}' ";
	    endif;

	    if (isset($_GET['origin']) && $_GET['origin'] != ''):
	    	$where .= "AND ({$this->table}.place_of_origin = '{$_GET['origin']}' OR CONCAT({$this->table}.region, ' - ', {$this->table}.city) = '{$_GET['origin']}')  ";
	    endif;

	    if ((isset($_GET['from']) && $_GET['from'] != '') || (isset($_GET['to']) && $_GET['to'] != '')):
	       	if((isset($_GET['from']) && $_GET['from'] != '') && (!isset($_GET['to']) || $_GET['to'] == '' )): #from only
	       		$from = $_GET['from'] .' 00:00:00';
		    	$to = $_GET['from'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
	    	elseif((isset($_GET['to']) && $_GET['to'] != '') && (!isset($_GET['from']) || $_GET['from'] == '' )): #to only
	    		$from = $_GET['to'] .' 00:00:00';
		    	$to = $_GET['to'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
	    	else: #from and to
		    	if ($_GET['from'] <= $_GET['to']) {
			    	$from = $_GET['from'] .' 00:00:00';
			    	$to = $_GET['to'] .' 23:59:59';
		    	}else{
		    		$from = $_GET['to'] .' 00:00:00';
		    		$to = $_GET['from'] .' 23:59:59';
		    	}
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
	    	endif;
	    endif;
	    // var_dump($where);die();
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
      			CASE
				    WHEN {$this->table}.place_of_origin != '' THEN {$this->table}.place_of_origin
				    ELSE CONCAT({$this->table}.region, ' - ', {$this->table}.city)
			    END as place_of_origin,
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->staffs}.fullname as staff_fullname,
            	{$this->staffs}.division_id as division,
            	{$this->division}.name as division_name,
            	{$this->table}.logout_at as logout_created_at,
            	CASE
				    WHEN {$this->table}.logout_at != '' THEN DATE_FORMAT({$this->table}.logout_at, '%M %d, %Y <br>%l:%i:%S %p')
				    ELSE '-'
				END as logout_timestamp
      		FROM {$this->table}
      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
      		LEFT JOIN {$this->feedbacks} ON {$this->feedbacks}.pin_code={$this->table}.pin_code
      		{$where} {$order_str} {$limit_str}
      	")->result();
  	}
  	public function all_total()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	    	$where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;

	    if (isset($_GET['cat']) && $_GET['cat'] != ''):
	    	$where .= "AND {$this->staffs}.division_id = '{$_GET['cat']}' ";
	    endif;

	    if (isset($_GET['origin']) && $_GET['origin'] != ''):
	    	$where .= "AND ({$this->table}.place_of_origin = '{$_GET['origin']}' OR CONCAT({$this->table}.region, ' - ', {$this->table}.city) = '{$_GET['origin']}')  ";
	    endif;

	    if ((isset($_GET['from']) && $_GET['from'] != '') || (isset($_GET['to']) && $_GET['to'] != '')):
	       	if((isset($_GET['from']) && $_GET['from'] != '') && (!isset($_GET['to']) || $_GET['to'] == '' )): #from only
	       		$from = $_GET['from'] .' 00:00:00';
		    	$to = $_GET['from'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
	    	elseif((isset($_GET['to']) && $_GET['to'] != '') && (!isset($_GET['from']) || $_GET['from'] == '' )): #to only
	    		$from = $_GET['to'] .' 00:00:00';
		    	$to = $_GET['to'] .' 23:59:59';
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
	    	else: #from and to
		    	if ($_GET['from'] <= $_GET['to']) {
			    	$from = $_GET['from'] .' 00:00:00';
			    	$to = $_GET['to'] .' 23:59:59';
		    	}else{
		    		$from = $_GET['to'] .' 00:00:00';
		    		$to = $_GET['from'] .' 23:59:59';
		    	}
		    	$where .= "AND {$this->table}.created_at >= '{$from}' AND {$this->table}.created_at <= '{$to}'";
	    	endif;
	    endif;

    	return $this->db->query("
      		SELECT {$this->table}.*, 
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->staffs}.fullname as staff_fullname,
            	{$this->staffs}.division_id as division,
            	{$this->division}.name as division_name,
            	{$this->table}.logout_at as logout_created_at,
            	CASE
				    WHEN {$this->table}.logout_at != '' THEN DATE_FORMAT({$this->table}.logout_at, '%M %d, %Y <br>%l:%i:%S %p')
				    ELSE '-'
				END as logout_timestamp
      		FROM {$this->table}
      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.staff_id
      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
      		LEFT JOIN {$this->feedbacks} ON {$this->feedbacks}.pin_code={$this->table}.pin_code
      		{$where}
      	")->num_rows();
  	}
    public function all_staff()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	    	$where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;

	    if (isset($_GET['cat']) && $_GET['cat'] != ''):
	    	$where .= "AND {$this->staffs}.division_id = '{$_GET['cat']}' ";
	    endif;

	    $order_str = '';

	    $order_by = "{$this->staffs}.created_at";
	    if (isset($_GET['order_by']) && $_GET['order_by']):
	      switch ($_GET['order_by']) {
	        case 'name':
	          $order_by = "{$this->staffs}.fullname";
	          break;
	        case 'date_reg':
	        default:
	          $order_by = "{$this->staffs}.created_at";
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
	    if($this->uri->segment(4) !== NULL){
	      $limit = $this->uri->segment(4);
	    }
	    $limit_str = "LIMIT {$this->per_rows} OFFSET {$limit}";	
    	return $this->db->query("
      		SELECT {$this->staffs}.*, 
            	DATE_FORMAT({$this->staffs}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->division}.name as division_name
      		FROM {$this->staffs}

      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
      		{$where} {$order_str} {$limit_str}
      	")->result();
  	}
  	public function all_staff_total()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	      $where .= "AND {$this->staffs}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;
	    if (isset($_GET['cat']) && $_GET['cat'] != ''):
	      $where .= "AND {$this->staffs}.division_id = '{$_GET['cat']}' ";
	    endif;

    	return $this->db->query("
      		SELECT {$this->staffs}.*, 
            	DATE_FORMAT({$this->staffs}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at
      		FROM {$this->staffs}
      		{$where}
      	")->num_rows();
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
  	public function displayPageData__($total)
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

  	public function displayCountingData__($total)
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
  	public function displayPageData_($total)
	{
	    if ($total) {
	      $from = 1;
	      if($this->uri->segment(6))
	      {
	        $from = ($this->uri->segment(6)/$this->per_rows)+1;
	      }
	      $total = ceil($total/$this->per_rows);
	    }else{
	      $from = 0;
	    }
	    return 'Page '.$from.' of '. $total;

  	}

  	public function displayCountingData_($total)
 	{
	    $from = 0;
	    $to = 0;
	    if ($total) {
	      # code...
	      if($this->uri->segment(6) !== FALSE)
	      {
	        $from = $this->uri->segment(6);
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
	    if($param == 'from'):
	    	unset( $parameters['to'] );               // Delete the one you want
	    endif;
	    unset( $parameters[$param] );               // Delete the one you want
	    unset( $parameters['srch'] );               // Delete the one you want
	    $new_query = http_build_query($parameters); // Rebuilt query string
	    return $current_page.'?srch=1&'.$new_query;            // Finally url is ready
	}
	public function get_cities()
	{
		return $this->db->query("
      		SELECT 
      			-- {$this->table}.place_of_origin,
      			CASE
				    WHEN {$this->table}.place_of_origin != '' THEN {$this->table}.place_of_origin
				    ELSE CONCAT({$this->table}.region, ' - ', {$this->table}.city)
			    END as place_of_origin
      		FROM {$this->table}
      		GROUP BY {$this->table}.place_of_origin, CONCAT({$this->table}.region, ' - ', {$this->table}.city)
      		ORDER BY place_of_origin  ASC
      	")->result();
	}

	public function add_staff($post)
	{
		return $this->db->insert($this->staffs, $post);
	}
	public function update_staff($post, $id)
  	{
    	$this->db->where('id', $id);
    	return $this->db->update($this->staffs, $post);
  	}
  	public function get_all_staff($select = '')
    {	
    	$select_str = "{$this->staffs}.*";
    	if($select):
    		$select_str = '';
    		foreach ($select as $key => $value) {
    			$select_str .= "{$this->staffs}.{$value}, ";
    		}
    		$select_str = rtrim($select_str, ', ');
    	endif;

        return $this->db->query("
          SELECT {$select_str}
          FROM {$this->staffs}
          WHERE {$this->staffs}.is_active = 1
          ORDER BY {$this->staffs}.fullname ASC
          ")->result();
    }
}