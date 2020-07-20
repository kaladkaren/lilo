<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
// require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Customers extends Crud_controller {

    #GET api/users/
    #POST api/users/
    #GET api/users/{id}

    public function __construct()
    {
        parent::__construct();
        $this->load->model("api/customer_model", 'model');
    }

    public function register_post()
    {
        $post = $this->post();

        $check_if_exists = $this->model->check_exists($post['email_address'], $post['mobile_number']);
        $message = 'Email/Mobile Number already exists';
        $status  = "400";
        
        if ($check_if_exists == 0):
            $add_account = $this->model->register($post);
            $message = 'Something went wrong. Please try again';
            if ($add_account):
                $status  = "201";
                $message = 'Registration Successful';
            endif;
        endif;
        

        $r_return = (object)[
            'data' => $this->post(),
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];

        $this->response($r_return, $status);
    }

    public function login_post()
    {
        $post = $this->post();

        $r_status = "400";
        $res = array();
        $message = "Invalid login credentials.";
        $status  = "400";


        $res = $this->model->login($post);

        if ($res):
            $message = "User login successfully";
            $status = "200";
        endif;

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, 500);
    }
}
