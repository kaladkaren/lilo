<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Divisions extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/division_model', 'division_model');
    $this->load->helper('url');
    $this->load->library("pagination");
  }

  public function index()
  {
    $this->divisions();
  }

  public function divisions()
  {
    $data['divisions'] = $this->division_model->all();
    # Pagination
    $pag_conf['base_url'] = base_url("/cms/divisions/index");
    $pag_conf['total_rows'] = $data['total_results'] = $this->division_model->all_total();
    $pag_conf['per_page'] = $this->division_model->per_rows;

    // next (>) link
    $pag_conf['next_tag_open'] = '<li>';
    $pag_conf['next_tag_close'] = '</li>';
    // prev (<) link
    $pag_conf['prev_tag_open'] = '<li>';
    $pag_conf['prev_tag_close'] = '</li>';
    // current active pagination
    $pag_conf['cur_tag_open'] = '<li class="active"><a href="#">';
    $pag_conf['cur_tag_close'] = '</a></li>';

    $pag_conf['num_tag_open'] = '<li>';
    $pag_conf['num_tag_close'] = '</li>';
    $pag_conf['reuse_query_string'] = true;
    $this->pagination->initialize($pag_conf);
    $data["pagination"] = $this->pagination->create_links();
    ### / Pagination

    $data['page_of'] = $this->division_model->displayPageData($pag_conf['total_rows']);
    $data['count_of'] = $this->division_model->displayCountingData($pag_conf['total_rows']);

    $url = '';
    $data['x_clear_stat'] = '';
    $data['x_clear_keyword'] = $this->division_model->strip_param_from_url($url, 'name', base_url('cms/divisions'));
    ### SORTING BUTTONS
    $data['order'] = $this->division_model->strip_param_from_url($url, 'order', base_url('cms/divisions'));
    $data['order_by'] = $this->division_model->strip_param_from_url($url, 'order_by', base_url('cms/divisions'));
    ### / SORTING BUTTONS
    $this->wrapper('cms/divisions/all', $data);
  }

  public function add_new()
  {
    $data = [];
    $data['cities'] = $this->city_model->all();

    $this->wrapper('cms/divisions/add', $data);
  }

  public function add_division()
  {
    $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');

    $post = $this->input->post();

    $add = $this->division_model->add($post);

    if($add):
        $msg_data = array('alert_msg' => 'Division added successfully.', 'alert_class' => 'alert-success');
    endif;

    $this->session->set_flashdata($msg_data);
      
    redirect($_SERVER['HTTP_REFERER']);
  }
}