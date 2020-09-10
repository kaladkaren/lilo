<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
// require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Cities extends Crud_controller {

    #GET api/users/
    #POST api/users/
    #GET api/users/{id}

    public function __construct()
    {
        parent::__construct();
        $this->load->model("cms/city_model", 'city_model');
        if(@$this->input->request_headers()['X-Api-Key'] != getenv('API_TOKEN')):
            $this->response((object)array('message' => "Unauthorized"), 401);
        endif;
    }

    public function cities_post()
    {
        $region = $this->post()['region'];
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res['cities'] = $this->city_model->get_cities($region);
        $res['region'] = $region;

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }
}