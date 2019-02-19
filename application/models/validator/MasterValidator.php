<?php
/**
 *  Master 验证模型
 * @author 罗仕辉
 * @create 2018-09-09
 */

namespace models\validator;

class MasterValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'Realname',
            'RoleId',
            'Account',
            'Password',
            'State',
        );
    }

    /** 修改 验证字段
     * @return array
     */
    public function valiEditFields()
    {
        return array(
            'Id',
            'Realname',
            'RoleId',
            'Account',
            'State',
        );
    }

    /** 修改资料 验证字段
     * @return array
     */
    public function valiModifyFields()
    {
        return array(
            'Realname',
            'Account',
        );
    }

    /** 修改密码 验证字段
     * @return array
     */
    public function valiEditPasswdFields()
    {
        return array(
            'Password',
        );
    }

    /** 登录 验证字段
     * @return array
     */
    public function valiLoginFields()
    {
        return array(
            'Account',
            'Password',
        );
    }

    /** 已登录 验证字段
     * @return array
     */
    public function valiOnlineFields()
    {
        return array(
            'Id',
            'Token',
        );
    }

    /** 重置密码 验证字段
     * @return array
     */
    public function valiUnsetPasswdFields()
    {
        return array(
            'Id',
            'Password',
        );
    }
}