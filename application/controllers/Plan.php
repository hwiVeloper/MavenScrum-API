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
      // plan detail contents
      foreach ($result as $k => $row) {
        $result[$k]['details'] = $this->MDashboard->plan_by_date_user($date, $result[$k]['user_id']);
      }

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

    $response = array(
      "result" => $result,
      "count" => $count
    );

    if ($count > 0) {
      $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    } else {
      $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
  }

  function detail_get() {
    $date = $this->get('date');
    $user = $this->get('user');

    $result = $this->MPlan->plan_detail_by_date_user($date, $user);

    if ($result) {
      // plan detail contents
      $result->details = $this->MPlan->plan_cotents_by_date_user($date, $result->user_id);
      // reply in plan
      $result->replies = $this->MPlan->reply_by_date_user($date, $result->user_id);

      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    } else {
      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
  }
}