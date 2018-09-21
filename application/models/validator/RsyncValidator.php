<?php
/**
 *  Rsync 验证模型
 * @user 罗仕辉
 * @create 2018-09-17
 */

namespace models\validator;

class RsyncValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'Name',
            'Instance',
            'Method',
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
            'Instance',
            'Method',
            'Infos',
        );
    }
}