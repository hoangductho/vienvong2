<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/16/15
 * Time: 4:26 PM
 */

require_once('Admin.php');

class Admin_SEO extends CI_Controller{
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
    }

    // ---------------------------------------------------------------------

    /**
     * Get Frontend Directory
     *
     * @return String frontend directory's path
     */
    private function _getFrontendPath() {
        $path = pathinfo(FCPATH);
        $frontend = $path['dirname'].'/frontend/dist';

        return $frontend;
    }
    // ---------------------------------------------------------------------

    /**
     * Site-map Init
     */
    private function _siteMapInit() {
        $table = 'Articles';
        $select = '_id, friendly';
        $where = array(
            'others >=' => 1
        );
        $limit = 0;

        $allArticles = $this->Admin_model->select($table, $select, $where, $limit);

        $sitemap = '';

        foreach($allArticles['result'] as $art) {
            $sitemap .= "<url>
                            <loc>http://vienvong.vn/express/".$art['_id']."</loc>
                            <changefreq>daily</changefreq>
                            <priority>1.0</priority>
                        </url>
                        ";
        }

        $sitemap = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                        <url>
                            <loc>http://www.vienvong.vn</loc>
                            <changefreq>daily</changefreq>
                            <priority>1.0</priority>
                        </url>'
                        . $sitemap.
                    '</urlset>';
        return $sitemap;
    }


    // ---------------------------------------------------------------------
    public function sitemap() {
        $frontend = $this->_getFrontendPath();
        $sitemap = $this->_siteMapInit();

        $fopen = fopen($frontend.'/sitemap.xml', 'w') or die("Unable to open file!");

        fwrite($fopen, $sitemap);

        fclose($fopen);

        return true;
    }

    public function metadata() {
        $table = 'Articles';
        $select = '_id, tags, series, firstTime';
        $where = array(
            'others >=' => 1
        );
        $limit = 0;

        $allArticles = $this->Admin_model->select($table, $select, $where, $limit);

        $meta = array();

        foreach ($allArticles as $art) {
            $com = $art['tags'].','.$art['series'];

            $data = explode($com, ',');

            foreach($data as $key) {
                $key = trim(strtolower($key));
                if(strlen($key) >= 2) {
                    $id = md5($key);
                    if(!isset($meta[$id])) {
                        $meta[$id] = array(
                            '_id' => $id,
                            'data' => $key,
                            'pid' => $art['_id'],
                            'firstTime' => $art['firstTime']
                        );
                    }
                }
            }
        }

        $insert = $this->Admin_model->insert_batch('metadata', $meta);

        var_dump($insert);

//        foreach($meta as $value) {
//
//        }
    }
}