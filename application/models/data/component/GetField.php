<?php
/**
 *  按字段 格式化数据
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\data\component;

trait GetField
{
    /** 格式化 Instance
     * @param $value
     * @param array $data
     * @return string
     */
    public function getInstanceField($value,$data=array())
    {
        if(!empty($value)){
            $value=str_replace('/','\\',$value);
        }

        return $value;
    }
}