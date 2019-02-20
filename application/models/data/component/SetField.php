<?php
/**
 *  按字段属性 处理数据
 * @author 罗仕辉
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
    public function setPasswordField($value,$data=array())
    {
        if(is_null($value) || 0 == strlen($value)){
            return $value;
        }
        # 防止重复hash
        $info = password_get_info($value);
        if(0 == $info['algo'] || 'unknown' == $info['algoName']){
            $value = password_hash($value,PASSWORD_DEFAULT);
        }
        return $value;
    }

    /** 处理 Token
     * @param $value
     * @param array $data
     * @return string
     */
    public function setTokenField($value,$data=array())
    {
        if(is_null($value)){
            $value=makeUniqueStr();
        }

        return $value;
    }

    /** 处理 Guid
     * @param $value
     * @param array $data
     * @return string
     */
    public function setGuidField($value,$data=array())
    {
        if(is_null($value)){
            $value=createGuid();
        }

        return $value;
    }

    /** 处理 Columns
     * @param string $value
     * @param array $data
     * @return array|false|mixed|string
     */
    public function setColumnsField($value, $data=array())
    {
        if(is_string($value)){
            $value=json_decode($value,true);
        }
        if(empty($value)){
            return '';
        }

        $result=array();
        foreach($value as $key=>$val){
            $result[$val['field']]=$val;
        }
        $result=json_encode($result);

        return $result;
    }
}