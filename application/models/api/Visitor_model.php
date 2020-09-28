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
        $this->attached_agency = 'attached_agency';
        $this->division = 'division';
        $this->staffs = 'staffs';
        $this->services = 'services';
        $this->feedbacks = 'feedbacks';
        $this->upload_dir = 'visitors';

        $config_mail['protocol'] = getenv('MAIL_PROTOCOL');
		$config_mail['smtp_host'] = getenv('SMTP_HOST');
		$config_mail['smtp_port'] = getenv('SMTP_PORT');
		$config_mail['smtp_user'] = getenv('SMTP_EMAIL');
		$config_mail['smtp_pass'] = getenv('SMTP_PASS');
		$config_mail['charset']='utf-8';
		$config_mail['newline']="\r\n";
		$config_mail['wordwrap'] = TRUE;
		$config_mail['mailtype'] = 'html';

		$this->load->library('email');
		$this->email->initialize($config_mail);
    }

    public function cesbie_logout($staff_id)
    {
    	$res = null;
    	$last_row=$this->db->select('id, logout_at')->where(array('staff_id' => $staff_id))->order_by('id',"desc")->limit(1)->get($this->cesbie_visitors)->row();
    	if ($last_row && $last_row->logout_at == '0000-00-00 00:00:00') {
	    	date_default_timezone_set('Asia/Manila');
	    	$update_visitor['logout_at'] = date('Y-m-d H:i:s');
	    	$this->db->where('id', $last_row->id);
			$update = $this->db->update($this->cesbie_visitors, $update_visitor);

	    	$sql = "SELECT 
		    			{$this->staffs}.fullname as fullname,
		    			{$this->division}.name as division,
		    			CONCAT({$this->cesbie_visitors}.temperature, '°C') as temperature,
		    			{$this->cesbie_visitors}.place_of_origin, 
		    			DATE_FORMAT({$this->cesbie_visitors}.created_at, '%c/%d/%Y | %l:%i %p') as login_time_format,
		    			DATE_FORMAT({$this->cesbie_visitors}.logout_at, '%c/%d/%Y | %l:%i %p') as logout_time_format,
		    			{$this->cesbie_visitors}.created_at as login_time,
		    			{$this->cesbie_visitors}.logout_at as logout_time 
		    		FROM {$this->cesbie_visitors} 
		    		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->cesbie_visitors}.staff_id
		    		LEFT JOIN {$this->division} ON {$this->division}.id={$this->staffs}.division_id
		    		WHERE {$this->cesbie_visitors}.staff_id = '{$staff_id}' ORDER BY {$this->cesbie_visitors}.id DESC";
			$res = $this->db->query($sql)->row();
			$res->duration = $this->calculate_duration($res->login_time, $res->logout_time);
    	}
		return $res;
    }
    public function is_decimal( $value )
	{
	    if ( strpos( $value, "." ) !== false ) {
	        return round((float)$value, 2);
	    }
	    return $value;
	}
    public function login($post, $files)
    {
	    $data = array(
	      'fullname' => $post['fullname'], 
	      'agency' => $post['agency'], 
	      'attached_agency' => $post['attached_agency'], 
	      'email_address' => @$post['email_address'], 
	      'is_have_ecopy' => $post['is_have_ecopy'], 
	      'division_to_visit' => @$post['division_to_visit']?: 0, 
	      'purpose' => @implode(",", @$post['purpose'])?:"", 
	      'person_to_visit' => @implode(",", @$post['person_to_visit'])?:"", 
	      'temperature' => $this->is_decimal($post['temperature']), 
	      'home_address' => @$post['home_address'] ?: "", 
	      'region' => $post['region'], 
	      'province' => @$post['province'] ?: "", 
	      'city' => $post['city'], 
	      'mobile_number' => $post['mobile_number'], 
	      'health_condition' => $post['health_condition'],
	      'is_recent_contact' => $post['is_recent_contact'],
	      'recent_contact_details' => @$post['recent_contact_details'] ?: "",
	      'is_travelled_locally' => $post['is_travelled_locally'],
	      'travelled_locally_details' => @$post['travelled_locally_details'] ?: ""
	    );
		$data['place_of_origin'] = $this->get_place_of_origin($data['region'], $data['province'], $data['city']);

	    $this->db->insert($this->visitors, $data);  
	    $visitor_id = $this->db->insert_id();

    	if ($visitor_id):
		    if(isset($files['photo']) && $files['photo']['name'] != ''):
		      $photo_name = $this->custom_upload('photo', $this->upload_dir, $visitor_id)['photo'];
		      $update_visitor['photo'] = $photo_name;
		      $update_visitor['pin_code'] = $this->get_pincode();
			  $this->db->where('id', $visitor_id);
		      $update = $this->db->update($this->visitors, $update_visitor);
		      if($update == false):
		      	$this->c_deleteUploadedMedia($visitor_id, $this->visitors, $this->upload_dir, 'photo');
		      	$this->db->where('id', $visitor_id);
          		$this->db->delete($this->visitors);
          		$return = false;
          	  else:
          	  	$sql = "SELECT 
	    			{$this->visitors}.*, 
	    			CONCAT({$this->visitors}.temperature, '°C') as temperature,
	    			{$this->agency}.name as agency,
	            	{$this->division}.name as division_to_visit,
	            	{$this->visitors}.person_to_visit as person_to_visit,
	            	{$this->visitors}.purpose as purpose,
	    			DATE_FORMAT({$this->visitors}.created_at, '%c/%d/%Y | %l:%i %p') as login_time_format 
	    		FROM {$this->visitors} 
	    		LEFT JOIN {$this->agency} ON {$this->agency}.id={$this->visitors}.agency
	      		LEFT JOIN {$this->division} ON {$this->division}.id={$this->visitors}.division_to_visit
	      		LEFT JOIN {$this->staffs} ON {$this->staffs}.id={$this->visitors}.person_to_visit
	      		LEFT JOIN {$this->services} ON {$this->services}.id={$this->visitors}.purpose
	    		WHERE {$this->visitors}.id = '{$visitor_id}'";
				$return = $this->db->query($sql)->row();
				$return->attached_agency = $this->get_attach_agency_name($return->attached_agency);
				// var_dump($return->purpose, $return->person_to_visit); die();
				$return->purpose = $this->get_purpose_concat($return->purpose);
				$return->person_to_visit = $this->get_person_to_visit_concat($return->person_to_visit);
		    	if ($post['is_have_ecopy'] == 1 && $post['email_address']) {
		    		$send = $this->send_details($return);
		    	}
		      endif;
		    endif;
	    endif;

    	return $return;
    }

    public function get_purpose_concat($ids){
    	if (!$ids) {
			return "";
		}
    	$ids = explode(',', $ids);
    	$this->db->select('name');
    	$this->db->where_in('id', $ids);
    	$services = $this->db->get('services')->result();
    	$ret = '';
    	foreach ($services as $value) {
    		$ret .= $value->name . ", ";
    	}
    	return rtrim($ret, ", ");
    }
	
	public function get_person_to_visit_concat($ids)
	{
		if (!$ids) {
			return "";
		}
    	$ids = explode(',', $ids);
    	$this->db->select('fullname');
    	$this->db->where_in('id', $ids);
    	$services = $this->db->get('staffs')->result();
    	$ret = '';

    	// var_dump($services); die();
    	foreach ($services as $value) {
    		$ret .= $value->fullname . ", ";
    	}
    	return rtrim($ret, ", ");
	}

    public function get_attach_agency_name($agency_id)
    {
    	$sql = "SELECT {$this->attached_agency}.*
	    		FROM {$this->attached_agency} 
	    		WHERE {$this->attached_agency}.id = '{$agency_id}'";
		return $this->db->query($sql)->row()->name;
    }
    public function get_pincode()
    {
    	$pin_code = null;
    	do {
    		$pin_code = $this->generate_pin();
    		$check_if_pincode_exists = $this->check_if_pincode_exists($pin_code);
		} while ($check_if_pincode_exists);

		return strtoupper($pin_code);
    }

    public function generate_pin()
    {
	    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < 2; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    $characters = '0123456789';
	    $charactersLength = strlen($characters);
	    for ($i = 0; $i < 4; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    return $randomString;
    }

    public function _old_generate_pin()
    {
    	$pin_code = md5(uniqid("", true));
    	return substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZ", 2)), 0, 2).substr($pin_code, 0,4);
    }

    public function check_if_pincode_exists($pin_code)
    {
    	$sql = "SELECT {$this->visitors}.*
	    		FROM {$this->visitors} 
	    		WHERE {$this->visitors}.pin_code = '{$pin_code}'";
		return $this->db->query($sql)->row();
    }

    public function send_details($data)
	{
		// var_dump($data->purpose, $data->division_to_visit); die();
		#########SENDING EMAIL############
    	$ret = true;
		$this->email->from('noreply@myoptimind.com', 'LiLo Xpress');
		$this->email->to($data->email_address);
		$this->email->bcc('lsalamante@myoptimind.com');
		$this->email->subject('LiLo Xpress: E-copy');
		$msg = '
		<body style="font-family: Arial, Helvetica, sans-serif;">

		<table style="text-align: center; border-left: 1px solid black;border-right: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;width: 370px;">
			<tbody>
			    <tr>
				    <td style="font-weight: 600;letter-spacing: 3px;font-size: 28px; padding: 10px 10px 0px 10px;">
				    	<p style="margin: 0px 0px 10px 0px;">CESB LETTERHEAD</p>
				    </td>
				</tr>
				<tr style="background: #cdcbcb;">
					<td>
						<p style="margin: 0; padding: 7px; font-size: 12px; font-weight: 600;letter-spacing: 2px;">'.$data->login_time_format.'</p>
					</td>
				</tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;margin-top: 10px;" >Full Name</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->fullname.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Agency / Attached Agency</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->agency.'<br>'.$data->attached_agency.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Email Address</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->email_address.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Division / Person Visited</p></td></tr>';

				$msg .= '<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->division_to_visit.' '. $data->person_to_visit.'</p></td></tr>';
				
				if ($data->purpose) {
					$msg .= '<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Purpose of Visit</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'. $data->purpose.'</p></td></tr>';
				}
				
				$msg .= '<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Temperature</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->temperature.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Place of Origin</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 30px 0px 30px;margin:0px 0px 20px 0px;font-weight: 700;">'.$data->region.', ' . $data->province . '<br>'.$data->city.'</p></td></tr>
			</tbody>
			<tfoot style="background: #E8FAFF;">
				<tr>
					<td style="padding: 0;"><p style="padding: 6px;margin: 0;"><label style="letter-spacing: 2px;margin:0;font-size: 12px;color: gray;">PIN CODE</label><br><label style="font-size: 20px;letter-spacing: 8px; font-family: Consolas; font-weight: 900;">'.$data->pin_code.'</label></p></td>
				</tr>	
			</tfoot>
		</table>
		</body>
		';

		$this->email->message($msg);

		if (!$this->email->send()){
			$ret = false;
		}

		return $ret;
		#########SENDING EMAIL############
	}
    public function send_logout_details($data)
	{
		#########SENDING EMAIL############
    	$ret = true;
		$this->email->from('noreply@myoptimind.com', 'LiLo Xpress');
		$this->email->to($data->email_address);
		$this->email->bcc('lsalamante@myoptimind.com');
		$this->email->subject('LiLo Xpress: Logout E-copy ');
		$msg = '
		<body style="font-family: Arial, Helvetica, sans-serif;">

		<table style="text-align: center; border-left: 1px solid black;border-right: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;width: 370px;">
			<tbody>
			    <tr>
				    <td style="font-weight: 600;letter-spacing: 3px;font-size: 28px; padding: 10px 10px 0px 10px;">
				    	<p style="margin: 0px 0px 10px 0px;">CESB LETTERHEAD</p>
				    </td>
				</tr>
				<tr style="background: #cdcbcb;">
					<td>
						<p style="margin: 0; padding: 7px; font-size: 12px; font-weight: 600;letter-spacing: 2px;">'.$data->login_time_format.'</p>
					</td>
				</tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;margin-top: 10px;" >Full Name</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->fullname.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Agency / Attached Agency</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->agency.'<br>'.$data->attached_agency.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Email Address</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->email_address.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Division / Person Visited</p></td></tr>';

				$msg .= '<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->division_to_visit.' '. $data->person_to_visit.'</p></td></tr>';
				
				if ($data->purpose) {
					$msg .= '<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Purpose of Visit</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'. $data->purpose.'</p></td></tr>';
				}
				
				$msg .= '<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Temperature</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 50px 0px 50px;margin:0px 0px 10px 0px;font-weight: 700;">'.$data->temperature.'</p></td></tr>
				<tr class="field"><td><p style="margin:0;font-size: 12px;letter-spacing: 2px; color: gray;">Place of Origin</p></td></tr>
				<tr class="field-value"><td><p style="padding: 0px 30px 0px 30px;margin:0px 0px 20px 0px;font-weight: 700;">'.$data->region.', ' . $data->province . '<br>'.$data->city.'</p></td></tr>
			</tbody>
			<tfoot style="background: #E8FAFF;">
				<tr>
					<td style="padding: 0;"><p style="padding: 6px;margin: 0;"><label style="letter-spacing: 2px;margin:0;font-size: 12px;color: gray;">PIN CODE</label><br><label style="font-size: 20px;letter-spacing: 8px; font-family: Consolas; font-weight: 900;">'.$data->pin_code.'</label></p></td>
				</tr>	
			</tfoot>
		</table>
		</body>
		';

		$this->email->message($msg);

		if (!$this->email->send()){
			$ret = false;
		}

		return $ret;
		#########SENDING EMAIL############
	}
    public function cesbie_login($post, $files)
    {
    	$return = false;
	    $data = array(
	      'staff_id' => $post['staff_id'], 
	      'temperature' => $this->is_decimal($post['temperature']), 
	      'health_condition' => $post['health_condition'], 
	      'region' => $post['region'], 
	      'city' => $post['city']
	    );
	    $this->db->insert($this->cesbie_visitors, $data);

	    $visitor_id = $this->db->insert_id();
	    if($visitor_id):
	    	$fullname = $this->db->get_where($this->staffs, array('id' => $post['staff_id']))->row()->fullname;
	    	$update_visitor['pin_code'] = strtoupper(substr(str_replace(' ', '', $fullname), rand(0, strlen(str_replace(' ', '', $fullname))-1), 2).date('is')).sprintf('%05d', $visitor_id).'C';
		  	$this->db->where('id', $visitor_id);
	      	$this->db->update($this->cesbie_visitors, $update_visitor);
	      	$sql = "SELECT 
	    			{$this->cesbie_visitors}.*, 
	    			CONCAT({$this->cesbie_visitors}.temperature, '°C') as temperature,
	    			DATE_FORMAT({$this->cesbie_visitors}.created_at, '%c/%d/%Y  |  %l:%i %p') as login_time_format 
	    		FROM {$this->cesbie_visitors} 
	    		WHERE {$this->cesbie_visitors}.id = '{$visitor_id}'";
			$return = $this->db->query($sql)->row();
	    endif;
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
	            	DATE_FORMAT({$this->feedbacks}.created_at, '%c/%d/%Y | %l:%i %p') as logout_time_format,
	            	{$this->$visitor_type}.fullname,
	            	{$this->agency}.name as agency,
	            	{$this->$visitor_type}.attached_agency,
	            	{$this->$visitor_type}.email_address,
	            	{$this->division}.name as division,
	            	{$this->$visitor_type}.is_have_ecopy,
	            	{$this->$visitor_type}.person_to_visit as person_visited,
	            	{$this->$visitor_type}.purpose as purpose,
	            	CONCAT({$this->$visitor_type}.temperature, '°C') as temperature,
	            	{$this->$visitor_type}.place_of_origin,
	            	{$this->$visitor_type}.region,
	            	{$this->$visitor_type}.province,
	            	{$this->$visitor_type}.city,
	            	{$this->$visitor_type}.created_at as login_time,
	            	{$this->feedbacks}.created_at as logout_time,
	            	{$this->feedbacks}.pin_code as pin_code
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
	            	DATE_FORMAT({$this->feedbacks}.created_at, '%c/%d/%Y | %l:%i %p') as logout_time_format,
	            	{$this->division}.name as division,
	            	{$this->staffs}.fullname,
	            	{$this->staffs}.email_address,
	            	CONCAT({$this->$visitor_type}.temperature, '°C') as temperature,
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
		if ($visitor_type == 'guest_visitors') {
			$res->attached_agency = $this->get_attach_agency_name($res->attached_agency);
			$res->purpose = $this->get_purpose_concat($res->purpose);
			$res->person_to_visit = $this->get_person_to_visit_concat($res->person_visited);
			$res->place_of_origin = $this->get_place_of_origin($res->region, $res->province, $res->city);
		}
		$res->duration = $this->calculate_duration($res->login_time, $res->logout_time);
		return $res;
	}

	function get_place_of_origin($region, $province, $city)
	{
		$array = [];
		if ($region != "") 
			$array[] = $region;
		if ($province != "") 
			$array[] = $province;
		if ($city != "") 
			$array[] = $city;

		$str = implode(', ', $array);
		return $str;
	}

	public function calculate_duration($login, $logout)
	{
		$return_str = '';
		$seconds = strtotime($logout) - strtotime($login);

		$days = floor($seconds / 86400);
		$hours = floor(($seconds - ($days * 86400)) / 3600);
		$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);

		$days_str = ($days == 1) ? 'day':'days';
		$hours_str = ($hours == 1) ? 'hour':'hours';
		$mins_str = ($minutes == 1) ? 'min':'mins';
		if($days):
			$return_str = $days ." {$days_str}, ";
		endif;
		if($hours):
			$return_str .= $hours ." {$hours_str}, ";
		endif;
		if($minutes == 0):
			$secs_str = ($seconds == 1) ? 'sec':'secs';
			$return_str .= $seconds ." {$secs_str}, ";
		else:
			$return_str .= $minutes ." {$mins_str}, ";
		endif;
		

		return rtrim($return_str, ', ');
	}
}