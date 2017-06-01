<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Users extends REST_Controller {
  function __construct()
  {
    parent:: __construct();

    $this->load->model('MUser');
  }

  /**
  * @api
  * @param String $id id of user
  * @return Array|null result of one user
  */
  function index_get()
  {
    $id = $this->get('id');

    // Get the user from the array, using the id as key for retrieval.
    // Usually a model is to be used for this.
    $user = $this->MUser->get_user( $id );

    if (!empty($user))
    {
      $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
    else
    {
      $this->set_response([
        'status' => FALSE,
        'message' => 'User could not be found'
      ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
    }
  }
}
?>