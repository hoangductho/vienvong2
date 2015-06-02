<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 5/20/15
 * Time: 5:31 AM
 */

require_once(APPPATH.'controllers/Controller.php');

class Comments extends Controller {

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
        $this->load->model('Comments_model');
    }
    // --------------------------------------------------------------------

    /**
     * Add Comment
     *
     * @param string $pid posts_id of the article will bee added this comment
     *
     * @todo check auth of add comment actions and write comment to database
     *
     * @return json result status of add comment action and error message if to have
     */
    public function add($pid) {
        $in = json_decode(file_get_contents('php://input'), true);

        if(!isset($in['auth']) || !$in['auth']) {
            echo json_encode(array('ok' => 0, 'err' => 'Auth invalid'), true);
            return false;
        }

        $term = $this->_checkTermPermission($pid, 'others', $this->write);
        $access = $this->_checkAccess($in['auth'], 'uid');

        if($access && $term) {
            $time = date('Y:d:m H:m:s');

            $cid = md5($pid.$access.$time);
            $data = array (
                '_id' => $cid,
                'pid' => $pid,
                'uid' => $access,
                'content' => $in['data'],
                'time' => $time
            );
            $comment = $this->Comments_model->insert('Comments', $data);

            $comment['data'] = $data;

            echo json_encode($comment);
        }else {
            echo json_encode(array('ok' => 0, 'err' => 'Permission denied. \n access: '.$access.'\n term: '.$term), true);
        }
    }
    // --------------------------------------------------------------------

    /**
     * Get comments of the article
     *
     * @param string $pid ID of article
     * @param int $page page comment wish load
     *
     * @output array[] list comment
     */
    public function index($pid, $page = 1) {
        $where['pid'] = $pid;
        $limit = 10;
        if($page < 1) $page = 1;
        $comments = $this->Comments_model->select('Comments', '*', $where, $limit, ($page -1) * $limit);

        if($comments['ok'] && count($comments['result'])) {
            $u_where['_id'] = array();
            foreach($comments['result'] as $data) {
                if(!in_array($data['uid'], $u_where['_id'])) {
                    array_push($u_where['_id'], $data['uid']);
                }
            }
            $u_info = $this->Comments_model->select_where_in('Users','_id, fullName, avatar',$u_where);

            if($u_info['ok'] && count($u_info['result'])) {
                $users = array();
                foreach($u_info['result'] as $info) {
                    $users[$info['_id']] = $info;
                }

                foreach($comments['result'] as $index=>$comment) {
                    $comments['result'][$index]['author'] = $users[$comment['uid']];
                }
            }
        }

        echo json_encode($comments);
    }
    // --------------------------------------------------------------------

    // End of class
}