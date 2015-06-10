<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/9/15
 * Time: 5:02 AM
 */

require_once('Admin.php');

class Admin_Groups extends  Admin{

    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - load model class to query database
     */
    public function __construct() {
        parent::__construct();
    }
    // ---------------------------------------------------------------------

    /**
     * Data Validate
     *
     * @param string $name data need check valid.
     * @todo using regular expression to check data valid.
     * @return bool result check valid
     */
    private function _groupNameValid($name) {
        $regexp = array("options"=>array("regexp"=>"/^[\\s\\w]{2,64}$/u"));
        $valid = filter_var($name, FILTER_VALIDATE_REGEXP, $regexp);

        if(!$valid) {
            return false;
        }

        return true;
    }
    // ---------------------------------------------------------------------

    /**
     * ListGroup list group in system
     *
     * @param string $text text search
     * @param int $page page of list
     * @return array result query
     */
    public function listGroup($page = 1, $text = '') {
        $table = 'Groups';
        $select = '*';
        $limit = 10;

        $textValid = $this->_groupNameValid($text);
        if(!$textValid) {
            $text = '';
        }

        $groups = $this->Admin_model->select_admin($table, $text, $select, $page, $limit);
        $groups['text'] = $text;
        echo json_encode($groups, true);
    }
    // ---------------------------------------------------------------------

    /**
     * Add New Groups Into System
     */
    public function addGroup() {
        $table = 'Groups';

        $textValid = $this->_groupNameValid($this->data['name']);
        if($textValid) {
            $data['_id'] = md5($this->data['name']);
            $where['_id'] = $data['_id'];

            $exist = $this->_dataExist($table, $where);

            if($exist) {
                echo json_encode(array('ok' => 0, 'err' => 'Group is existed'), true);
                return false;
            }

            $data['_id'] = md5($this->data['name']);
            $data['name'] = $this->data['name'];
            $data['time'] = date('Y:d:m H:m:s');
            $data['creator'] = $this->uid;


            $add = $this->Admin_model->insert($table, $data);
            echo json_encode($add, true);
            return true;
        }else{
            echo json_encode(array('ok' => 0, 'err' => 'Group name invalid'), true);
            return false;
        }

    }
    // ---------------------------------------------------------------------

    // End of class

}