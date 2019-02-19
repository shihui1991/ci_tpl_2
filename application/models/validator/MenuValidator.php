<?php
/**
 *  Menu 验证模型
 * @author 罗仕辉
 * @create 2018-09-08
 */

namespace models\validator;

class MenuValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'ParentId',
            'Url',
            'UrlAlias',
            'Name',
            'Icon',
            'Ctrl',
            'Display',
            'State',
            'Sort',
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
            'Url',
            'UrlAlias',
            'Name',
            'Icon',
            'Ctrl',
            'Display',
            'State',
            'Sort',
            'Infos',
        );
    }
}