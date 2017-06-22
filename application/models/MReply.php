<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MReply extends CI_Model{

  public function __construct() {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function add_reply($data) {
    $query = $this->db->insert('scrum_reply', $data);
    return $this->db->insert_id();
  }

  function delete_reply($id) {
    $this->db->where('reply_id', $id);
    $this->db->delete('scrum_reply');
  }

  function get_child_reply($id) {
    $sql = "SELECT r.reply_id
            FROM (SELECT @rownum := @rownum + 1 AS rownum
                       , get_lvl() AS id
                       , @level AS level
                  FROM (SELECT @start_with:=$id
                             , @id:=@start_with
                             , @level:=0) vars
                  JOIN scrum_reply
                  WHERE @id IS NOT NULL) func
                  JOIN scrum_reply r ON func.id = r.reply_id
            WHERE plan_date in (SELECT plan_date FROM scrum_plan WHERE r.plan_date = plan_date)
            AND user_id in (SELECT user_id FROM scrum_user WHERE r.user_id = user_id)
            ORDER BY rownum, r.reply_id";
    $query = $this->db->query($sql);

    return $query->result_array();
  }

  function check_reply_fake($user_id, $plan_date, $write_user) {
    $sql = "SELECT COUNT(*) AS count
            FROM scrum_reply
            WHERE reply_timestamp > DATE_ADD(NOW(), INTERVAL -30 SECOND)
            AND user_id = '$user_id'
            AND plan_date = '$plan_date'
            AND write_user = '$write_user'";
    $query = $this->db->query($sql);
    $row = $query->row();

    return $row->count;
  }

  function reply_by_date_user($date, $user) {
    $this->db->query("SET @rownum := 0;");
    $sql = "SELECT level - 1 AS reply_level
                 , r.reply_id
                 , r.user_id
                 , r.write_user
                 , r.plan_date
                 , REPLACE(r.reply_comment, '<br />', '') AS reply_comment
                 , r.reply_timestamp
                 , r.up_reply_id
                 , r.up_reply_user
                 , (SELECT user_name FROM scrum_user WHERE user_id = r.write_user) AS user_name
                 , concat('http://scrum.mismaven.kr/assets/img/member/', (SELECT user_img FROM scrum_user WHERE user_id = r.write_user) ) AS user_img
                 , func.level
            FROM (SELECT @rownum := @rownum + 1 AS rownum
                       , get_lvl() AS id
                       , @level AS level
                  FROM (SELECT @start_with:=0
                             , @id:=@start_with
                             , @level:=0) vars
                  JOIN scrum_reply
                  WHERE @id IS NOT NULL) func
                  JOIN scrum_reply r ON func.id = r.reply_id
            WHERE plan_date = '$date'
            AND user_id = '$user'
            ORDER BY rownum, r.reply_id";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  function count_reply($date, $user) {
    $sql = "SELECT COUNT(*) count
            FROM scrum_reply
            WHERE plan_date = '$date'
            AND user_id = '$user'";
    $query = $this->db->query($sql);
    $row = $query->row();
    return $row->count;
  }
}
