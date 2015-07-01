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
     * ListGroup list group in system
     *
     * @param string $text text search
     * @param int $page page of list
     * @return array result query
     */
    public function all($page = 1, $text = '') {
        $table = 'Groups';
        $select = '_id, name, family';
        $limit = 10;

        $textValid = $this->_groupNameValid($text);
        if(!$textValid) {
            $text = '';
        }

        $groups = $this->Admin_model->select_admin($table, $text, $select, $page, $limit);

        echo json_encode($groups, true);
    }
    // ---------------------------------------------------------------------

    /**
     * Add New Groups Into System
     */
    public function add() {
        $table = 'Groups';
        $family = null;

        $textValid = $this->_groupNameValid($this->data['name']);
        if($textValid) {
            $data['_id'] = md5(strtolower(str_replace(' ','_',$this->data['name'])));
            $where['_id'] = $data['_id'];

            $exist = $this->_dataExist($table, $where);

            if($exist) {
                echo json_encode(array('ok' => 0, 'err' => 'Group is existed'), true);
                return false;
            }

            if($this->_groupNameValid($this->data['father'])){
                $fatherID = md5( md5(strtolower(str_replace(' ','_',$this->data['father']))));
                $father = $this->_getGroupInfo($fatherID, 'family');
                if($father['ok'] && count($father['result'])) {
                    $fatherName = str_replace(' ','_',$this->data['father']);
                    if($father['result'][0]['family']){
                        $family = $father['result'][0]['family'].'.'.$fatherName;
                    }else {
                        $family = $fatherName;
                    }
                }
            }

            $data['name'] = strtolower($this->data['name']);
            $data['family'] = $family;
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