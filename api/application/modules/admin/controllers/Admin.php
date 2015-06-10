<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/9/15
 * Time: 3:28 AM
 */

require_once(APPPATH . '/controllers/Controller.php');
class Admin extends  Controller{

    /*
     * INPUT DATA
     *
     * Data was inputted from client
     */
    protected $data = null;

    /*
     * USER ID
     *
     * ID of user gotten from certificate check
     */
    protected $uid = null;

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

        $cert = $this->_adminCertificate();

        if(!$cert) {
            die();
        }
    }
    // ---------------------------------------------------------------------

    /**
     * _AdminCertificate Check admin role permission
     *
     * @return false,array false or admin id and data
     */
    protected function _adminCertificate() {
        $in = json_decode(file_get_contents('php://input'), true);


        if(isset($in['auth']) && $in['auth']) {
            $user = $this->_checkAccess($in['auth'], 'user_info');

            if(isset($user['role']) && $user['role'] == 'admin'){
                if(isset($in['data'])){
                    $this->data = $in['data'];
                }
                $this->uid = $user['uid'];

                return $this;
            }
        }

        echo json_encode(array('ok' => 0, 'err' => 'Permission Denied'), true);
        return false;
    }
    // ---------------------------------------------------------------------

    /**
     * Check Data Existed
     *
     * @param string $table table name need check
     * @param array $where where conditions to query
     * @return bool
     */
    protected function _dataExist($table, $where) {
        $select = '*';
        $data = $this->Admin_model->select($table, $select, $where);

        if($data['ok'] && count($data['result'])) {
            return true;
        }

        return false;
    }
    // ---------------------------------------------------------------------

    // End of class
}