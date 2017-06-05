<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Suggestion extends REST_Controller {
  function __construct()
  {
    parent:: __construct();

    $this->load->model('MSuggestions');
  }

  function index_get() {
    $result = $this->MSuggestions->get_suggestions();

    if ($result)
    {
      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    else
    {
      $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
  }
}