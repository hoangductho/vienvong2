<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 4/7/15
 * Time: 7:15 AM
 */

/**
 * Class: Photos
 *
 * Description:
 *  - management photos module
 *  - upload photos
 *  - browser photos in server
 *  - delete photos
 *
 */

require_once(APPPATH.'controllers/Controller.php');

class Photos extends Controller {
    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     */
    public function __construct() {
        parent::__construct();
    }

    // ---------------------------------------------------------------------

    /**
     * Function : upload
     * Type     : Public
     * Task     :
     *      - upload image to server
     */
    public function upload64() {
        $post = json_decode(file_get_contents('php://input'), true);

        $access = $this->_checkAccess($post['auth'],'shortLiveToken');

        if(!isset($access['shortLiveToken'])) {
            $result['err'] = 'Permission Denied';
            echo json_encode($result, true);
            die();
        }

        $imgName = md5('avatar.'.$access['shortLiveToken']);

        $options = array(
            'path' => 'uploader/tmpImgStore/',
            'width' => 400,
            'height' => 400
        );

        require_once('photoProcess.php');
        $photo = new photoProcess();
        $photo->set($options);

        $upload = $photo->photo64($post['source'], $imgName);

        echo json_encode($upload, true);
    }
    // ---------------------------------------------------------------------

    // End of class
}