<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 5/20/15
 * Time: 9:20 AM
 */

class Controller extends CI_Controller {

    /**
     * write permission values
     */
    protected $write = array(2, 3, 6, 7);

    // --------------------------------------------------------------------

    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - load model class to query database
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Models');
    }
    // --------------------------------------------------------------------

    /**
     * _GetUser Get user detail info
     *
     * @param string $select info need get.
     * @param array $where where conditions query.
     * @param int $limit number of user will be get.
     * @return array query result.
     */
    protected function _getUser($select, $where, $limit = 1) {
        $table = 'Users';

        $user = $this->Auth_model->select($table, $select, $where, $limit);

        return $user;
    }
    // --------------------------------------------------------------------

    /**
     * _UserExist Check User Existed
     *
     * @param array $where where conditions to query
     * @param bool $detail get detail info or check existed
     * @return mixed (array, 0, -1)
     */
    protected function _userExist($where, $detail = false) {
        $select = 'email';

        if($detail) {
            $select = '*';
        }

        $user = $this->_getUser($select, $where);

        if(!$user['ok'])
            return -1;

        if($user['ok'] && !count($user['result']))
            return 0;

        if($detail) {
            return $user;
        }

        return 1;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _CheckAccess
     * Type     : private
     * Task     :
     *      - Check access token have correct
     */
    protected function _getAccessInfo($where) {
        $table = 'AccessTokens';
        $select = '*';

        $access = $this->Models->select($table, $select, $where, 1);

        return $access;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _GetGroupInfo
     * Type     : private
     * Task     :
     *      - get group's info
     */
    protected function _getGroupInfo($where, $select = '*') {
        $table = 'Groups';

        $group = $this->Models->select($table, $select, $where, 1);

        return $group;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _GetGroupRole
     * Type     : private
     * Task     :
     *      - get user's role with the group
     */
    protected function _getGroupRole($where, $select = '*') {
        $table = 'Roles';

        $group = $this->Models->select_where_in($table, $select, $where, 1);

        return $group;
    }
    // --------------------------------------------------------------------

    /**
     * Check access from user access token
     *
     * @param string $code auth access code
     * @param string $fields fields data you want to return
     *
     * @todo analyst access code to get users_id and select database to validate this access code
     *
     * @return false|data return data of field or false when it invalid
     */
    protected function _checkAccess($code, $fields) {
        $client = $this->_client();

        list($signal, $data64) = explode('.', base64_decode($code), 2);

        $data = json_decode(base64_decode($data64), true);

        $valid_regexp = array("options"=>array("regexp"=>"/^\w{64}+$/"));

        if(isset($data['accessStatic']) && filter_var($data['accessStatic'], FILTER_VALIDATE_REGEXP, $valid_regexp) === $data['accessStatic']) {
            $where['_id'] = $data['accessStatic'];
            $where['status'] = true;
            $where['device'] = $client;

            $access = $this->_getAccessInfo($where);

            if($access['ok'] && count($access['result'])) {
                $valid = hash_hmac('sha256', $data64, $access['result'][0]['secretKey']);

                if($valid == $signal){
                    if($fields == '*') {
                        return $access['result'][0];
                    }

                    if(!is_array($fields)) {
                        $fields = explode(',', str_replace(' ', '',$fields));
                    }
                    foreach($fields as $key) {
                        $respond[$key] = $access['result'][0][$key];
                    }
                    return $respond;
                }
            }
        }

        return false;
    }
    // --------------------------------------------------------------------

    /**
     * Get client info
     *
     * @return array[] return host ip and host agent of client
     */
    protected function _client() {
        // Function to get the client IP address
        $ipAddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipAddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipAddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipAddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipAddress = getenv('REMOTE_ADDR');
        else
            $ipAddress = 'UNKNOWN';

        $host['ip'] = $ipAddress;
        $host['agent'] = $_SERVER['HTTP_USER_AGENT'];

        return $host;
    }
    // --------------------------------------------------------------------

    /**
     * Check permission of group user with the articles
     *
     * @param string $pid Article's ID need check
     * @param string $term user object need check (owner, groups, others)
     * @param array[] $permission permission value valid
     *
     * @return bool
     */
    protected function _checkTermPermission($pid, $term, $permission) {
        $where['_id'] = $pid;
        $table = 'Articles';
        $select = "_id, $term";
        $code = $this->Models->select($table, $select, $where);
        if(count($code['result'])) {
            return in_array($code['result'][0][$term], $permission);
        }

        return false;
    }
    // --------------------------------------------------------------------
    /**
     * Get Role's info
     *
     * @param string $rid id of role need get family
     * @return array info of the role
     */
    protected function _getRoleInfo($rid, $select) {
        $table = 'Roles';
        $where['_id'] = $rid;

        $role = $this->Admin_model->select($table,$select, $where);

        return $role;
    }
    // ---------------------------------------------------------------------

    /**
     * Data Validate
     *
     * @param string $name data need check valid.
     * @todo using regular expression to check data valid.
     * @return bool result check valid
     */
    protected function _groupNameValid($name) {
        $regexp = array("options" => array("regexp" => "/^[\\s\\w]{2,64}$/u"));
        $valid = filter_var($name, FILTER_VALIDATE_REGEXP, $regexp);

        if (!$valid) {
            return false;
        }

        return true;
    }
    // ---------------------------------------------------------------------

    /**
     * Email Validate
     *
     * @param string $email data need check valid.
     * @todo using regular expression to check data valid.
     * @return bool result check valid
     */
    protected function _emailValid($email) {
        $valid = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$valid) {
            return false;
        }

        return true;
    }
    // ---------------------------------------------------------------------

    /**
     * ID Validate
     *
     * @param string $id data need check valid.
     * @todo using regular expression to check data valid.
     * @return bool result check valid
     */
    protected function _idValid($id) {
        $regexp = array("options" => array("regexp" => "/^[\\w]{16,128}$/u"));
        $valid = filter_var($id, FILTER_VALIDATE_REGEXP, $regexp);

        if (!$valid) {
            return false;
        }

        return true;
    }
    // ---------------------------------------------------------------------

    // End of class
}