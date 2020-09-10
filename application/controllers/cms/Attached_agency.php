<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attached_agency extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/agency_model', 'agency_model');
    $this->load->helper('url');
    $this->load->library("pagination");
  }

  public function index()
  {
    $this->attached_agency();
  }

  public function attached_agency()
  {
    $data['agency'] = $this->agency_model->all_attached_agency();
    $data['mother_agency'] = $this->agency_model->all_agency();
    # Pagination
    $pag_conf['base_url'] = base_url("/cms/attached-agency/index");
    $pag_conf['total_rows'] = $data['total_results'] = $this->agency_model->all_attached_agency_total();
    $pag_conf['per_page'] = $this->agency_model->per_rows;

    // next (>) link
    $pag_conf['next_tag_open'] = '<li>';
    $pag_conf['next_tag_close'] = '</li>';
    // prev (<) link
    $pag_conf['prev_tag_open'] = '<li>';
    $pag_conf['prev_tag_close'] = '</li>';
        // next (>) link
    $pag_conf['last_tag_open'] = '<li>';
    $pag_conf['last_tag_close'] = '</li>';
    // prev (<) link
    $pag_conf['first_tag_open'] = '<li>';
    $pag_conf['first_tag_close'] = '</li>';
    // current active pagination
    $pag_conf['cur_tag_open'] = '<li class="active"><a href="#">';
    $pag_conf['cur_tag_close'] = '</a></li>';

    $pag_conf['num_tag_open'] = '<li>';
    $pag_conf['num_tag_close'] = '</li>';
    $pag_conf['reuse_query_string'] = true;
    $this->pagination->initialize($pag_conf);
    $data["pagination"] = $this->pagination->create_links();
    ### / Pagination

    $data['page_of'] = $this->agency_model->displayPageData($pag_conf['total_rows']);
    $data['count_of'] = $this->agency_model->displayCountingData($pag_conf['total_rows']);

    $url = '';
    $data['x_clear_stat'] = '';
    $data['x_clear_keyword'] = $this->agency_model->strip_param_from_url($url, 'name', base_url('cms/attached-agency'));
    $data['x_clear_cat_agency'] = $this->agency_model->strip_param_from_url($url, 'cat_agency', base_url('cms/attached-agency'));
    ### SORTING BUTTONS
    $data['order'] = $this->agency_model->strip_param_from_url($url, 'order', base_url('cms/attached-agency'));
    $data['order_by'] = $this->agency_model->strip_param_from_url($url, 'order_by', base_url('cms/attached-agency'));
    ### / SORTING BUTTONS
    $this->wrapper('cms/attached-agency/all', $data);
  }

  public function add_new()
  {
    $data = [];
    $data['cities'] = $this->city_model->all();

    $this->wrapper('cms/attached-agency/add', $data);
  }

  public function add_agency()
  {
    $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');

    $post = $this->input->post();

    $add = $this->agency_model->add_attached_agency($post);

    if($add):
        $msg_data = array('alert_msg' => 'Agency added successfully.', 'alert_class' => 'alert-success');
    endif;

    $this->session->set_flashdata($msg_data);
      
    redirect($_SERVER['HTTP_REFERER']);
  }

  public function update_agency($id)
  {
    $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');

    $post = $this->input->post();

    $update = $this->agency_model->update_attached_agency($post, $id);

    if($update):
        $msg_data = array('alert_msg' => 'Agency updated successfully.', 'alert_class' => 'alert-success');
    endif;

    $this->session->set_flashdata($msg_data);
      
    redirect($_SERVER['HTTP_REFERER']);
  }
}