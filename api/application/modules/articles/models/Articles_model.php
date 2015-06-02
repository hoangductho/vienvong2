<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 3/20/15
 * Time: 6:19 PM
 */

/**
 * Class: Articles_model
 * Description:
 *  - Query database to control and following articles
 */

require_once(APPPATH . 'models/Models.php');

class Articles_model extends Models {

    private $table = 'Articles';

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
     * Function : Express_article
     * Type     : Public
     * Task     : Get detail content of the articles
     * Params   :
     *
     * @table   String (table name)
     * @where   array
     * @select  fixed
     */
    public function detail_article($table, $where, $select) {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->from($table);

        //var_dump($this->db->get_compiled_select('', false));
        $detail = $this->db->get();

        return $detail;
    }

    // ------------------------------------------------------------

    /**
     * Function : Update_article
     * Type     : Public
     * Task     : Insert_article in to table
     * Params   :
     *
     * @table   String (table have inserted )
     * @data    array (data have inserted)
     * @where   fixed (array or string)
     * @limit   Int
     */
    public function update_articles($table, $data, $where = array(), $limit = 0) {

        $insert = $this->db->update($table, $data, $where, $limit);

        return $insert;
    }

    // ------------------------------------------------------------

    /**
     * Function : Insert_article
     * Type     : Public
     * Task     : Insert_article in to table
     * Params   :
     *
     * @table   String (table have inserted )
     * @data    array (data have inserted)
     */
    public function insert_articles($table, $data) {
        $this->db->set($data);

        $insert = $this->db->insert($table);

        return $insert;
    }

    // ------------------------------------------------------------

    /**
     * Suggest articles query
     *
     * @param string $table table will be queried
     * @param string $text  text need search
     * @param string $select fields want to return
     * @param array $where  where conditions
     * @param int   $limit  number of records will be return
     * @return array Query result
     */
    public function suggest_articles($table, $text, $select = '*', $where=array(), $limit = 5) {
        $this->db->select($select);
        $this->db->fulltext_where($text);
        $this->db->order_by('firstTime', 'Desc');
        $this->db->where($where);
        $this->db->limit($limit);
        $this->db->from($table);
        //var_dump($this->db->get_compiled_select($table, false));
        $data = $this->db->get();
        return $data;
    }
    /* End of class*/
}