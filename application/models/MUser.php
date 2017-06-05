<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MUser extends CI_Model{

  public function __construct()
  {
    parent::__construct();
  }

  function get_user($user)
  {
    $sql = "SELECT *
            FROM scrum_user
            WHERE user_id = '$user'";
    $query = $this->db->query($sql);

    return $query->row();
  }

  function modify_user($data)
  {
    $this->db->where('user_id', $this->session->userdata('user_id'));
    $query = $this->db->update('scrum_user', $data);
    return 1;
  }

  function get_all_users()
  {
    $query = $this->db->get('scrum_user');
    return $query->result_array();
  }
}
