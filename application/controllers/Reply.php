<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Reply extends REST_Controller {
  function __construct()
  {
    parent:: __construct();

    $this->load->model('MReply');
  }

  function index_get()
  {
    $date = $this->get('date');
    $user = $this->get('user');

    $response = array(
      "result" => $this->MReply->reply_by_date_user($date, $user),
      "count" => $this->MReply->count_reply($date, $user)
    );

    if ($response['count'] > 0) {
      $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    } else {
      $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
  }
}