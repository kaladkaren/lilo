<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cesbie extends Admin_core_controller {

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
    $this->cesbie_staffs();
  }

  public function cesbie_staffs()
  {
    $data['cesbies'] = $this->cesbie_model->all_staff();
    $data['divisions'] = $this->division_model->get_all();
    # Pagination
    $pag_conf['base_url'] = base_url("/cms/cesbie-staffs/index");
    $pag_conf['total_rows'] = $data['total_results'] = $this->cesbie_model->all_staff_total();
    $pag_conf['per_page'] = $this->cesbie_model->per_rows;

    // next (>) link
    $pag_conf['next_tag_open'] = '<li>';
    $pag_conf['next_tag_close'] = '</li>';
    // prev (<) link
    $pag_conf['prev_tag_open'] = '<li>';
    $pag_conf['prev_tag_close'] = '</li>';
    // first (<) link
    $pag_conf['first_tag_open'] = '<li>';
    $pag_conf['first_tag_close'] = '</li>';
    // last (<) link
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

    $data['page_of'] = $this->cesbie_model->displayPageData($pag_conf['total_rows']);
    $data['count_of'] = $this->cesbie_model->displayCountingData($pag_conf['total_rows']);

    $url = '';
    $data['x_clear_stat'] = '';
    $data['x_clear_keyword'] = $this->cesbie_model->strip_param_from_url($url, 'name', base_url('cms/cesbie-staffs'));
    $data['x_clear_cat'] = $this->cesbie_model->strip_param_from_url($url, 'cat', base_url('cms/cesbie-staffs'));
    ### SORTING BUTTONS
    $data['order'] = $this->cesbie_model->strip_param_from_url($url, 'order', base_url('cms/cesbie-staffs'));
    $data['order_by'] = $this->cesbie_model->strip_param_from_url($url, 'order_by', base_url('cms/cesbie-staffs'));
    ### / SORTING BUTTONS
    $this->wrapper('cms/cesbie/all', $data);
  }

  public function add_staff()
  {
    $post = $this->input->post();

    $post['division_id'] = $post['division'];
    unset($post['division']);

    if(isset($post['is_active'])):
      $post['is_active'] = 1;
    else:
      $post['is_active'] = 0;
    endif;

    $add = $this->cesbie_model->add_staff($post);

    $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');

    if($add):
      $msg_data = array('alert_msg' => 'Cesbie Staff added successfully.', 'alert_class' => 'alert-success');
    endif;

    $this->session->set_flashdata($msg_data);
      
    redirect($_SERVER['HTTP_REFERER']);
  }
  public function update_staff($id)
  {
    $post = $this->input->post();

    $post['division_id'] = $post['division'];
    unset($post['division']);
    
    if(isset($post['is_active'])):
      $post['is_active'] = 1;
    else:
      $post['is_active'] = 0;
    endif;

    $update = $this->cesbie_model->update_staff($post, $id);

    $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');

    if($update):
      $msg_data = array('alert_msg' => 'Cesbie Staff updated successfully.', 'alert_class' => 'alert-success');
    endif;

    $this->session->set_flashdata($msg_data);
      
    redirect($_SERVER['HTTP_REFERER']);
  }
}