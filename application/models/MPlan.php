<?php
class MPlan extends CI_Model {
  function __construct() {
    parent::__construct();
  }
  /**
   * PRIMARY FUNCTIONS
   */
  function plan_detail_by_date_user($date, $user) {
    $sql = "SELECT i.plan_date
                 , i.user_id
                 , user_name
                 , concat('http://scrum.mismaven.kr/assets/img/member/', user_img) AS user_img
                 , plan_comment
            FROM scrum_plan_info i
               , scrum_user u
            WHERE i.user_id = '$user'
            AND plan_date = '$date'
            AND i.user_id = u.user_id
            GROUP BY i.plan_date, i.user_id";
    $query = $this->db->query($sql);
    return $query->row();
  }

  function plan_cotents_by_date_user($date, $user) {
    $sql = "SELECT plan_detail_seq
                 , plan_content
                 , plan_status
            FROM scrum_plan
            WHERE plan_date = '$date'
            AND user_id = '$user'";
    $query = $this->db->query($sql);
    return $query->result_array();
  }

  function view_comment($date, $user) {
    $this->db->select('plan_comment');
    // $this->db->from('scrum_plan_info');
    $this->db->where('user_id', $user);
    $this->db->where('plan_date', $date);

    $query = $this->db->get('scrum_plan_info');
    $row = $query->row();

    return isset($row) ? $row->plan_comment : "";
  }

  function reply_by_date_user($date, $user) {
    $this->db->query("SET @rownum := 0;");
    $sql = "SELECT level - 1 AS reply_level
                 , r.*
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

  function count_plan_details($date, $user) {
      $this->db->from('scrum_plan');
      $this->db->where('plan_date', $date);
      $this->db->where('user_id', $user);

      return $this->db->count_all_results();
  }

  function add_plan($data) {
    $query = $this->db->insert('scrum_plan', $data);
    return 1;
  }

  function add_plan_info($data) {
    $query = $this->db->insert('scrum_plan_info', $data);
    return;
  }

  function additional_plan($data) {
    $query = $this->db->insert('scrum_plan', $data);
    return 1;
  }

  function modify_plan($data) {
    $this->db->where('plan_date', $data['plan_date']);
    $this->db->where('user_id', $data['user_id']);
    $this->db->where('plan_detail_seq', $data['plan_detail_seq']);
    $query = $this->db->update('scrum_plan', $data);
    return 1;
  }

  function modify_plan_info($data) {
    $this->db->where('plan_date', $data['plan_date']);
    $this->db->where('user_id', $data['user_id']);
    $query = $this->db->update('scrum_plan_info', $data);
    return;
  }

  function remove_plan($data) {
    $query = $this->db->delete('scrum_plan', $data);
    return 1;
  }

  function remove_plan_info($date, $user) {
    $this->db->where('plan_date', $date);
    $this->db->where('user_id', $user);
    $this->db->delete('scrum_plan_info');
  }

  function update_seq($data) {
    $this->db->set('plan_detail_seq', 'plan_detail_seq-1', FALSE);
    $this->db->where('plan_date', $data['plan_date']);
    $this->db->where('user_id', $data['user_id']);
    $this->db->where('plan_detail_seq >', $data['plan_detail_seq']);
    $this->db->update('scrum_plan');
    return;
  }

  function get_creation_dttm($date, $user) {
    $sql = "SELECT time(plan_creation_dttm) AS creation_dttm
            FROM scrum_plan_info
            WHERE user_id = '$user'
            AND plan_date = '$date'";
    $query = $this->db->query($sql);
    $row = $query->row();
    if($row) {
      return $row->creation_dttm;
    }else {
      return "";
    }
  }

  /**
   * PLAN CHECK FUNCTIONS
   */
  function check_valid_plan($date, $user) {
    $sql = "SELECT *
            FROM scrum_plan
            WHERE user_id = '$user'
            AND plan_date = '$date'";
    $query = $this->db->query($sql);
    return $query->num_rows();
  }

  function get_max_seq($date, $user) {
    $this->db->where('plan_date', $date);
    $this->db->where('user_id', $user);
    $this->db->select_max('plan_detail_seq', 'max_seq');
    $query = $this->db->get('scrum_plan');
    $row = $query->row();
    return $row->max_seq;
  }
}
?>
