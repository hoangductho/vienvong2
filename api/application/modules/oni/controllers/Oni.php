<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 6/3/15
 * Time: 11:38 AM
 */

class Oni extends CI_Controller {
    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - load model class to query database
     */
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('asia/ho_chi_minh');
        $this->load->model('Oni_models');
    }

    public function income($uid) {
        $d1 = strtotime('2015-05-01 00:00:00');
        $d2 = strtotime('2015-04-01 00:00:00');
        $d = 60 * 60 * 24;
//        $uid = 2424;

        /*$sum = $this->Oni_models->get_income($uid, $d1, $d2);
        $end = 0;
        $inc = array();
        foreach($sum['result'] as $k => $v) {
            $inc[$k]['cash'] = $v['cash']/12;
            $inc[$k]['created'] = date('Y:m:d H:i:s', $v['created']);
            $end += $v['cash'];
        }*/

        /*$cash = array(
            'count' => 0,
            'inserted' => 0
        );

        $index = 0;
        for($i = $d1; $i >= $d2; $i -= $d){
            $out[$index]['date'] = date('Y:m:d', $i - 1);
            $result = $this->Oni_models->count_visit($uid, $i, $d);
            if(count($result['result'])) {
                $out[$index]['count'] = $result['result'][0]['count'];
//                $out[$index]['vs_created'] = $result['result'][0]['vs_created'];
            }else {
                $out[$index]['count'] = 0;
//                $out[$index]['vs_created'] = 0;
            }
//            var_dump($result['result']);
            $income = $this->Oni_models->count_income($uid, $i, $d);
//            var_dump($income['result']);
            if(count($income['result'])) {
                $out[$index]['inserted'] = $income['result'][0]['cash'] / 12;
                $out[$index]['created'] = date('Y:m:d H:i:s', $income['result'][0]['created']);
            }else {
                $out[$index]['inserted'] = 0;
                $out[$index]['created'] = 0;
            }
//            var_dump($out[$index]);
//            if($out[$index]['inserted'] != $inc[$index]['cash']) {
//                $out[$index]['inc'] = $inc[$index]['cash'];
//                var_dump($out[$index]);
//                $index -= 1;
//            }
            $cash['count'] += $out[$index]['count'];
            $cash['inserted'] += $out[$index]['inserted'];

            $index ++;
        }

        var_dump($cash);
//        var_dump($inc);
//        var_dump($end/12);

        $sum = $this->Oni_models->get_income($uid, $d1, $d2);
        $end = 0;
        $inc = array();
        foreach($sum['result'] as $k => $v) {
            $out[$k]['cash'] = $v['cash']/12;
            $out[$k]['inc_created'] = date('Y:m:d H:i:s', $v['created']);
            $end += $v['cash'];
        }

        var_dump($out);*/

        $result = $this->Oni_models->count_visit($uid, $d1, $d2);
        if(count($result['result'])) {
            $out['count'] = $result['result'][0]['count'];
//            $out['count'] = count($result['result']);
        }
//        $income = $this->Oni_models->count_income($uid, $d1, $d2);
//        if(count($income['result'])) {
//            $out['inserted'] = $income['result'][0]['cash'] / 12;
//        }
        var_dump($out);
//        var_dump($income);
    }

    public function action_update()
    {
        $d1 = strtotime('2015-06-00 00:00:00');
        $d2 = strtotime('2015-05-00 00:00:00');
        $d = 60 * 60 * 24;
        for($i = $d1; $i >= $d2; $i -= $d){
            $limit = 30000;
            $getDate = Arr::get($_GET, 'date');
            //echo $getDate;
            if($getDate<=0) { echo "000"; die; }
            $date_run = '2015-05-'.$getDate;
            $date_save = $cvdate = str_replace('_', '-', $date_run);
            $hasCron = $this->cronDaily($date_save);
            if (strpos($hasCron,'DONE') !== false){
                echo 'DONE';
                exit;
            };
            echo $date_run.'- ';
            $sd = strtotime($date_run.' 00:00:01');
            $ed = strtotime($date_run.' 23:59:39');
            $mongo = Class_Mongo::connect('mongo_visits');
            $ops = array(
                array('$match' => array('vs_created' => array('$gt' => $sd, '$lte' => $ed))),
                //array('$match' => array('vs_creator' => 2424)),
                array(
                    '$group' => array(
                        "_id" => '$vs_creator',
                        'total' => array('$sum' => 1),
                    ),
                ),
                //array('$sort' => array('vs_created' => -1)),
                //array('$skip' => 1000),
                // array('$limit' => $limit),
            );
            $list =  $mongo->aggregate($ops);

            if(!isset($list['result']) OR $list['result'] == array()){
                echo 'DONE';
                exit;
            }
            echo count($list['result']);
            $curlCash = file_get_contents('http://api.oni.vn/tools/mcash/'.date('m/Y'));
            $cash = json_decode($curlCash,TRUE);
            $money = $cash['data']['price']/1000;
            $percent = $cash['data']['percent']/100;
            $modelUser = new Model_User();
            foreach($list['result'] as  $k=>$v){
                $ttcash = $v['total']*$money;
                $bonus  = $ttcash*$percent;
                //echo $v['_id'].'<br />';
                $user = $modelUser->details($v['_id']);
                // Lưu dữ liệu bảng mn_thunhap chi tiết từng tháng
                Mango::factory('Mongo_Income', array(
                    'uid' => (int)$v['_id'],
                    'cash' => $ttcash,
                    'created' => $sd
                ))->create();
                //Lưu dữ liệu vào bảng hoa hồng hàng tháng
                if($user['magioithieu'] > 0 || $user['active']=='0'){
                    Mango::factory('Mongo_Commission', array(
                        'uid' => (int)$v['_id'],
                        'recipient' => (int)$user['magioithieu'],
                        'cash' => $bonus,
                        'created' => $sd
                    ))->create();
                    //Cộng hoa hồng cho người được hưởng
                    DB::update('mn_user')->set(array(
                        'hoahong' => DB::expr('hoahong+'.$bonus),
                        'cash' => DB::expr('cash+'.$bonus),
                        'tongthunhap' => DB::expr('tongthunhap+'.$bonus)
                    ))->where('id', '=', $user['magioithieu'])->execute();
                }
                //Lưu vào field cash trong user và update các thông tin
                $mn_user = array(
                    'cash' => DB::expr('cash+'.$ttcash),
                    'tongthunhap' => DB::expr('tongthunhap+'.$ttcash),
                    'clicktotal' =>  DB::expr('clicktotal+='.$v['total'])
                );
                DB::update('mn_user')->set($mn_user)->where('id', '=', $v['_id'])->execute();

            }
            $text  = 'DONE!'.count($list['result']);
            $this->cronDailyCreat($date_save,$text);
            die;
        }

    }

    // --------------------------------------------------------------------
}