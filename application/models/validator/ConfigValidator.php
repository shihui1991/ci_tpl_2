<?php
/**
 *  Config 验证模型
 * @user 罗仕辉
 * @create 2018-09-15
 */

namespace models\validator;

class ConfigValidator extends ValidatorModel
{

    /** 修改 验证字段
     * @return array
     */
    public function valiEditFields()
    {
        return array(
            'Id',
            'Name',
            'Infos',
        );
    }
}