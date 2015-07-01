<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 4/3/15
 * Time: 2:57 AM
 */

date_default_timezone_set('UTC');

if($_SERVER['HTTP_ORIGIN'] === 'http://localhost:9000'){
    header("Access-Control-Allow-Origin: http://localhost:9000");
}

if($_SERVER['HTTP_ORIGIN'] === 'http://beta.vienvong.vn'){
    header("Access-Control-Allow-Origin: http://beta.vienvong.vn");
}

if($_SERVER['HTTP_ORIGIN'] === 'http://backend.vienvong.vn'){
    header("Access-Control-Allow-Origin: http://backend.vienvong.vn");
}

function access_control() {
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header ('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
}

function hostAllow() {
    $hostList = array(
        'http://localhost:9000',
        'http://vienvong.vn',
        'http://backend.vienvong.vn'
    );

    if(!in_array($_SERVER['HTTP_ORIGIN'], $hostList)) {
        die('<h1>500 - Access Reject</h1>');
    }
}

#hostAllow();
access_control();

