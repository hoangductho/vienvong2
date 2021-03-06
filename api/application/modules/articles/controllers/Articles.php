<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 3/20/15
 * Time: 4:32 PM
 */

/**
 * Class: Articles
 *
 * Description:
 *  - Search and Show all articles follow conditions: categories, keyword, tags, or text search
 *
 */
require_once(APPPATH . '/controllers/Controller.php');
class Articles extends Controller {

    /**
     * Table name to query
     */
    private $table = 'Articles';

    /**
     * Limited record query
     */
    private $limit = 10;

    /**
     * field name valid sent from client
     */
    private $valid = array(
        'title',
        'lAvatar',
        'sAvatar',
        'description',
        'content',
        'categories',
        'tags',
        'keyword',
        'series'
    );

    /**
     * permission value of articles with visitor
     */
    private $others = 0;

    //
    private $uploadPath = 'uploader/';

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
        $this->load->model('Articles_model');
    }
    // --------------------------------------------------------------------


    // --------------------------------------------------------------------

    /**
     * Function : _GetPermission
     * Type     : private
     * Task     :
     *      - Get permission of user with a group
     */
    private function _checkGroupPermission($uid, $gid, $permission) {
        $where['_id'] = $gid;
        $group = $this->_getGroupInfo($where, 'family');

        if($group['ok'] && count($group['result'])) {

            $family = explode('.',$group['result'][0]['family']);

            foreach($family as $key=>$value) {
                $family[$key] = md5($uid.md5($value));
            }

            $family[count($family)] = md5($uid.$gid);

            $role = $this->_getGroupRole(array('_id' => $family), '_id, permission');

            if($role['ok'] && ($count = count($role['result']))) {
                return in_array($role['result'][$count-1]['permission'], $permission);
            }
        }

        return false;
    }

    // ---------------------------------------------------------------------

    /**
     * Storage Avatar
     *
     * @param string $imagePath image uploaded path.
     * @param string $pid articles id.
     * @return bool result.
     */
    private function _storageAvatar($imagePath, $pid, $thumb = false) {
        if(!file_exists($imagePath)){
            return null;
        }

        $storagePath = $this->uploadPath.$pid;

        $storageName = "avatar_$pid.jpg";

        if($thumb) {
            $storageName = 'avatar_'.$pid.'_thumb.jpg';
        }

        // make directory for the article
        if(!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        // copy image to folder storage
        $copy = copy($imagePath, "$storagePath/$storageName");

        // delete template image uploaded
        if($copy) $delete = unlink($imagePath);

        return "$storagePath/$storageName";
    }
    // ---------------------------------------------------------------------

    /**
     * Function     : Index
     * Type         : Public
     * Task         : find and respond articles
     *
     */
    public function index($group = 'all', $page = 0) {

        $where = array(
            'others >' => $this->others
        );

        if($group !== '' && $group !== 'all') {
            $where['categories'] = $group;
        }

        $select = '_id, users_id, title, friendly, description, lAvatar, sAvatar, tags, series, firstTime';

        $articles = $this->Articles_model->find_articles($this->table, $select, $where, $this->limit, $page);
        $articles['page'] = $page;
        $articles['group'] = $group;

        echo json_encode($articles, true);

        if(isset($_COOKIE['2vu'])) {
            $this->_logging();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Function     : focus
     * Type         : Public
     * Task         : find and respond articles
     *
     */
    public function focus() {

        $where = array(
            'others >' => $this->others,
            'hot' => 1
        );

        $select = '_id, users_id, title, friendly, lAvatar, sAvatar';
        $articles = $this->Articles_model->find_articles($this->table, $select, $where, 4);

        echo json_encode($articles, true);
    }

    // --------------------------------------------------------------------

    /**
     * Function : Express
     * Type     : Public
     * Task     : Express content of the article
     * Params   :
     *      $id - String    - id of the article
     */
    public function express($id) {
        $where = array(
            '_id' => $id
        );

        $select = '_id, users_id, title, description, lAvatar, content, tags, series, friendly, keyword, firstTime';

        $detail = $this->Articles_model->detail_article($this->table, $where, $select);

        echo json_encode($detail, true);

        if(isset($_COOKIE['2vu'])) {
            $this->_logging();
        }
    }

    // --------------------------------------------------------------------
    /**
     * Suggest articles follow current
     *
     * @param string $pid articles id need suggest
     * @param string $text keyword to search articles suggested
     * @return array list article follow
     */
    public function suggest($pid, $text) {
        $articles = $this->_getSuggest($pid, $text);
        echo json_encode($articles, true);
    }
    // --------------------------------------------------------------------

    /**
     * Get Suggest Article From Database
     *
     * @param string $pid articles id need suggest
     * @param string $text keyword to search articles suggested
     * @return array list article follow
     */
    private function _getSuggest($pid, $text) {
        if(is_string($text) && strlen($text)) {
            $text = urldecode($text);
            $limit = 5;
            $select = '_id, title, friendly';
            $where['_id !='] = $pid;
            $articles = $this->Articles_model->suggest_articles('Articles', $text, $select, $where);


        }else {
            $articles = [
                'ok' => 0,
                'err' => 'no result'
            ];
        }

        return $articles;
    }

    // --------------------------------------------------------------------

    /**
     * Create Snapshot For Express Articles Page
     *
     * @param string $id Article's ID
     */
    public function snapshot($id) {
        $where = array(
            '_id' => $id
        );

        $select = '_id, title, description, lAvatar, content, tags, keyword';

        $detail = $this->Articles_model->detail_article($this->table, $where, $select);

        $data = $detail['result'][0];

        if(strlen($data['keyword']) >= 2)
        $suggest = $this->_getSuggest($id, $data['keyword']);

        if(isset($suggest['result'])) {
            $data['suggest'] = $suggest['result'];
        }

        $this->load->view('snapshot', $data);
    }
    // --------------------------------------------------------------------

    /**
     * Function : Detail
     * Type     : Public
     * Task     : get detail of the article
     * Params   :
     *      $id - String    - id of the article
     */
    public function detail($id) {
        $in = json_decode(file_get_contents('php://input'), true);

        if(!isset($in['auth']) || !$in['auth']) {
            echo json_encode(array('ok' => 0, 'err' => 'Auth invalid'), true);
        }else {
            $check = $this->_checkAccess($in['auth'], 'uid');

            $uid = $check['uid'];

            if($this->_idValid($uid)) {
                $where = array(
                    '_id' => $id
                );

                $select = '*';

                $detail = $this->Articles_model->detail_article($this->table, $where, $select);
                $data = $detail['result'][0];

                if($data['others'] === 0 && $data['users_id'] == $uid && in_array($data['owner'], $this->write)) {
                    foreach($this->valid as $key) {
                        $result[$key] = $data[$key];
                    }
                    echo json_encode(array('ok' => 1, 'result'=>array($result)), true);
                }else {
                    if(in_array($data['groups'], $this->write)) {
                        $role = $this->_checkGroupPermission($uid,$data['groups_id'],$this->write);
                        if($role){
                            foreach($this->valid as $key) {
                                $result[$key] = $data[$key];
                            }
                            echo json_encode(array('ok' => 1, 'result'=>array($result)), true);
                        }else {
                            echo json_encode(array('ok' => 0, 'err' => 'Permission denied'), true);
                        }

                    }else {
                        echo json_encode(array('ok' => 0, 'err' => 'Permission denied'), true);
                    }
                }
            }else {
                echo json_encode(array('ok' => 0, 'err' => 'Auth invalid'), true);
            }
        }

    }

    // --------------------------------------------------------------------

    /**
     * Function : Detail
     * Type     : Public
     * Task     : get detail of the article
     * Params   :
     *
     * @id - String    - id of the article
     */
    public function edit($id) {
        $in = json_decode(file_get_contents('php://input'), true);

        if(!isset($in['auth']) || !$in['auth']) {
            echo json_encode(array('ok' => 0, 'err' => 'Auth invalid'), true);
        }else {
            $check = $this->_checkAccess($in['auth'], 'uid');

            $uid = $check['uid'];

            if($this->_idValid($uid)) {
                $where = array(
                    '_id' => $id
                );

                $select = '_id, users_id, lAvatar, others, owner, groups, groups_id';

                $detail = $this->Articles_model->detail_article($this->table, $where, $select);
                $data = $detail['result'][0];

                if($data['others'] === 0 && $data['users_id'] == $uid && in_array($data['owner'], $this->write)) {
                    echo json_encode($detail, true);
                }else {
                    if(in_array($data['groups'], $this->write)) {
                        $role = $this->_checkGroupPermission($uid,$data['groups_id'],$this->write);
                        if($role){
                            // storage avatar
                            if($in['data']['lAvatar'] && $data['lAvatar'] != $in['data']['lAvatar']) {
                                $in['data']['lAvatar'] = $this->_storageAvatar($in['data']['lAvatar'], $id);;
                                $in['data']['sAvatar'] = $this->_storageAvatar($in['data']['sAvatar'], $id, true);
                            }

                            foreach ($this->valid as $key) {
                                $update[$key] = $in['data'][$key];
                            }

                            $lasttime = new DateTime(date('Y-m-d H:m:s'));
                            $update['lastTime'] = $lasttime->format('c');
                            $this->load->helper("url");
                            $update['friendly'] = mb_strtolower(url_title($this->_removesign($in['data']['title'])));

                            $updated = $this->Articles_model->update_articles($this->table, $update, $where, 0);

                            echo json_encode($updated, true);
                        }else {
                            echo json_encode(array('ok' => 0, 'err' => 'Permission denied'), true);
                        }

                    }else {
                        echo json_encode(array('ok' => 0, 'err' => 'Permission denied'), true);
                    }
                }
            }else {
                echo json_encode(array('ok' => 0, 'err' => 'Auth invalid'), true);
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Function : Detail
     * Type     : Public
     * Task     : get detail of the article
     * Params   :
     *
     */
    public function create() {
        $data = [];
        $post = json_decode(file_get_contents('php://input'), true);

        $check = $this->_checkAccess($post['auth'], 'uid');

        $access = $check['uid'];

        if(!$this->_idValid($access)) {
            $err = [
                'ok' => 0,
                'err' => "Permission Denied"
            ];
            echo json_encode($err, true);
            die();
        }

        $in = $post['data'];
        if(count($in)) {
            foreach ($this->valid as $key) {
                if(isset($in[$key])) {
                    $data[$key] = $in[$key];
                }else {
                    $err = [
                        'ok' => 0,
                        'err' => "$key just empty"
                    ];
                    echo json_encode($err, true);
                    return false;
                }
            }

            $data['users_id'] = $access;
            $data['firstTime'] = date('Y-m-d H:m:s');
            $data['_id'] = md5($data['users_id']. $data['firstTime']);

            $lasttime = new DateTime($data['firstTime']);
            $data['lastTime'] = $lasttime->format('c');

            // storage avatar
            $data['lAvatar'] = $this->_storageAvatar($data['lAvatar'], $data['_id']);
            $data['sAvatar'] = $this->_storageAvatar($data['sAvatar'], $data['_id'], true);

            $this->load->helper("url");
            $data['friendly'] = mb_strtolower(url_title($this->_removesign($data['title'])));
            $data['owner'] = 1;
            $data['groups'] = 7;
            $data['others'] = 2;
            $data['groups_id'] =  md5(strtolower(str_replace(' ','_',$data['categories'])));
            $data['hot'] = 0;

            $insert = $this->Articles_model->insert_articles($this->table, $data);

            $insert['_id'] = $data['_id'];
        }

        echo json_encode($insert, true);
    }
    // --------------------------------------------------------------------

    /**
     * Search articles
     *
     * @param string $text
     * @method Get
     * @todo using full text search function to find all articles follow
     */
    public function search($text = null, $page = 1) {
        if(is_string($text) && strlen($text)) {
            $text = urldecode($text);
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $select = '_id, users_id, title, friendly, description, lAvatar, sAvatar, tags, series, firstTime';
            $articles = $this->Articles_model->fulltextSearch('Articles', $text, $select, $offset, $limit);
            echo json_encode($articles, true);
        }

        if(isset($_COOKIE['2vu'])) {
            $this->_logging();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Function : Detail
     * Type     : Public
     * Task     : get detail of the article
     * Params   :
     *
     * @id - String    - id of the article
     */
    private function _removesign($str)
    {
        $coDau=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ"
        ,"ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ","ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
        ,"ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
        ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
        ,"Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ","ê","ù","à");
        $khongDau=array("a","a","a","a","a","a","a","a","a","a","a"
        ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o"
        ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A"
        ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O"
        ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D","e","u","a");
        return str_replace($coDau,$khongDau,$str);
    }

    // --------------------------------------------------------------------

    /**
     * Function : Detail
     * Type     : Public
     * Task     : get detail of the article
     * Params   :
     *
     * @id - String    - id of the article
     */
    public function convert() {
        $this->load->helper("url");

        $where = array(
            'Express' => 1
        );

        $select = '*';
        $articles = $this->Articles_model->find_articles('Posts', $select, $where, 0);

        foreach ($articles['result'] as $count => $index) {
            $art['_id'] = $index['PPID'];
            $art['users_id'] = md5('hoangductho');
            $art['title'] = $index['Title'];
            $art['friendly'] = mb_strtolower(url_title($this->_removesign($art['title'])));
            $art['sAvatar'] = $index['SAvatar'];
            $art['lAvatar'] = $index['LAvatar'];
            $art['description'] = filter_var(html_entity_decode(htmlspecialchars_decode($index['Sapo'])), FILTER_SANITIZE_STRIPPED);
            $art['content'] = html_entity_decode(htmlspecialchars_decode($index['Content']));
            $art['keyword'] = null;
            $art['categories'] = 'news';
            $art['tags'] = $index['Tag'];
            $art['series'] = $index['Keyword'];
            $art['firstTime'] = $index['Time'];
            $art['lastTime'] = $index['LastestTime'];
            $art['owner'] = 1;
            $art['groups'] = 7;
            $art['others'] = 2;
            $art['groups_id'] = md5('vienvong_1');
            $art['hot'] = $index['Location'];

            $insert = $this->Articles_model->insert_articles('Articles', $art);
            echo $count;
            var_dump($insert);
        }
    }

    // --------------------------------------------------------------------

    /*End of class*/
}