<?php
/**
 * Created by PhpStorm.
 * User: malal
 * Date: 11/5/2018
 * Time: 12:31 PM
 */


function hashSh3($data, $salt)
{
    return hash('sha3-512', $data . $salt);
}