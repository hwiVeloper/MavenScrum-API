<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MAuth extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function auth($id, $pw)
  {
    $this->db->where('user_id', $id);
    $this->db->where('user_password', $pw);
    $query = $this->db->get('scrum_user');

    return $query->num_rows();
  }
}