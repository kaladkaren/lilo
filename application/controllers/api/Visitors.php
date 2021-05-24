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
        if(@$this->input->request_headers()['X-Api-Key'] != getenv('API_TOKEN')):
            $this->response((object)array('message' => "Unauthorized"), 401);
        endif;
    }
    public function logout_step_one_post()
    {
        $post = $this->post();
        $res = array();
        $message = "Bad request";
        $status  = "400";


        $res = $this->model->search_pin_validity($post['pin_code']);

        if ($res && $post['pin_code']):
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
        $ecopy_sent = '';

        $res = $this->model->search_pin_validity($post['pin_code']);

        if ($res):
            $logout = $this->model->logout($post);
            if($logout):
                $res = $this->model->print($post['pin_code']);
                if ($res->is_have_ecopy) {
                    $ecopy_sent = $this->model->send_logout_details($res);
                }
                $message = "THANK YOU! For visiting CESB";
                $status = "200";
            endif;
        else:
            $message = "Invalid pin code";
            $status = "404";
        endif;

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'post' => $post,
                'ecopy' => $ecopy_sent,
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
            $message = "THANK YOU! For visiting CESB";
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
                $res->staff = $staff;
                $message = "THANK YOU! For visiting CESB";
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
        $res['agency'] = array_merge($res['agency'],[(object)['id' => "0", 'name' =>'Others']]);
        // var_dump($res['agency']); die();
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

    public function attached_agency_others_get()
    {
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res = $this->agency_model->get_all_other_attached_agencies();
        $res = array_merge($res,[(object)['name' =>'Others']]);

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

        $res['place_of_origin'] = $this->city_model->get_regions();

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }

    public function cesbie_logout_post()
    {
        $post = $this->input->post();
        $res = array();
        $message = "THANK YOU! For visiting CESB";
        $status  = "200";

        $res = $this->model->cesbie_logout($post['staff_id']);

        if($res == null):
            $res = array();
            $status  = "201";
            $message = "User hasn't logged in yet";
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

    public function attached_agency_get($agency_id)
    {
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res = $this->agency_model->get_options($agency_id);

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }

    public function divisions_get()
    {
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res = $this->division_model->get_all();

        $r_return = (object)[
            'data' => $res,
            'meta' => (object)[
                'message' => $message,
                'status' => $status
            ]
        ];
        $this->response($r_return, $status);
    }

    public function service_by_division_get($division_id)
    {
        $res = array();
        $message = "Data found";
        $status  = "200";

        $res = $this->service_model->getByDivision($division_id);

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
