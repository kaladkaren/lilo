<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
// require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Visitors extends Crud_controller {

    #GET api/users/
    #POST api/users/
    #GET api/users/{id}

    public function __construct()
    {
        parent::__construct();
        $this->load->model("api/visitor_model", 'model');
    }

    public function guest_login_post()
    {
        $post = $this->post();
        $res = array();
        $message = "Bad request";
        $status  = "400";


        $res = $this->model->login($post, $_FILES);

        if ($res):
            $message = "Visitor login successfully";
            $status = "200";
        else:
            $message = "Error";
            $status = "400";
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
