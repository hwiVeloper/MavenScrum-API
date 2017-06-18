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
    $post = $this->post();

    $id = $post['id'];
    $pw = md5($post['pw']);
    log_message('debug', "ARRAY!!! ]]===]]===]]===> ". print_r($post, TRUE), false);
    // foreach($post as $k => $val){
    //   print($k);
    //   log_message('debug', 'BBBBBBBBBB'.$k['id'], false);
    // }
    log_message('debug', "input values ===> ".$this->post('id')." / ".$this->post('pw'), false);

    $count = $this->MAuth->auth($id, $pw);

    if ($count > 0)
    {
      log_message('debug', "success", false);
      $this->set_response([
        'status' => TRUE,
        'message' => $id.'님 환영합니다.'
      ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    else
    {
      $this->set_response([
        'status' => FALSE,
        'message' => '아이디, 패스워드를 확인해 주세요.'
      ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    }
  }

  function logout_post() {

  }
}