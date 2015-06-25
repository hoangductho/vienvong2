<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 5/20/15
 * Time: 5:34 AM
 */

class Models extends CI_Model {
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
     * Function : Select
     * Type     : Public
     * Task     :
     *      - Select data in database
     */
    public function select($table, $select = '*', $where = array(), $limit = 1, $offset = 0) {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->offset($offset);
        $this->db->limit($limit);
        $this->db->from($table);
        $this->db->order_by('firstTime', 'DESC');

        $data = $this->db->get();

        return $data;
    }
    // ------------------------------------------------------------

    /**
     * Function : Select
     * Type     : Public
     * Task     :
     *      - Select data in database
     */
    public function select_where_in($table, $select = '*', $where = array(), $limit = 1, $offset = 0) {
        $this->db->select($select);
        foreach($where as $key=> $in) {
            $this->db->where_in($key,$in);
        }
        $this->db->offset($offset);
        $this->db->limit($limit);
        $this->db->from($table);
        $data = $this->db->get();

        return $data;
    }
    // ------------------------------------------------------------
    /**
     * Full text search
     *
     * @param string $table table will be queried
     * @param string $text  text need search
     * @param string $select fields want to return
     * @param array $where  where conditions
     * @param int   $limit  number of records will be return
     * @param int   $offset pagination point
     * @return array Query result
     */
    public function fulltextSearch($table, $text, $select = '*', $offset = 0, $limit = 10, $where=array()) {
        $this->db->select($select);
        $this->db->fulltext_where($text);
        $this->db->order_by('firstTime', 'Desc');
        $this->db->where($where);
        $this->db->offset($offset);
        $this->db->limit($limit);
        $this->db->from($table);
        //var_dump($this->db->get_compiled_select($table, false));
        $data = $this->db->get();

        return $data;
    }
    // ------------------------------------------------------------

    /**
     * Function : Insert
     * Type     : Public
     * Task     :
     *      - Insert data into database
     */
    public function insert($table, $data) {
        $this->db->set($data);

        $insert = $this->db->insert($table);

        return $insert;
    }

    public function insert_batch($table, $data) {
//        $this->db->set($data);

        $insert = $this->db->insert_batch($table, $data);

        return $insert;
    }
    // ------------------------------------------------------------

    /**
     * Function : Update
     * Type     : Public
     * Task     :
     *      - Update data in database
     */
    public function update($table, $data, $where) {
        $update = $this->db->update($table, $data, $where, 1);

        return $update;
    }
    // ------------------------------------------------------------

    /**
     * Function : delete
     * Type     : Public
     * Task     :
     *      - Update data in database
     */
    public function delete($table, $where) {
        $delete = $this->db->delete($table, $where, 1);

        return $delete;
    }
    // ------------------------------------------------------------

    // End of class
}
