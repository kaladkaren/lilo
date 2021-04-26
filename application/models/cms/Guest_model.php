<?php

class Guest_model extends Crud_model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'guest_visitors';
        $this->staffs = 'staffs';
        $this->division = 'division';
        $this->feedbacks = 'feedbacks';
        $this->services = 'services';
        $this->agency = 'agency';
        $this->attached_agency = 'attached_agency';
        $this->per_rows = 10;
        $this->uploade_dir_visitors = 'visitors';

        $this->load->model('api/visitor_model');
    }
    public function all()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	      $where .= "AND {$this->table}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;
	    if (isset($_GET['cat']) && $_GET['cat'] != ''):
	      $where .= "AND {$this->table}.division_to_visit = '{$_GET['cat']}' ";
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

	    $order_str = '';

	    $order_by = "{$this->table}.created_at";
	    if (isset($_GET['order_by']) && $_GET['order_by']):
	      switch ($_GET['order_by']) {
	        case 'name':
	          $order_by = "{$this->table}.fullname";
	          break;
	        case 'date_logout':
	        	$order_by = "logout_created_at";
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
	    $upload_photo = base_url('uploads/'.$this->uploade_dir_visitors.'/');
	    $expi_png = base_url('public/admin/img/');
    	$res = $this->db->query("
      		SELECT {$this->table}.*,
      			CASE
				    WHEN {$this->table}.place_of_origin != '' THEN {$this->table}.place_of_origin
				    ELSE CONCAT({$this->table}.region, ' - ', {$this->table}.city)
			   	END as place_of_origin,
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at,
            	{$this->staffs}.fullname as person_fullname_visited,
            	{$this->division}.name as division_name_visited,
            	{$this->agency}.name as agency_name,
            	{$this->attached_agency}.name as att_agency_name,
            	{$this->services}.name as purpose_name,
            	REPLACE(CONCAT('{$upload_photo}', {$this->table}.photo), ' ', '_') as photo,
            	CASE
                WHEN {$this->feedbacks}.overall_experience = '1' THEN 'Bad'
                WHEN {$this->feedbacks}.overall_experience = '2' THEN 'Fair'
                WHEN {$this->feedbacks}.overall_experience = '3' THEN 'Okay'
                WHEN {$this->feedbacks}.overall_experience = '4' THEN 'Good'
                WHEN {$this->feedbacks}.overall_experience = '5' THEN 'Excellent'
				    ELSE ''
				END as overall_experience,
				CASE
          WHEN {$this->feedbacks}.overall_experience = '1' THEN CONCAT('{$expi_png}', 'disastrous_on.png')
          WHEN {$this->feedbacks}.overall_experience = '2' THEN CONCAT('{$expi_png}', 'Stressed.png')
          WHEN {$this->feedbacks}.overall_experience = '3' THEN CONCAT('{$expi_png}', 'Okay.png')
          WHEN {$this->feedbacks}.overall_experience = '4' THEN CONCAT('{$expi_png}', 'Happy.png')
          WHEN {$this->feedbacks}.overall_experience = '5' THEN CONCAT('{$expi_png}', 'excellent_on.png')
				    ELSE ''
				END as overall_experience_png,
            	{$this->feedbacks}.feedback as feedback,
            	CASE
				    WHEN {$this->feedbacks}.created_at != '' THEN DATE_FORMAT({$this->feedbacks}.created_at, '%M %d, %Y <br>%l:%i:%S %p')
				    ELSE '-'
				END as logout_timestamp,
				CASE
				    WHEN {$this->feedbacks}.created_at != '' THEN {$this->feedbacks}.created_at
				    ELSE ''
				END as logout_created_at
      		FROM {$this->table}
      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.person_to_visit
      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->table}.division_to_visit
      		LEFT JOIN {$this->agency} ON {$this->agency}.id={$this->table}.agency
      		LEFT JOIN {$this->attached_agency} ON {$this->attached_agency}.id={$this->table}.attached_agency
      		LEFT JOIN {$this->services} ON {$this->services}.id={$this->table}.purpose
      		LEFT JOIN {$this->feedbacks} ON {$this->feedbacks}.pin_code={$this->table}.pin_code
      		{$where} {$order_str} {$limit_str}
      	")->result();

    	foreach ($res as $value) {
      		$value->person_fullname_visited = $this->visitor_model->get_person_to_visit_concat($value->person_to_visit);
      		$value->purpose_name = $this->visitor_model->get_purpose_concat($value->purpose);
    	}
      	return $res;
  	}
  	public function all_total()
  	{
  		$where = ' WHERE 1=1 ';
	    if (isset($_GET['name']) && $_GET['name'] != ''):
	      $where .= "AND {$this->table}.fullname LIKE '%{$_GET['name']}%' ";
	    endif;
	    if (isset($_GET['cat']) && $_GET['cat'] != ''):
	      $where .= "AND {$this->table}.division_to_visit = '{$_GET['cat']}' ";
	    endif;

	    if (isset($_GET['origin']) && $_GET['origin'] != ''):
	    	$where .= "AND ({$this->table}.place_of_origin = '{$_GET['origin']}' OR CONCAT({$this->table}.region, ' - ', {$this->table}.city) = '{$_GET['origin']}')  ";
	    endif;

    	return $this->db->query("
      		SELECT {$this->table}.*,
            	DATE_FORMAT({$this->table}.created_at, '%M %d, %Y <br>%l:%i:%S %p') as f_created_at
      		FROM {$this->table}
      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->table}.person_to_visit
      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->table}.division_to_visit
      		{$where}
      	")->num_rows();
  	}
  	public function get_cities()
	{
		return $this->db->query("
      		SELECT
      			CASE
				    WHEN {$this->table}.place_of_origin != '' THEN {$this->table}.place_of_origin
				    ELSE CONCAT({$this->table}.region, ' - ', {$this->table}.city)
			   	END as place_of_origin
      		FROM {$this->table}
      		GROUP BY  CONCAT({$this->table}.region, ' - ', {$this->table}.city), {$this->table}.place_of_origin
      		ORDER BY place_of_origin ASC
      	")->result();
	}
}
