<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cesbie_visitors extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/cesbie_model', 'cesbie_model');
    $this->load->model('cms/division_model', 'division_model');
    $this->load->helper('url');
    $this->load->library("pagination");
  }

  public function index()
  {
    $this->cesbie_visitors();
  }

  public function cesbie_visitors()
  {
    $data = [];
    $data['cesbie_visitors'] = $this->cesbie_model->all();
    $data['divisions'] = $this->division_model->get_all();

    # Pagination
    $pag_conf['base_url'] = base_url("/cms/visitors/cesbie-visitors/index");
    $pag_conf['total_rows'] = $this->cesbie_model->all_total();
    $pag_conf['per_page'] = $this->cesbie_model->per_rows;

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

    $data['page_of'] = $this->cesbie_model->displayPageData($pag_conf['total_rows']);
    $data['count_of'] = $this->cesbie_model->displayCountingData($pag_conf['total_rows']);

    $url = '';
    $data['x_clear_stat'] = '';
    $data['x_clear_keyword'] = $this->cesbie_model->strip_param_from_url($url, 'name', base_url('cms/visitors/cesbie-visitors'));
    $data['x_clear_cat'] = $this->cesbie_model->strip_param_from_url($url, 'cat', base_url('cms/visitors/cesbie-visitors'));
    ### SORTING BUTTONS
    $data['order'] = $this->cesbie_model->strip_param_from_url($url, 'order', base_url('cms/visitors/cesbie-visitors'));
    $data['order_by'] = $this->cesbie_model->strip_param_from_url($url, 'order_by', base_url('cms/visitors/cesbie-visitors'));
    ### / SORTING BUTTONS


    $this->wrapper('cms/cesbie-visitors', $data);
  }
}