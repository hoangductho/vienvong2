<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/10/15
 * Time: 2:56 AM
 */

require_once('Admin.php');

class Admin_Roles extends  Admin
{

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
     * Permission Validate
     *
     * @param int $int data need check valid.
     * @todo using regular expression to check data valid.
     * @return bool result check valid
     */
    private function _permissionValid($int) {
        $valid = filter_var($int, FILTER_VALIDATE_INT);

        if ($valid === 0 || !$valid === false) {
            return true;
        }

        return false;
    }
    // ---------------------------------------------------------------------

    /**
     * ListRole list group in system
     *
     * @param string $text text search
     * @param int $page page of list
     * @return array result query
     */
    public function listRole($page = 1, $text = '') {
        $table = 'Roles';
        $select = '_id, email, group, permission';
        $limit = 10;

        $textValid = $this->_groupNameValid($text);
        $emailValid = $this->_emailValid($text);
        if (!$textValid && !$emailValid) {
            $text = '';
        }

        $roles = $this->Admin_model->select_admin($table, $text, $select, $page, $limit);

        echo json_encode($roles, true);
    }
    // ---------------------------------------------------------------------

    /**
     * Add New Role Into System
     */
    public function addRole() {
        $table = 'Roles';
        $family = null;

        $groupValid = $this->_groupNameValid($this->data['group']);
        $emailValid = $this->_emailValid($this->data['email']);
        $perValid = $this->_permissionValid($this->data['permission']);
        if ($groupValid && $emailValid && $perValid) {
            $data['uid'] = hash('sha256',$this->data['email']);
            $data['email'] = $this->data['email'];
            $data['gid'] = md5(strtolower(str_replace(' ','_',$this->data['group'])));
            $data['group'] = $this->data['group'];
            $data['permission'] = $this->data['permission'];
            $data['_id'] = md5($data['uid'].$data['gid']) ;
            $data['time'] = date('Y:d:m H:m:s');
            $data['creator'] = $this->uid;

            $where['_id'] = $data['_id'];

            $exist = $this->_dataExist($table, $where);

            if ($exist) {
                echo json_encode(array('ok' => 0, 'err' => 'Role is existed'), true);
                return false;
            }

            $add = $this->Admin_model->insert($table, $data);
            echo json_encode($add, true);
            return true;
        } else {
            echo json_encode(array('ok' => 0, 'err' => 'Data invalid'), true);
            return false;
        }

    }
    // ---------------------------------------------------------------------

    // End of class
}