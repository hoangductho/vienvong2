<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/11/15
 * Time: 9:48 AM
 */

class photoProcess {
    // height of thumb image
    // default 200 pixel
    private $height = 200;

    // width of thumb image
    // default 200 pixel
    private $width = 200;

    // smooth resize from origin image to thumb
    // if $smooth = TRUE size of image will be ratio with origin
    // if $smooth = FALSE  size of image will be same is set
    // default TRUE
    private $smooth = true;

    // create thumb of origin image
    // if createThumb = TRUE create thumb image of origin
    // if createThumb = FALSE don't create it
    // default TRUE
    private $createThumb = true;

    // path to store image
    private $path = '';

    // ---------------------------------------------------------------------

    /**
     * SET SIZE
     *
     * @param array $options options setting.
     * @return object
     */
    public function set($options = array()) {
        foreach($options as $key => $value) {
            if(isset($this->{$key})){
                $this->{$key} = $value;
            }
        }
        return $this;
    }
    // ---------------------------------------------------------------------

    /**
     * Function : upload
     * Type     : Public
     * Task     :
     *      - upload image to server
     */
    public function photo64($source64, $name = null) {

        if($name == null) {
            $name = md5(time() . rand(0, 9));
        }

        $origin = $this->path.$name.'.jpg';

        $data = explode(",", $source64);

        $source = base64_decode($data[1]);

        $image = fopen($origin, "w") or die("Unable to open file!");

        fwrite($image, $source);

        fclose($image);

        $respond['photo'] = $origin;

        if($this->createThumb) {
            $thumb = $this->path.$name.'_thumb.jpg';
            $this->thumb($origin, $thumb, $this->width, $this->height);
            $respond['thumb'] = $thumb;
        }

        return $respond;
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
        if($this->smooth) {
            if($width >= $height) {
                $setHeight = ($setWidth / $width) * $height;
            }else {
                $setWidth = ($setHeight / $height) * $width;
            }
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