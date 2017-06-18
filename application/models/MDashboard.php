<?php
class MDashboard extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function today_count($date) {
    $sql = "SELECT COUNT(*) as count
            FROM scrum_plan_info
            WHERE plan_date = '$date'";
    $query = $this->db->query($sql);
    $row = $query->row();
    return $row->count;
  }

  function today_plans($date) {
    $sql = "SELECT p.plan_date
                 , p.user_id
                 , u.user_name
                 , concat('http://scrum.mismaven.kr/assets/img/member/', u.user_img) AS user_img
                 , i.plan_comment
                 , i.plan_creation_dttm
                 , (SELECT COUNT(*)
                                FROM scrum_reply
                                WHERE plan_date = i.plan_date
                                AND user_id = i.user_id) AS reply_count
                 , (SELECT plan_content FROM scrum_plan pp
                    WHERE plan_date = '$date' AND pp.user_id = i.user_id AND plan_detail_seq = 0) AS plan_content_1
                 , (SELECT plan_content FROM scrum_plan pp
                    WHERE plan_date = '$date' AND pp.user_id = i.user_id AND plan_detail_seq = 1) AS plan_content_2
                 , (SELECT plan_content FROM scrum_plan pp
                    WHERE plan_date = '$date' AND pp.user_id = i.user_id AND plan_detail_seq = 2) AS plan_content_3
            from scrum_user u, scrum_plan_info i
            left join scrum_plan p on i.plan_date = p.plan_date AND i.user_id = p.user_id
            where i.plan_date = '$date'
            and u.user_id = i.user_id
            and p.plan_detail_seq <= 2
            group by p.plan_date, p.user_id
            order by plan_creation_dttm
            ";

    $query = $this->db->query($sql);
    return $query->result_array();
  }

  function plan_by_date_user($date, $user) {
    $sql = "SELECT p.plan_detail_seq
                 , p.plan_content
                 , p.plan_status
            FROM scrum_plan p
            WHERE p.plan_date = '$date'
            AND p.user_id = '$user'";
    $query = $this->db->query($sql);
    return $query->result_array();
  }
}
?>
