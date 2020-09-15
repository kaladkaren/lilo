<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guest_visitors extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/guest_model', 'guest_model');
    $this->load->model('cms/division_model', 'division_model');
    $this->load->model('cms/cesbie_model', 'cesbie_model');
    $this->load->helper('url');
    $this->load->library("pagination");
  }

  public function index()
  {
    $this->guest_visitors();
  }

  public function guest_visitors()
  {
    $data = [];
    $data['cesbie_visitors'] = $this->guest_model->all();
    // var_dump($data['cesbie_visitors']); die();
    $data['divisions'] = $this->division_model->get_all();
    $data['place_of_origin'] = $this->guest_model->get_cities();

    # Pagination
    $pag_conf['base_url'] = base_url("/cms/visitors/guest-visitors/index");
    $pag_conf['total_rows'] = $this->guest_model->all_total();
    $pag_conf['per_page'] = $this->cesbie_model->per_rows;

    // next (>) link
    $pag_conf['next_tag_open'] = '<li>';
    $pag_conf['next_tag_close'] = '</li>';
    // prev (<) link
    $pag_conf['prev_tag_open'] = '<li>';
    $pag_conf['prev_tag_close'] = '</li>';

    // next (>) link
    $pag_conf['first_tag_open'] = '<li>';
    $pag_conf['first_tag_close'] = '</li>';
    // prev (<) link
    $pag_conf['last_tag_open'] = '<li>';
    $pag_conf['last_tag_close'] = '</li>';

    // current active pagination
    $pag_conf['cur_tag_open'] = '<li class="active"><a href="#">';
    $pag_conf['cur_tag_close'] = '</a></li>';

    $pag_conf['num_tag_open'] = '<li>';
    $pag_conf['num_tag_close'] = '</li>';
    $pag_conf['reuse_query_string'] = true;
    $this->pagination->initialize($pag_conf);
    $data["pagination"] = $this->pagination->create_links();
    ### / Pagination

    $data['page_of'] = $this->cesbie_model->displayPageData__($pag_conf['total_rows']);
    $data['count_of'] = $this->cesbie_model->displayCountingData__($pag_conf['total_rows']);

    $url = '';
    $data['x_clear_stat'] = '';
    $data['x_clear_keyword'] = $this->cesbie_model->strip_param_from_url($url, 'name', base_url('cms/visitors/guest-visitors'));
    $data['x_clear_cat'] = $this->cesbie_model->strip_param_from_url($url, 'cat', base_url('cms/visitors/guest-visitors'));
    $data['x_clear_origin'] = $this->cesbie_model->strip_param_from_url($url, 'origin', base_url('cms/visitors/guest-visitors'));
    $data['x_clear_date_range'] = $this->cesbie_model->strip_param_from_url($url, 'from', base_url('cms/visitors/guest-visitors'));
    ### SORTING BUTTONS
    $data['order'] = $this->cesbie_model->strip_param_from_url($url, 'order', base_url('cms/visitors/guest-visitors'));
    $data['order_by'] = $this->cesbie_model->strip_param_from_url($url, 'order_by', base_url('cms/visitors/guest-visitors'));
    ### / SORTING BUTTONS


    $this->wrapper('cms/guest-visitors', $data);
  }
}