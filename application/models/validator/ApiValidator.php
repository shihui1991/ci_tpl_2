<?php
/**
 *  Api 验证模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\validator;

class ApiValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'Name',
            'Url',
            'EventId',
            'Request',
            'Response',
            'Example',
            'State',
            'Infos',
        );
    }

    /** 修改 验证字段
     * @return array
     */
    public function valiEditFields()
    {
        return array(
            'Id',
            'Name',
            'Url',
            'EventId',
            'Request',
            'Response',
            'Example',
            'State',
            'Infos',
        );
    }
}