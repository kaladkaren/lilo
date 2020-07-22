<?php

class Visitor_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->visitors = 'visitors';
        $this->cesbie_visitors = 'cesbie_visitors';
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
}