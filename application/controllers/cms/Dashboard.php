<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/admin_model', 'admin_model');
    $this->load->model('cms/cesbie_model', 'cesbie_model');
    $this->load->helper('url');
    $this->load->library("pagination");
  }

  public function index()
  {
    if ($_SESSION['is_super_admin'] == 1) {
      $this->dashboard();
    }else{
      redirect('cms/divisions');
    }
  }

  public function dashboard()
  {
    $res = $this->admin_model->all();

    $data['res'] = $res;

    # Pagination
    $pag_conf['base_url'] = base_url("/cms/index");
    $pag_conf['total_rows'] = $pag_conf['total_rows'] =  $this->admin_model->all_total();
    $pag_conf['per_page'] = $this->admin_model->per_rows;

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


    $data['page_of'] = $this->admin_model->displayPageData($pag_conf['total_rows']);
    $data['count_of'] = $this->admin_model->displayCountingData($pag_conf['total_rows']);


    $post = $this->input->post();
    if ($post) {

      $msg_data = array('alert_msg' => 'Duplicate entry of email address', 'alert_class' => 'alert-danger');

      $post['password'] = password_hash($post['password-add'], PASSWORD_DEFAULT);
      unset($post['password-add']);
      unset($post['c_password-add']);

      if($this->admin_model->check_if_email_exists($post['email']) == 0):
        $_user = $this->admin_model->add($post);
        if ($_user) {
          $msg_data = array('alert_msg' => 'Administrator added successfully.', 'alert_class' => 'alert-success');
        }
      endif;

      $this->session->set_flashdata($msg_data);
      
      redirect($_SERVER['HTTP_REFERER']);
    }

    $this->wrapper('cms/index', $data);
  }

  public function update()
  {
    $msg_data = array('alert_msg' => 'Duplicate entry of email address', 'alert_class' => 'alert-danger');
    $post = $this->input->post();

    $check = $this->admin_model->check_if_email_exists($post['email-edit'], $post['id-edit']);

    if ($check == 0 && $post) {

      $update_arr = array(
        'name' => $post['name-edit'], 
        'email' => $post['email-edit'],
        'super_admin' => $post['super_admin-edit']
      );

      if (isset($post['password-edit']) && $post['password-edit']) {
        $new_password = password_hash($post['password-edit'], PASSWORD_DEFAULT);
        $update_arr = array_merge(
          array('password' => $new_password), 
          $update_arr
        );
      }

      if ($this->admin_model->update($post['id-edit'], $update_arr)) {
        $msg_data = array('alert_msg' => 'Administrator updated successfully.', 'alert_class' => 'alert-success');
      }
    }

    $this->session->set_flashdata($msg_data);  
    redirect($_SERVER['HTTP_REFERER']);
  }

  public function delete()
  {
    $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');
    $post = $this->input->post();

    if ($post['id-delete'] == $_SESSION['id'] || count($this->admin_model->all()) == 1):
      $msg_data = array('alert_msg' => 'Unable to delete.', 'alert_class' => 'alert-danger');
    else:
      if ($post) {
        $delete = $this->admin_model->delete($post['id-delete']);
        if($delete){
          $msg_data = array('alert_msg' => 'Administrator deleted successfully.', 'alert_class' => 'alert-success');
        }
      }
    endif;


    $this->session->set_flashdata($msg_data);  
    redirect($_SERVER['HTTP_REFERER']);
  }
}
