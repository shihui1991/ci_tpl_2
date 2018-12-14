<?php
/**
 *  按字段属性 处理数据
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\data\component;

trait SetAttr
{
    /**
     * @param null $value
     * @return int
     */
    public function setIntAttr($value=null)
    {
        return (int)$value;
    }

    /**
     * @param null $value
     * @return float
     */
    public function setFloatAttr($value=null)
    {
        return (float)$value;
    }

    /**
     * @param null $value
     * @return double
     */
    public function setDoubleAttr($value=null)
    {
        return (double)$value;
    }

    /**
     * @param null $value
     * @return string
     */
    public function setStringAttr($value=null)
    {
        return (string)trim($value);
    }

    /**
     * @param null $value
     * @return false|mixed|null|string
     */
    public function setArrayAttr($value=null)
    {
        if(empty($value)){
            return '';
        }
        if(is_object($value)){
            $value=json_encode($value);
        }
        if(is_string($value)){
            $value=json_decode($value,true);
        }
        if(is_array($value)){
            $value=formatArray($value);
            $value=json_encode(array_values($value));
        }else{
            $value='';
        }

        return $value;
    }

    /**
     * @param null $value
     * @return false|mixed|null|string
     */
    public function setJsonAttr($value=null)
    {
        if(empty($value)){
            return '';
        }
        if(is_object($value)){
            $value=json_encode($value);
        }
        if(is_string($value)){
            $value=json_decode($value,true);
        }
        if(is_array($value)){
            $value=formatArray($value);
            $value=json_encode($value);
        }else{
            $value='';
        }
        return $value;
    }

    /**
     * @param null $value
     * @return false|int|null|string
     */
    public function setDateAttr($value=null)
    {
        if(empty($value)){
            return date('Y-m-d');
        }
        if(is_numeric($value)){
            $value=(int)$value;
        }else{
            $value=strtotime($value);
        }
        $value = date('Y-m-d',$value);

        return $value;
    }

    /**
     * @param null $value
     * @return false|int|null|string
     */
    public function setDatetimeAttr($value=null)
    {
        if(empty($value)){
            return date('Y-m-d H:i:s');
        }
        if(is_numeric($value)){
            $value=(int)$value;
        }else{
            $value=strtotime($value);
        }
        $value = date('Y-m-d H:i:s',$value);

        return $value;
    }
}