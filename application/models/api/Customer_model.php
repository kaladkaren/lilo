<?php

class Customer_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'customers';
        
    }

    public function check_exists($email, $mobile)
    {
    	$query = $this->db->query("
      		SELECT {$this->table}.*
      		FROM {$this->table} 
      		WHERE {$this->table}.email_address = '{$email}' 
      			  OR  {$this->table}.mobile_number = '{$mobile}'
      	");
    	return $query->num_rows();
    }

    public function register($post)
    {
    	$post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
    	return $this->db->insert($this->table, $post);
    }

    public function login($post)
    {
        $this->db->where("email_address", $post['email_address']);
        $this->db->or_where('mobile_number', $post['email_address']);
        $res = $this->db->get($this->table)->row();
        if ($res) {
        	if(password_verify($post['password'], $res->password)){
                $this->db->where("email_address", $post['email_address']);
        		$this->db->or_where('mobile_number', $post['email_address']);
                $res = $this->db->get($this->table)->row();
                unset($res->password);
	        }else{
	        	$res = array();
	        }
        }else{
        	$res = array();
        }

        return $res;
    }
}
