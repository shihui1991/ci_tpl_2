<?php


namespace app\helpers;


class StrHelper
{
    /**
     * 生成时间唯一字符串（32位）
     *
     * @param bool $upper
     * @return string
     */
    static public function makeUniqid($upper = false)
    {
        $str = md5(uniqid(mt_rand(), true));
        if($upper){
            $str = strtoupper($str);
        }

        return $str;
    }

    /**
     * 生成GUID
     *
     * @return string
     */
    static public function makeGuid()
    {
        $charid =  static::makeUniqid(true);
        $hyphen = '-';
        $guid = substr($charid, 6, 2).substr($charid, 4, 2). substr($charid, 2, 2).substr($charid, 0, 2).$hyphen
            .substr($charid, 10, 2).substr($charid, 8, 2).$hyphen
            .substr($charid,14, 2).substr($charid,12, 2).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);

        return $guid;
    }
}