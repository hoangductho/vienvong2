<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/16/15
 * Time: 4:26 PM
 */

require_once('Admin.php');

class Admin_SEO extends Admin{
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
        $time = date('c');
        $table = 'Articles';
        $select = '_id, friendly, lastTime';
        $where = array(
            'others >=' => 1
        );
        $limit = 0;
        $order = array(
            'firstTime' => 'DESC'
        );

        $allArticles = $this->Admin_model->select($table, $select, $where, $limit, $order);

        $sitemap = '';

        foreach($allArticles['result'] as $art) {
            /*if(isset($art['lastTime']) && $art['lastTime'] > $art['firstTime']) {
                $art['firstTime'] = $art['lastTime'];
            }

            if($art['firstTime'][4] == ':') {

                $date = DateTime::createFromFormat('Y:d:m H:m:s', $art['firstTime']);
                $art['firstTime'] = $date->format('c');
            }else {
                $newdate = new DateTime($art['firstTime']);
                $art['firstTime'] = $newdate->format('c');
            }*/

            $sitemap .= '<url>
                            <loc>http://vienvong.vn/express/'.$art['_id'].'/'.$art['friendly'].'</loc>
                            <lastmod>'.$art['lastTime'].'</lastmod>
                            <changefreq>weekly</changefreq>
                        </url>
                        ';
        }

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
                    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
                          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                          xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                                http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
                        <url>
                            <loc>http://www.vienvong.vn</loc>
                            <changefreq>daily</changefreq>
                            <lastmod>'.$time.'</lastmod>
                            <priority>1.0</priority>
                        </url>
                        <url>
                            <loc>http://www.vienvong.vn/news</loc>
                            <changefreq>daily</changefreq>
                            <lastmod>'.$time.'</lastmod>
                            <priority>0.8</priority>
                        </url>
                        <url>
                            <loc>http://www.vienvong.vn/blog</loc>
                            <changefreq>daily</changefreq>
                            <lastmod>'.$time.'</lastmod>
                            <priority>0.8</priority>
                        </url>
                        <url>
                            <loc>http://www.vienvong.vn/tutorials</loc>
                            <changefreq>daily</changefreq>
                            <lastmod>'.$time.'</lastmod>
                            <priority>0.8</priority>
                        </url>
                        <url>
                          <loc>http://vienvong.vn/info/about</loc>
                          <lastmod>2015-06-23T17:16:58+00:00</lastmod>
                        </url>
                        <url>
                          <loc>http://vienvong.vn/info/privacy</loc>
                          <lastmod>2015-06-23T17:16:58+00:00</lastmod>
                        </url>
                        <url>
                          <loc>http://vienvong.vn/info/copyright</loc>
                          <lastmod>2015-06-23T17:16:58+00:00</lastmod>
                        </url>
                        <url>
                          <loc>http://vienvong.vn/info/sitemap</loc>
                          <lastmod>2015-06-23T17:16:58+00:00</lastmod>
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

        echo json_encode(array('ok' => 1), true);
    }

    private function get_metadata() {
        $table = 'Articles';
        $select = '_id, tags, series, firstTime';
        $where = array(
            'others >=' => 1
        );
        $limit = 0;

        $all = $this->Admin_model->select($table, $select, $where, $limit);
        $allArticles = $all['result'];

        $meta = array();

        foreach ($allArticles as $art) {
            $com = $art['tags'].','.$art['series'];

            $data = explode(',', $com);

            foreach($data as $key) {
                $key = trim(strtolower($key));
                if(strlen($key) >= 2) {
                    $id = md5($key);
                    if(!isset($meta[$id])) {
                        $meta[$id] = array(
                            '_id' => $id,
                            'data' => ucfirst($key),
                            'pid' => $art['_id'],
                            'firstTime' => $art['firstTime']
                        );
                    }
                }
            }
        }

        $insert = $this->Admin_model->insert_batch('metadata', $meta);

        var_dump($insert);
    }
}