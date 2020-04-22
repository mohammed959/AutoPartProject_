<?php
/**
 * Created by PhpStorm.
 * User: mohammad
 * Date: 25/10/18
 * Time: 12:12 ص
 */

class PartPicture
{

    private $id;
    private $part_id;
    private $url;

    /**
     * PartPicture constructor.
     * @param $id
     * @param $part_id
     * @param $url
     */
    public function __construct($part_id, $url, $id = -1)
    {
        $this->id = $id;
        $this->part_id = $part_id;
        $this->url = $url;
    }


}