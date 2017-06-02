<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller {
  function __construct()
  {
    parent:: __construct();

    $this->load->model('MAuth');
  }

  function index_post() {
    $id = $this->post('id');
    $pw = md5($this->post('pw'));

    $count = $this->MAuth->auth($id, $pw);

    if ($count > 0)
    {
      $this->set_response($count, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    else
    {
      $this->set_response([
        'status' => FALSE,
        'message' => '아이디, 패스워드를 확인해 주세요.'
      ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    }
  }
}