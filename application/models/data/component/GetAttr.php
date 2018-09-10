<?php
/**
 *  按字段属性 格式化数据
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\data\component;

trait GetAttr
{

    /**
     * @param null $value
     * @return int
     */
    public function getIntAttr($value=null)
    {
        return (int)$value;
    }

    /**
     * @param null $value
     * @return float
     */
    public function getFloatAttr($value=null)
    {
        return (float)$value;
    }

    /**
     * @param null $value
     * @return double
     */
    public function getDoubleAttr($value=null)
    {
        return (double)$value;
    }

    /**
     * @param null $value
     * @return string
     */
    public function getStringAttr($value=null)
    {
        return (string)trim($value);
    }

    /**
     * @param null $value
     * @return array|mixed|null
     */
    public function getArrayAttr($value=null)
    {
        if(!empty($value)){
            $value=json_decode($value,true);
        }
        if(empty($value)){
            return array();
        }
        $value = array_values(array_filter($value));
        $value=formatArray($value);

        return $value;
    }

    /**
     * @param null $value
     * @return array|mixed|null
     */
    public function getJsonAttr($value=null)
    {
        if(!empty($value)){
            $value=json_decode($value,true);
        }
        if(empty($value)){
            return array();
        }
        $value=formatArray($value);

        return $value;
    }

    /**
     * @param null $value
     * @return false|int|null
     */
    public function getDateAttr($value=null)
    {
        if(empty($value)){
            return 0;
        }
        if(is_string($value)){
            $value=strtotime($value);
        }else{
            $value=(int)$value;
        }
        return $value;
    }

    /**
     * @param null $value
     * @return false|int|null
     */
    public function getDatetimeAttr($value=null)
    {
        if(empty($value)){
            return 0;
        }
        if(is_string($value)){
            $value=strtotime($value);
        }else{
            $value=(int)$value;
        }
        return $value;
    }
}