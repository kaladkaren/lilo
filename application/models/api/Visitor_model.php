<?php

class Visitor_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->visitors = 'guest_visitors';
        $this->guest_visitors = 'guest_visitors';
        $this->cesbie_visitors = 'cesbie_visitors';
        $this->agency = 'agency';
        $this->division = 'division';
        $this->staffs = 'staffs';
        $this->services = 'services';
        $this->feedbacks = 'feedbacks';
        $this->upload_dir = 'visitors';
    }

    public function login($post, $files)
    {
	    $return = $data = array(
	      'fullname' => $post['fullname'], 
	      'agency' => $post['agency'], 
	      'attached_agency' => $post['attached_agency'], 
	      'email_address' => $post['email_address'], 
	      'is_have_ecopy' => $post['is_have_ecopy'], 
	      'division_to_visit' => $post['division_to_visit'], 
	      'purpose' => $post['purpose'], 
	      'person_to_visit' => $post['person_to_visit'], 
	      'temperature' => $post['temperature'], 
	      'place_of_origin' => $post['place_of_origin'], 
	      'mobile_number' => $post['mobile_number'], 
	      'health_condition' => $post['health_condition'], 
	      'pin_code' => $post['pin_code']
	    );
	    $this->db->insert($this->visitors, $data);  
	    $visitor_id = $this->db->insert_id();

    	if ($visitor_id):
		    if(isset($files['photo']) && $files['photo']['name'] != ''):
		      $photo_name = $this->custom_upload('photo', $this->upload_dir, $visitor_id)['photo'];
		      $update_visitor['photo'] = $photo_name;
			  $this->db->where('id', $visitor_id);
		      $update = $this->db->update($this->visitors, $update_visitor);
		      if($update == false):
		      	$this->c_deleteUploadedMedia($visitor_id, $this->visitors, $this->upload_dir, 'photo');
		      	$this->db->where('id', $visitor_id);
          		$this->db->delete($this->visitors);
          		$return = false;
		      endif;
		    endif;
	    endif;

    	return $return;
    }
    public function cesbie_login($post, $files)
    {
	    $data = array(
	      'staff_id' => $post['staff_id'], 
	      'temperature' => $post['temperature'], 
	      'place_of_origin' => $post['place_of_origin'], 
	      'pin_code' => $post['pin_code']
	    );
	    $this->db->insert($this->cesbie_visitors, $data);

	    $return = $this->db->insert_id();

    	return $return;
    }
    public function custom_upload($file_key, $dir, $visitor_id)
	{
	    @$file = $_FILES[$file_key];
	    $upload_path = 'uploads/'.$dir;

	    $config['upload_path'] = $upload_path; # NOTE: Change your directory as needed
	    $config['allowed_types'] = 'jpg|jpeg|png'; # NOTE: Change file types as needed
	    $config['file_name'] = $visitor_id .'-'.time() . '_' . $file['name']; # Set the new filename
	    $this->upload->initialize($config);

	    if (!is_dir($upload_path) && !mkdir($upload_path, DEFAULT_FOLDER_PERMISSIONS, true)){
	      mkdir($upload_path, DEFAULT_FOLDER_PERMISSIONS, true); # You can set DEFAULT_FOLDER_PERMISSIONS constant in application/config/constants.php
	    }
	    if($this->upload->do_upload($file_key)){
	      return [$file_key => $this->upload->data('file_name')];
	    }else{
	      return [];
	    }
	}

	public function search_pin_validity($pin_code, $valid = '')
	{	
		if($valid == ''):
			$feedbacks = $this->db->get_where($this->feedbacks, array('pin_code' => $pin_code))->row();
			if($feedbacks):
				return [];
			endif;
		endif;

		$visitor_type = '';
		$cesbie_visitor = $return = $this->db->get_where($this->cesbie_visitors, array('pin_code' => $pin_code))->row();
		if(!$cesbie_visitor):
			$visitor = $return = $this->db->get_where($this->visitors, array('pin_code' => $pin_code))->row();
			$visitor_type = 'guest_visitors';
		else:
			$visitor_type = 'cesbie_visitors';
		endif;

		return ($return == null) ? []:$visitor_type;
	}

	public function logout($post)
	{
		return $this->db->insert($this->feedbacks, $post);
	}

	public function print($pin_code)
	{
		$visitor_type = $this->search_pin_validity($pin_code, true);
		if($visitor_type == 'guest_visitors'):
			$sql = "
	      		SELECT 
	            	DATE_FORMAT({$this->$visitor_type}.created_at, '%c/%d/%Y | %l:%i %p') as login_time_format,
	            	{$this->$visitor_type}.fullname,
	            	{$this->agency}.name as agency,
	            	{$this->agency}.name as attached_agency,
	            	{$this->$visitor_type}.email_address,
	            	{$this->division}.name as division,
	            	{$this->staffs}.fullname as person_visited,
	            	{$this->services}.name as purpose,
	            	{$this->$visitor_type}.temperature,
	            	{$this->$visitor_type}.place_of_origin,
	            	{$this->$visitor_type}.created_at as login_time,
	            	{$this->feedbacks}.created_at as logout_time
	      		FROM {$this->$visitor_type}
	      		LEFT JOIN {$this->agency} ON {$this->agency}.id={$this->$visitor_type}.agency
	      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->$visitor_type}.division_to_visit
	      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->$visitor_type}.person_to_visit
	      		LEFT JOIN {$this->services} ON {$this->services}.id={$this->$visitor_type}.purpose
	      		LEFT JOIN {$this->feedbacks} ON {$this->feedbacks}.pin_code={$this->$visitor_type}.pin_code
	      		WHERE {$this->$visitor_type}.pin_code = '{$pin_code}'
	      	";
		elseif($visitor_type == 'cesbie_visitors'):
			$sql = "
	      		SELECT 
	            	DATE_FORMAT({$this->$visitor_type}.created_at, '%c/%d/%Y | %l:%i %p') as login_time_format,
	            	{$this->division}.name as division,
	            	{$this->staffs}.fullname,
	            	{$this->staffs}.email_address,
	            	{$this->$visitor_type}.temperature,
	            	{$this->$visitor_type}.place_of_origin,
	            	{$this->$visitor_type}.created_at as login_time,
	            	{$this->feedbacks}.created_at as logout_time
	      		FROM {$this->$visitor_type}
	      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->$visitor_type}.staff_id
	      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
	      		LEFT JOIN {$this->feedbacks} ON {$this->feedbacks}.pin_code={$this->$visitor_type}.pin_code
	      		WHERE {$this->$visitor_type}.pin_code = '{$pin_code}'
	      	";
	    else:
	    	return $visitor_type;
		endif;

		$res = $this->db->query($sql)->row();
		$res->duration = $this->calculate_duration($res->login_time, $res->logout_time);
		return $res;
	}

	public function calculate_duration($login, $logout)
	{
		$seconds = strtotime($logout) - strtotime($login);

		$days = floor($seconds / 86400);
		$hours = floor(($seconds - ($days * 86400)) / 3600);
		$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);

		$days_str = ($days == 1) ? 'day':'days';
		$hours_str = ($hours == 1) ? 'hour':'hours';
		if($days):
			$return_str = $days .' days, ';
		endif;
		if($hours):
			$return_str = $hours .' hours, ';
		endif;

		return rtrim($return_str, ', ');
	}
}