<?php
/**
 *  按字段属性 处理数据
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\data\component;

trait SetField
{

    /** 处理 Password
     * @param $value
     * @param array $data
     * @return bool|mixed|string
     */
    public function SetPasswordField($value,$data=array())
    {
        $value=password_hash($value,PASSWORD_DEFAULT);

        return $value;
    }

    /** 处理 Token
     * @param $value
     * @param array $data
     * @return string
     */
    public function SetTokenField($value,$data=array())
    {
        if(empty($value)){
            $value=createGuid();
        }

        return $value;
    }
}