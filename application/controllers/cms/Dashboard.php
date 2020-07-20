<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/admin_model', 'admin_model');
  }

  public function index()
  {
    if ($_SESSION['is_super_admin'] == 1) {
      $this->dashboard();
    }else{
      redirect('cms/customers');
    }
  }

  public function dashboard()
  {
    $res = $this->admin_model->all();

    $data['res'] = $res;


    $post = $this->input->post();
    if ($post) {

      $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');

      $post['password'] = password_hash($post['password-add'], PASSWORD_DEFAULT);
      unset($post['password-add']);
      unset($post['c_password-add']);
      $_user = $this->admin_model->add($post);

      if ($_user) {
        $msg_data = array('alert_msg' => 'Administrator added successfully.', 'alert_class' => 'alert-success');
      }

      $this->session->set_flashdata($msg_data);
      
      redirect($_SERVER['HTTP_REFERER']);
    }

    $this->wrapper('cms/index', $data);
  }

  public function update()
  {
    $msg_data = array('alert_msg' => 'Something went wrong. Please try again.', 'alert_class' => 'alert-danger');
    $post = $this->input->post();
    if ($post) {

      $update_arr = array(
        'name' => $post['name-edit'], 
        'email' => $post['email-edit']
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
}
