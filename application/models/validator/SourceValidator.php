<?php
/**
 *  Source 验证模型
 * @user 罗仕辉
 * @create 2018-09-12
 */

namespace models\validator;

class SourceValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'Name',
            'Url',
            'Cloud',
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
            'Cloud',
            'Infos',
        );
    }
}