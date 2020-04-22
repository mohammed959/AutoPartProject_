<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 02/11/18
 * Time: 12:16 ص
 */


$ERR_EXT = 0;
$ERR_UNKNOWN = 1;
$ERR_SIZE = 2;
$SUCCESS = 3;

function uploadImage($file, $path, $maxSize = 5242880)
{

    global $ERR_EXT, $ERR_UNKNOWN, $ERR_SIZE, $SUCCESS;

    $name = $file['name'];
    $tmpName = $file['tmp_name'];
    $size = $file['size'];
    $error = $file['error'];

    $ext = explode('.', $name);
    $ext = strtolower(end($ext));

    $allowed = array('png', 'jpg', 'jpeg', 'gif', 'bmp');
    if (in_array($ext, $allowed)) {
        if ($error === 0) {
            if ($size <= $maxSize) {
                $fileName = uniqid('', true) . '.' . $ext;
                $dest = $path . '/' . $fileName;

                if (move_uploaded_file($tmpName, $dest)) {
                    return $fileName;
                } else return $ERR_UNKNOWN;
            } else return $ERR_SIZE;
        } else return $ERR_UNKNOWN;
    } else return $ERR_EXT;

}

function isFileSet($file)
{
    return file_exists($file['tmp_name']) && is_uploaded_file($file['tmp_name']);
}