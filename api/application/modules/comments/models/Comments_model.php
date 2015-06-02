<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 5/20/15
 * Time: 5:31 AM
 */

require_once(APPPATH . 'models/Models.php');

class Comments_model extends Models {
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

    // End of Class
}
