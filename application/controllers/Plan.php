<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Plan extends REST_Controller {
  function __construct()
  {
    parent:: __construct();

    $this->load->model('MPlan');
    $this->load->model('MDashboard');
  }

  function today_get() {
    $date = date("Y-m-d");
    $result = $this->MDashboard->today_plans($date);
    $count = $this->MDashboard->today_count($date);

    if ($count > 0)
    {
      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    else
    {
      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
  }

  function other_get() {
    $date = $this->get('date');
    $result = $this->MDashboard->today_plans($date);
    $count = $this->MDashboard->today_count($date);

    if ($count > 0) {
      foreach ($result as $k => $row) {
        $result[$k]['details'] = $this->MDashboard->plan_by_date_user($date, $result[$k]['user_id']);
      }

      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    } else {
      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
  }

  function my_get() {
    return 1;
  }

  function detail_get() {
    return 1;
  }
}