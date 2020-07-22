<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cesbie_visitors extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/cesbie_model', 'cesbie_model');
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

    $this->wrapper('cms/cesbie-visitors', $data);
  }
}