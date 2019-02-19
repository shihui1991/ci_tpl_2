<?php
/**
 *  Role 验证模型
 * @author 罗仕辉
 * @create 2018-09-09
 */

namespace models\validator;

class RoleValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'ParentId',
            'Name',
            'Admin',
            'MenuIds',
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
            'Admin',
            'MenuIds',
            'Infos',
        );
    }
}