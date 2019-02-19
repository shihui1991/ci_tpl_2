<?php
/**
 *  Cate 验证模型
 * @author 罗仕辉
 * @create 2018-09-11
 */

namespace models\validator;

class CateValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'Group',
            'Value',
            'Name',
            'Constant',
            'Sort',
            'Display',
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
            'Group',
            'Value',
            'Name',
            'Constant',
            'Sort',
            'Display',
            'Infos',
        );
    }
}