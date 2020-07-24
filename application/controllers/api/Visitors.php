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
        $this->load->model("api/staff_model", 'staff_model');
        $this->load->model("api/agency_model", 'agency_model');
        $this->load->model("api/division_model", 'division_model');
        $this->load->model("api/service_model", 'service_model');
        $this->load->model("cms/city_model", 'city_model');
        $this->load->model("cms/cesbie_model", 'cesbie_model');
    }
    public function logout_step_one_post()
    {
        $post = $this->post();
        $res = array();
        $message = "Bad request";
        $status  = "400";


        $res = $this->model->search_pin_validity($post['pin_code']);

        if ($res):
            $message = "Valid pin code";
            $status = "200";
        else:
            $message = "Invalid pin code";
            $status = "404";
        endif;

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }

    public function logout_step_two_post()
    {
        $post = $this->post();
        $res = array();
        $message = "Bad request";
        $status  = "400";

        $res = $this->model->search_pin_validity($post['pin_code']);

        if ($res):
            $logout = $this->model->logout($post);
            if($logout):
                $res = $post;
                $message = "Logout successfully";
                $status = "200";
            endif;
        else:
            $message = "Invalid pin code";
            $status = "404";
        endif;

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }

    public function logout_print_post()
    {
        $post = $this->post();
        $res = array();
        $message = "Bad request";
        $status  = "400";


        $res = $this->model->print($post['pin_code']);

        if ($res):
            $message = "Data found";
            $status = "200";
        else:
            $message = "Invalid pin code";
            $status = "404";
        endif;

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }
    public function guest_login_post()
    {
        $post = $this->post();
        $res = array();
        $message = "Bad request";
        $status  = "400";


        $res = $this->model->login($post, $_FILES);

        if ($res):
            $message = "Guest Visitor login successfully";
            $status = "201";
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
        $this->response($r_return, $status);
    }
    public function cesbie_login_post()
    {
        $post = $this->post();
        $res = array();
        $message = "Bad request";
        $status  = "400";

        $staff = $this->staff_model->get($post['staff_id']);
        if($staff):
            $res = $this->model->cesbie_login($post, $_FILES);
            if ($res):
                $post['staff_id'] = $staff;
                $res = $post;
                $message = "Cesbie Visitor login successfully";
                $status = "201";
            else:
                $message = "Error";
                $status = "400";
            endif;
        else:
            $message = "Staff ID not found";
            $status = "404";
        endif;


        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }
    public function cesbie_login_get()
    {
        $select = array('id', 'fullname');
        
        $res['staff'] = $this->cesbie_model->get_all_staff($select);
        $res['place_of_origin'] = $this->city_model->get_all();

        $message = "Data found";
        $status  = "200";


        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }
    public function guest_login_step_one_get()
    {
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res['agency'] = $this->agency_model->get_all();
        $res['attached_agency'] = [];

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }
    public function guest_login_step_two_get()
    {
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res['division'] = $this->division_model->get_all();
        $res['purpose'] = $this->service_model->get_all();
        $res['person_to_visit'] = $this->staff_model->get_all();

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }
    public function guest_login_step_three_get()
    {
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res['place_of_origin'] = $this->city_model->get_all();

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
