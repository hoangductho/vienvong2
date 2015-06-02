<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 4/12/15
 * Time: 10:25 AM
 *
 *
 * Class: Auth
 *
 * Description:
 *  - Authenticate function to process authentication of user
 *
 */

require_once(APPPATH . '/libraries/Security/Crypt.php');

use CI\Security\Algorithm\Crypt;

class Auth extends CI_Controller {

    /**
     * Name: Default_User_info
     *
     * Description:
     *  - Default info of user
     *  - If that info not set, system will be using that info as that user
     */
    private $default_user_info = array(
        'avatar' => '',
        'fbID' => null,
        'googleID' => null,
        'fullName' => null,
        'sex' => null,
        'birthday' => null,
        'town' => null,
        'status' => 'pending',
        'phone' => null
    );

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
        $this->load->model('Auth_model');
    }

    // --------------------------------------------------------------------

    /**
     * Function : _RsaInsert
     * Type     : Private
     * Task     :
     *      - Insert RSA key in database
     */
    public function publicKey() {

        $key = $this->_getRsaKey();

        echo json_encode($key);
    }

    // --------------------------------------------------------------------

    /**
     * Function : _RsaInit
     * Type     : Private
     * Task     :
     *      - get RSA Key
     */
    private function _getRsaKey($private = false) {
        $table = 'Crypt';

        $select = 'publicHex, publicHash, date';

        if($private) {
            $select = 'private, publicHex, publicHash, date';
        }

        $date = date('Y:m:d');
        $where['_id'] = hash('sha256', base64_encode('rsa' . $date));

        $key = $this->Auth_model->select($table, $select, $where);

        if(!count($key['result'])) {
            $rsa = $this->_rsaInit();
            $insert = $this->Auth_model->insert($table, $rsa);

            if($insert) {
                $key = array(
                    'publicHex' => $rsa['publicHex'],
                    'publicHash' => $rsa['publicHash'],
                    'date' => $date
                );
            }
        }else {
            $key = $key['result'][0];
        }

        return $key;
    }

    // --------------------------------------------------------------------

    /**
     * Function : _RsaInit
     * Type     : Private
     * Task     :
     *      - Create RSA Key
     */
    private function _rsaInit() {
        $crypt = new Crypt();

        $data = $crypt->rsaInit();

        $data['date'] = date('Y:m:d');
        $data['_id'] = hash('sha256', base64_encode('rsa' . $data['date']));
        $data['algorithm'] = 'rsa';
        $data['publicHash'] = hash('sha256', base64_encode($data['publicHex']));

        return $data;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _AuthDataValid
     * Type     : Private
     * Task     :
     *      - Check valid of auth input data
     */
    private function _authDataValid($data) {
        if (isset($data['email']) && (filter_var($data['email'], FILTER_VALIDATE_EMAIL) != $data['email'])) {
            $result = array(
                'ok' => 0,
                'message' => 'E-mail invalid'
            );

            echo json_encode($result, true);

            return false;
        }

        $fullname_regexp = array("options"=>array("regexp"=>"/^[\\s\\w]{6,64}$/u"));

        if(isset($data['fullname']) && filter_var($data['fullname'], FILTER_VALIDATE_REGEXP, $fullname_regexp) != $data['fullname']) {
            $result = array(
                'ok' => 0,
                'message' => 'Fullname invalid'
            );

            echo json_encode($result, true);

            return false;
        }

        $pass_regexp = array("options"=>array("regexp"=>"/^[\\S]{8,256}$/"));

        if(isset($data['password']) && filter_var($data['password'], FILTER_VALIDATE_REGEXP, $pass_regexp) != $data['password']) {
            $result = array(
                'ok' => 0,
                'message' => 'Password invalid'
            );

            echo json_encode($result, true);

            return false;
        }

        $valid_regexp = array("options"=>array("regexp"=>"/^\w{64}+$/"));

        if(isset($data['valid']) && filter_var($data['valid'], FILTER_VALIDATE_REGEXP, $valid_regexp) != $data['valid']) {
            $result = array(
                'ok' => 0,
                'message' => 'Password invalid'
            );

            echo json_encode($result, true);

            return false;
        }

        return true;
    }
    // --------------------------------------------------------------------

    /**
     * Function : Registry
     * Type     : Public
     * Task     :
     *      - Registry new account
     */
    public function _rsaDecrypt($data) {
        $key = $this->_getRsaKey(true);

        $crypt = new Crypt();

        $decrypt = $crypt->rsaDecrypt($key['private'], $data);

        return $decrypt;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _GetUser
     * Type     : Private
     * Task     :
     *      - Get info of user
     */
    private function _getUser($select, $where, $limit = 1) {
        $table = 'Users';

        $user = $this->Auth_model->select($table, $select, $where, $limit);

        return $user;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _CreateUserAccessToken
     * Type     : Private
     * Task     :
     *      - Get info of user
     */
    private function _createUserAccessToken($user, $host) {
        if(!$user['email'] && !$user['password']) {
            return false;
        }

        if(!$user['_id']) {
            $user['_id'] = hash('sha256', $user['email']);
        }

        $crypt = new Crypt();

        $data['uid'] = $user['_id'];

        $data['user_info']['uid'] = $user['_id'];
        $data['user_info']['fullName'] = $user['fullName'];
        $data['user_info']['avatar'] = $user['avatar'];

        $data['secretKey'] = $crypt->randomKey(16);

        $auth_hash = hash_hmac('sha256', $user['email'], $user['password']);

        $data['time'] = time();

        $data['longLiveToken'] = hash('sha512', $data['secretKey'] + $auth_hash + $host['agent'] + $data['time']);

        $data['shortTime'] = $data['time'] + 86400;

        $data['shortLiveToken'] = hash('sha512', $data['longLiveToken'] + $host['ip']);

        $data['device'] = $host;

        $data['_id'] = hash('sha256',$data['longLiveToken']);

        $data['status'] = true;

        $token = $this->Auth_model->insert('AccessTokens', $data);

        if($token['ok'] && $token['err'] == null) {
            $data64 = base64_encode(json_encode(array('uid' => $data['uid'], 'accessStatic' => $data['_id'],'accessToken' => $data['shortLiveToken']), true));

            $data64_hash = hash_hmac('sha256', $data64, $data['secretKey']);

            $respond['code'] = base64_encode($data64_hash.'.'.$data64);
            $respond['access'] = $data['shortLiveToken'];
            $respond['info'] = $data['user_info'];
            $respond['ok'] = 1;

            return json_encode($respond, true);
        }else {
            return 0;
        }
    }
    // --------------------------------------------------------------------

    /**
     * Function : _client
     * Type     : private
     * Task     :
     *      - Get client info
     */
    private function _client() {
        // Function to get the client IP address
        $ipAddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipAddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipAddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipAddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipAddress = getenv('REMOTE_ADDR');
        else
            $ipAddress = 'UNKNOWN';

        $host['ip'] = $ipAddress;
        $host['agent'] = $_SERVER['HTTP_USER_AGENT'];

        return $host;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _userExist
     * Type     : Private
     * Task     :
     *      - Check user existed
     */
    private function _userExist($where, $detail = false) {
        $select = 'email';

        if($detail) {
            $select = '*';
        }

        $user = $this->_getUser($select, $where);

        if(!$user['ok'])
            return -1;

        if($user['ok'] && !count($user['result']))
            return 0;

        if($detail) {
            return $user;
        }

        return 1;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _createUser
     * Type     : Private
     * Task     :
     *      - create new user
     */
    private function _createUser($data) {
        $table = 'Users';
        $result = array(
            'done' => 0,
            'message' => 'E-mail or Password invalid'
        );

        if(!$data['email'] && !$data['password']) {
            return $result;
        }

        $data['_id'] = hash('sha256', $data['email']);

        $where['_id'] = $data['_id'];

        $exist = $this->_userExist($where);

        if($exist === 0) {
            $data['time'] = date('Y:d:m H:m:s');
            $data['liveTime'] = $data['time'];
            $data['password'] = hash('sha256', $data['password']);

            foreach($this->default_user_info as $key => $value) {
                if(!isset($data[$key])) {
                    $data[$key] = $value;
                }
            }

            $user = $this->Auth_model->insert($table, $data);

            $result['done'] = 1;

            if($user['ok'] && !$user['err'])
                $result['message'] = 'success';
            else
                $result['message'] = 'Registry is error. Please retry it later';
            return $result;
        }else {
            $result['message'] = 'This e-mail is existed';
            return $result;
        }
    }
    // --------------------------------------------------------------------

    /**
     * Function : _Base64_url_decode
     * Type     : private
     * Task     :
     *      - decode base64 data
     */
    private function _base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }
    // --------------------------------------------------------------------

    /**
     * Function : _CheckAccess
     * Type     : private
     * Task     :
     *      - Check access token have correct
     */
    private function _getAccessInfo($where) {
        $table = 'AccessTokens';
        $select = '*';

        $access = $this->Auth_model->select($table, $select, $where, 1);

        return $access;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _CheckAccess
     * Type     : private
     * Task     :
     *      - Check access token have correct
     */
    private function _checkAccess($code, $client) {
        list($signal, $data64) = explode('.', base64_decode($code), 2);

        $data = json_decode(base64_decode($data64), true);

        $valid_regexp = array("options"=>array("regexp"=>"/^\w{64}+$/"));

        if(isset($data['accessStatic']) && filter_var($data['accessStatic'], FILTER_VALIDATE_REGEXP, $valid_regexp) === $data['accessStatic']) {
            $where['_id'] = $data['accessStatic'];
            $where['status'] = true;
            $where['device'] = $client;

            $access = $this->_getAccessInfo($where);

            if($access['ok'] && count($access['result'])) {
                $valid = hash_hmac('sha256', $data64, $access['result'][0]['secretKey']);

                if($valid == $signal){
                    return $data['accessStatic'];
                }
            }
        }

        return false;
    }
    // --------------------------------------------------------------------

    /**
     * Function : _CheckAccess
     * Type     : private
     * Task     :
     *      - Check access token have correct
     */
    private function _endAccess($id) {
        $where['_id'] = $id;
        $data['status'] = false;

        $end = $this->_changeAccess($where, $data);

        if($end['ok'] && !$end['err']) {
            return true;
        } else {
            return false;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Function : _CheckAccess
     * Type     : private
     * Task     :
     *      - Check access token have correct
     */
    private function _changeAccess($where, $data) {
        $table = 'AccessTokens';

        return $this->Auth_model->update($table, $data, $where, 1);
    }

    // --------------------------------------------------------------------

    /**
     * Function : Registry
     * Type     : Public
     * Task     :
     *      - Registry new account
     */
    public function registry() {
        $in = json_decode(file_get_contents('php://input'), true);

        $decrypt = $this->_rsaDecrypt($in['auth']);

        list($email, $pass, $fullname) = explode('/',$decrypt);

        $data = array(
            'email' => $email,
            'password' => $pass,
            'fullname' => $fullname
        );

        if(!$this->_authDataValid($data)) {
            return false;
        }

        $registry = $this->_createUser($data);

        echo json_encode($registry, true);

    }
    // --------------------------------------------------------------------

    /**
     * Function : Login
     * Type     : Public
     * Task     :
     *      - Registry new account
     */
    public function login() {
        $in = json_decode(file_get_contents('php://input'), true);

        $decrypt = $this->_rsaDecrypt($in['auth']);

        list($email, $valid, $ip) = explode('/',$decrypt);

        $data = array(
            'email' => $email,
            'valid' => $valid
        );

        if(!$this->_authDataValid($data)) {
            return false;
        }

        $where['_id'] = hash('sha256', $email);

        $user = $this->_userExist($where, true);

        if(!is_array($user)) {
            echo 0;
        }else {
            $hash = hash_hmac('sha256', $user['result'][0]['email'], $user['result'][0]['password']);

            if($hash == $valid) {
                // Function to get the client IP address
                $client = $this->_client();

                $token = $this->_createUserAccessToken($user['result'][0], $client);

                echo $token;
            }else {
                echo 0;
            }
        }
    }
    // --------------------------------------------------------------------

    /**
     * Function : RefreshToken
     * Type     : Public
     * Task     :
     *      - refresh Access Token
     *      - replace old access token return new of it
     */
    public function refreshToken() {
        $in = json_decode(file_get_contents('php://input'), true);
    }
    // --------------------------------------------------------------------

    /**
     * Function : logout
     * Type     : Public
     * Task     :
     *      - logout user online
     *      - replace access token of user
     */
    public function logout() {
        $in = json_decode(file_get_contents('php://input'), true);
        $client = $this->_client();

        $id = $this->_checkAccess($in['auth'], $client);

        if($id) {
            $end = $this->_endAccess($id);

            if($end) {
                echo json_encode(array('ok' => 1), true);
            }else {
                echo json_encode(array('ok' => 0), true);
            }
        }else {
            echo json_encode(array('ok' => $id), true);
        }
    }
    // --------------------------------------------------------------------

    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - load model class to query database
     */
    public function facebook() {
        $in = json_decode(file_get_contents('php://input'), true);
        list($signal, $payload) = explode('.', $in['signedRequest'], 2);

        $appID = '550251971759267';
        $secret = "a38bfb60e1649061029d529915e33c07"; // Use your app secret here

        $signal = $this->_base64_url_decode($signal);

        $data = json_decode($this->_base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);

        if ($signal !== $expected_sig) {
            echo json_encode(array('ok' => 0, 'err' => 'data error'));
        }else {
            // Link to GET user info
            $facebook_access_token_uri = "https://graph.facebook.com/me?"
                . "access_token={$in['accessToken']}";

            // Connect and GET access_token of user from Facebook
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $facebook_access_token_uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($ch);
            curl_close($ch);

            // access token
            $userInfo = str_replace('access_token=', '', explode("&", $response)[0]);

            $userInfo = json_decode($userInfo, true);

            if($data['user_id'] == $userInfo['id']) {
                $return = [
                    'email' => $userInfo['email'],
                    'token' => 1,
                    'code' => 'abcd1234'
                ];

                echo json_encode($return, true);
            }
        }

    }


    public function test() {
        $where['_id'] = 'ea39cb541c2826d3c0758876b052645589b3a0677009ddf27d686d60654b8706';

        $access = $this->_getAccessInfo($where);

        var_dump($access);

        // Function to get the client IP address
        /*$ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        echo 'Test';
        var_dump($ipaddress);

        $useragent=$_SERVER['HTTP_USER_AGENT'];
        $mobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
        if($mobile)
            echo $mobile;
        else echo $useragent;

        $data['_id'] = 1;
        $data['ip'] = $ipaddress;
        $data['agent'] = $useragent;

        $test = $this->Auth_model->insert('test', $data);

        var_dump($test);*/

        /*$where['_id'] = 1;
        $data['ip'] = 'UNKNOWN';

        $test = $this->Auth_model->update('test', $data, $where);

        var_dump($test);*/

        /*include(APPPATH . '/libraries/phpseclib/Crypt/RSA.php');
        require_once(APPPATH . '/libraries/phpseclib/Math/BigInteger.php');
        $rsa = new Crypt_RSA();

        $rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS1);

        extract($rsa->createKey(1024));
        echo "$publickey<br>";
        echo '<br><br>';
        echo $privatekey;

        $rsa2 = new Crypt_RSA();
        $rsa2->loadKey($publickey); // public key

//        $plaintext = new Math_BigInteger('abcd1234', 16);
        $plaintext = 'abcd1234';
        $rsa2->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

        $ciphertext = base64_encode($rsa2->encrypt($plaintext));
        echo "<br>encrypt1: $ciphertext<br>";

        $rsa2->loadKey($privatekey); // public key

//        $encrypted=pack('H*', base64_decode($ciphertext));

        echo $rsa2->decrypt(base64_decode($ciphertext));

        $raw = $rsa2->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_RAW);
        echo '<br>hex: <br>';
        echo $raw['n']->toHex();
        echo '<br>end hex: <br>';*/

    }
    // --------------------------------------------------------------------
}