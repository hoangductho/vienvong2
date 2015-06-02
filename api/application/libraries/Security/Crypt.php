<?php
/**
 * User: hoanggia
 * Date: 4/23/15
 * Time: 9:11 AM
 *
 * Class: Security
 *
 * - all function security of site implement in here
 * - using namespace to call class security
 */

namespace CI\Security\Algorithm;

class Crypt
{

    private $rsa = false;

    /**
     * Function : Construct
     * Type     : Public
     * Task     :
     *      - Init class and overwrite older class
     *      - load model class to query database
     */
    public function __construct()
    {
        $path = APPPATH . '/libraries/phpseclib';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
        include_once('Crypt/RSA.php');

        $this->rsa = new \Crypt_RSA();
    }

    // --------------------------------------------------------------------

    /**
     * Function : rsaInit
     * Type     : Public
     * Task     :
     *      - create RSA Key
     *
     */
    public function rsaInit()
    {


        $this->rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
        $this->rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS1);

        $key = $this->rsa->createKey(1024);

        $data['public'] = $key['publickey'];
        $data['private'] = $key['privatekey'];

        $this->rsa->loadKey($data['private']);

        $raw = $this->rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_RAW);

        $data['publicHex'] = $raw['n']->toHex();

        return $data;
    }

    // --------------------------------------------------------------------

    /**
     * Function : RandomKey
     * Type     : Public
     * Task     :
     *      - create random string width length is set
     *
     */
    public function randomKey($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
    // --------------------------------------------------------------------

    /**
     * Function : rsaInit
     * Type     : Public
     * Task     :
     *      - create RSA Key
     *
     */
    public function rsaDecrypt($privateKey, $encryption)
    {
        $encrypted = pack('H*', $encryption);
        $this->rsa->loadKey($privateKey);

        $this->rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);

        $decrypt = $this->rsa->decrypt($encrypted);

        return $decrypt;
    }
    // --------------------------------------------------------------------
}
