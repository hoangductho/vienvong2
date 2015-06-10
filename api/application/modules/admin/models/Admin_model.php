<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/9/15
 * Time: 3:29 AM
 */

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

    // End of class
}