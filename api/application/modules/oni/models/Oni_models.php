<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/3/15
 * Time: 11:38 AM
 */

require_once(APPPATH . 'models/Models.php');

class Oni_models extends Models {
    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - Init database connect
     */
    public function __construct() {
        parent::__construct();
        flush();
        $this->load->database();
    }
    // ------------------------------------------------------------

    public function count_visit ($uid, $sd, $d) {
        $where['vs_created'] = array(
            '$lte' => $sd,
            '$gt' => $d
        );

        $where['vs_creator'] = $uid;

        $in = array(2118,2424,7109,2057,402,11470,10461,12709,15188,6054);

        $this->db->from('mongo_visits');
        $this->db->where($where);
//        $this->db->where_in('vs_creator',$in);
        $this->db->select_count(array('vs_creator'));
//        $this->db->limit(1500);
        var_dump($this->db->get_compiled_select('mongo_visits', false));
//        return 0;

        return $this->db->get();
    }

    public function count_income($uid, $sd, $d) {
        $where['created'] = array(
            '$lte' => $sd - 1,
            '$gt' => $sd - $d
        );
//        $where['created'] = $sd;
        $where['uid'] = $uid;
        $this->db->select('*');
        $this->db->from('mongo_incomes');
        $this->db->where($where);
//        $this->db->limit(0);
//        $this->db->select_count(array('uid'));
//        $this->db->select_sum('cash');
//        $this->db->group_by(array('uid'));
//        var_dump($this->db->get_compiled_select('mongo_incomes', false));
//        return 0;
        flush();
        return $this->db->get();
    }

    public function get_income($uid, $sd, $d) {
        $where['created'] = array(
            '$lte' => $sd,
            '$gt' => $d
        );

        $where['uid'] = $uid;
        $this->db->select('*');
        $this->db->from('mongo_incomes');
        $this->db->where($where);
        $this->db->order_by('created', 'Desc');
        return $this->db->get();
    }

    // End of class
}