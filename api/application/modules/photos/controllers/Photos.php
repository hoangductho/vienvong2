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

class Photos extends CI_Controller {
    // ---------------------------------------------------------------------

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
        $origin = 'test.jpg';
        $thumb = 'test_thumb.jpg';

        $post = json_decode(file_get_contents('php://input'), true);

        $data = explode(",", $post['source']);

        $source = base64_decode($data[1]);

        $image = fopen($origin, "w") or die("Unable to open file!");

        fwrite($image, $source);

        $create_thumb = $this->thumb($origin, $thumb, 200, 200);

        $respond = [
            'ok' => 1,
            'imageUrl' => $thumb
        ];

        echo json_encode($respond, true);
    }

    // ---------------------------------------------------------------------

    /**
     * Function : upload
     * Type     : Public
     * Task     :
     *      - upload image to server
     */
    private function thumb($source, $output, $setWidth, $setHeight) {

        // Content type
        header('Content-Type: image/jpeg');

        // Get new sizes
        list($width, $height) = getimagesize($source);

        // compute width and height of thumb
        if($width >= $height) {
            $setHeight = ($setWidth / $width) * $height;
        }else {
            $setWidth = ($setHeight / $height) * $width;
        }

        // Load
        $thumb = imagecreatetruecolor($setWidth, $setHeight);
        $source = imagecreatefromjpeg($source);

        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $setWidth, $setHeight, $width, $height);

        // Output
        return imagejpeg($thumb, $output);
    }
}