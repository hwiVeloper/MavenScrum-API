<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Forum extends REST_Controller {
  function __construct()
  {
    parent:: __construct();

    $this->load->model('MForum');
  }

  function index_get() {
    $ym = $this->get('date') ? $this->get('date') : date('Ym');

    $result['base_ym'] = $ym;
    $result['details'] = $this->MForum->get_forum_list_by_ym($ym);

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