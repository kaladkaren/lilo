<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitors extends Admin_core_controller {

  public function __construct()
  {
    parent::__construct();

    $this->load->model('cms/cesbie_model', 'cesbie_model');
    $this->load->model('cms/visitor_model', 'visitor_model');
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
    $data['cesbie_visitors'] = $this->visitor_model->all();

    foreach ($data['cesbie_visitors'] as &$value) {
        if ($value->visitor_type == 'GUEST') {
            $this->db->where('id', $value->id);
            $visitor = $this->db->get('guest_visitors')->row();
            // var_dump($visitor->purpose, $visitor->person_to_visit); die();
            $value->agency_name = $value->agency_name?: 'Others';
            $value->att_agency_name = $value->att_agency_name?: 'Others';
            $value->attached_agency_others = $visitor->attached_agency_others;
            $value->attached_agency = $this->visitor_model->get_attach_agency_name($visitor->attached_agency) ?: 'Others';
            $value->purpose_name = $this->visitor_model->get_purpose_concat($visitor->purpose);
            $value->person_fullname_visited = $this->visitor_model->get_person_to_visit_concat($visitor->person_to_visit);
            $value->place_of_origin = $this->visitor_model->get_place_of_origin($visitor->region, $visitor->province, $visitor->city);

            $value->is_recent_contact = $visitor->is_recent_contact;
            $value->recent_contact_details = $visitor->recent_contact_details;
            $value->is_travelled_locally = $visitor->is_travelled_locally;
            $value->travelled_locally_details = $visitor->travelled_locally_details;
            $value->home_address = $visitor->home_address;
        } else if ($value->visitor_type == 'CESBIE') {
          $this->db->where('id', $value->id);
          $visitor = $this->db->get('cesbie_visitors')->row();
          // var_dump($visitor->purpose, $visitor->person_to_visit); die();
          $value->location_prior = $visitor->location_prior?: '-';
          $value->location_prior_others = $visitor->location_prior_others?: '-';
          $value->has_travelled = $visitor->has_travelled?: '-';
          $value->has_travelled_others = $visitor->has_travelled_others?: '-';
          $value->has_contact = $visitor->has_contact?: '-';
          $value->has_contact_others = $visitor->has_contact_others?: '-';

          // var_dump($value); die();
        }
    }
    // var_dump($data); die();

    // var_dump($data['cesbie_visitors']); die();
    $data['place_of_origin'] = $this->visitor_model->get_cities();

    # Pagination
    $pag_conf['base_url'] = base_url("/cms/visitors/index");
    $pag_conf['total_rows'] = $this->visitor_model->all_total();
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

    $data['page_of'] = $this->visitor_model->displayPageData($pag_conf['total_rows']);
    $data['count_of'] = $this->visitor_model->displayCountingData($pag_conf['total_rows']);

    $url = '';
    $data['x_clear_stat'] = '';
    $data['x_clear_keyword'] = $this->cesbie_model->strip_param_from_url($url, 'name', base_url('cms/visitors'));
    $data['x_clear_cat'] = $this->cesbie_model->strip_param_from_url($url, 'v_type', base_url('cms/visitors'));
    $data['x_clear_origin'] = $this->cesbie_model->strip_param_from_url($url, 'origin', base_url('cms/visitors'));
    $data['x_clear_date_range'] = $this->cesbie_model->strip_param_from_url($url, 'from', base_url('cms/visitors'));
    ### SORTING BUTTONS
    $data['order'] = $this->cesbie_model->strip_param_from_url($url, 'order', base_url('cms/visitors'));
    $data['order_by'] = $this->cesbie_model->strip_param_from_url($url, 'order_by', base_url('cms/visitors'));
    ### / SORTING BUTTONS


    $this->wrapper('cms/all-visitors', $data);
  }
}
