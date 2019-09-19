<?php

class AttrFormatter
{
    /**
     * @param $type
     * @param $attr
     * @param $val
     * @return mixed
     */
    public static function handle($type, $attr, $val)
    {
        $method = strtolower($type).'AttrFor'.ucfirst($attr);
        return method_exists(static::class,$method) ? static::$method($val) : $val;
    }
    
    /**
     * @param $val
     * @return int
     */
    public static function getAttrForInt($val)
    {
        return (int)$val;
    }

    /**
     * @param $val
     * @return float
     */
    public static function getAttrForFloat($val)
    {
        return (float)$val;
    }

    /**
     * @param $val
     * @return string
     */
    public static function getAttrForString($val)
    {
        return (string)$val;
    }

    /**
     * 转换为数组
     * 
     * @param $val
     * @return mixed
     */
    public static function transformToArray($val)
    {
        $temp = $val;
        if(is_string($val)){
            $temp = json_decode($val,true);
            if(is_null($temp)) return $val;
        }
        if(is_object($val)){
            $temp = json_decode(json_encode($val),true);
        }

        return $temp;
    }

    /**
     * @param $val
     * @return
     */
    public static function getAttrForArray($val)
    {
        $temp = static::transformToArray($val);
        if( ! is_array($temp)) return $val;
        $res = [];
        foreach($temp as $k => $v){
            if(is_array($v)){
                $v = static::getAttrForArray($v);
            }else{
                $v = is_numeric($v) ? (float)$v : trim((string)$v);
            }
            $res[] = $v;
        }

        return $res;
    }

    /**
     * @param $val
     * @return string
     */
    public static function setAttrForArray($val)
    {
        if(is_string($val)){
            $temp = json_decode($val,true);
            if(is_null($temp) || is_array($temp)) return $val;
        }
        if(is_object($val) || is_array($val)){
            return json_encode($val,JSON_OPTION);
        }
        return $val;
    }

    /**
     * @param $val
     * @return mixed
     */
    public static function getAttrForJson($val)
    {
        $temp = static::transformToArray($val);
        if( ! is_array($temp)) return $val;
        foreach($temp as $k => & $v){
            if(is_array($v)){
                $v = static::getAttrForArray($v);
            }else{
                $v = is_numeric($v) ? (float)$v : trim((string)$v);
            }
        }

        return $temp;
    }

    /**
     * @param $val
     * @return string
     */
    public static function setAttrForJson($val)
    {
        return static::setAttrForArray($val);
    }
}