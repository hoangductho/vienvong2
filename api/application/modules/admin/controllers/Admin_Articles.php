<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/29/15
 * Time: 4:10 AM
 */

require_once('Admin.php');

class Admin_Articles extends Admin{

    // table will be working
    private $table = 'Articles';

    // limited record will be get
    private $limit = 10;

    // ---------------------------------------------------------------------

    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - load model class to query database
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
    }
    // ---------------------------------------------------------------------

    /**
     * Set Hot Articles
     *
     * @param string $pid id of article will be set hot
     * @todo  update hot field of articles in database
     * @result update status
     */
    public function sethot($pid, $hot) {
        if($hot >= 1) {
            $hot = 0;
        }else {
            $hot = 1;
        }

        $where = array(
            '_id' => $pid
        );
        $update = array(
            'hot' => $hot
        );

        $updated = $this->Admin_model->update($this->table, $update, $where);

        echo json_encode($updated, true);
    }
    // ---------------------------------------------------------------------

    /**
     * Get All Articles
     */
    public function all($page = 0) {

        $select = '_id, users_id, title, friendly, description, lAvatar, sAvatar, hot, firstTime';

        $articles = $this->Admin_model->find_articles($this->table, $select, array(), $this->limit, $page);
        $articles['page'] = $page;

        echo json_encode($articles, true);
    }
    // ---------------------------------------------------------------------

    // End of class
}