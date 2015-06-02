<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 4/3/15
 * Time: 2:57 AM
 */

date_default_timezone_set('UTC');

header("Access-Control-Allow-Origin: *");

function access_control() {
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header ('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
}

access_control();