<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 4/24/15
 * Time: 3:31 AM
 *
 * Class: Sec_DB_Communicate
 *
 * Description:
 *  - Security connect to Database
 *
 */

//namespace CI\Security\DB;

require_once(APPPATH . 'models/Models.php');

class Auth_model extends Models {
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

    // End of class
}
