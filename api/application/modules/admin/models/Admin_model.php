<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/9/15
 * Time: 3:29 AM
 */

require_once(APPPATH.'models/Models.php');

class Admin_model extends  Models{

    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - Init database connect
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    // ------------------------------------------------------------

    /**
     * Select function
     *
     * @param string $table table name.
     * @param string $text search string for full text search.
     * @param string $select fields will be select.
     * @param int $page number page to compute offset.
     * @param int $limit number rows will be select.
     * @return array query data
     */
    public function select_admin($table, $text, $select = '*', $page = 1, $limit = 10) {
        $this->db->select($select);
        if(strlen(trim($text))) {
            $this->db->fulltext_where($text);
        }
        $this->db->offset(($page - 1) * $limit);
        $this->db->limit($limit);
        $this->db->from($table);
        $data = $this->db->get();

        return $data;
    }
    // ------------------------------------------------------------

    /**
     * Function : Find_articles
     * Type     : Public
     * Task     : Find articles follow conditions
     * Params   :
     *
     * @select - string
     * @where  - array
     * @limit  - number (default 0)
     * @page   - number (default 1)
     */
    public function find_articles($table, $select = '*', $where = array(), $limit = 0, $page = 0) {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->limit($limit);
        if($page > 1) {
            $this->db->offset(($page-1) * $limit);
        }
        $this->db->from($table);
        $this->db->order_by('firstTime', 'DESC');
        //var_dump($this->db->get_compiled_select('', false));
        $articles = $this->db->get();

        return $articles;
    }
    // ------------------------------------------------------------

    /**
     * Count data of field
     *
     * @param string $table name of table will be query.
     * @param string $field name of field will be count.
     * @param bool   $distinct count only one or recursive.
     * @param string $time_name name of field storage time
     * @param timestamp $start_date date to start count.
     * @param timestamp $end_date data to end count.
     *
     * @return count result
     */
    public function count($table, $field, $distinct = false, $time_name = 'time', $start_date = null, $end_date = null) {
        if($start_date == null) {
            $start_date = strtotime(date('Y-m-d 00:00:00'));
        }

        if($end_date == null) {
            $end_date = strtotime(date('Y-m-d 23:59:59'));
        }

        $where = array(
            $time_name = array(
                '$gte' => $start_date,
                '$lte' => $end_date
            )
        );

        if($distinct) {
            $this->db->from($table);
            $this->db->where($where);
            $this->db->select_count($field);
            $this->db->group_by($field);

            $count = $this->db->get();
            $count['result'] = array(
                $field => count($count['result'])
            );

            return $count;
        }else {
            $this->db->from($table);
            $this->db->where($where);
            $this->db->select_count($field);
            return $this->db->get();
        }
    }
    // ------------------------------------------------------------

    // End of class
}