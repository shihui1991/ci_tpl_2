<?php
/**
 *  Master 数据模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\data;

class MasterData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '管理员ID',
            'alias' => 'MasterId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '管理员ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Realname' => array(
            'field' => 'Realname',
            'name'  => '真实姓名',
            'alias' => 'Realname',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '真实姓名'",
            'rules' => 'trim|required|min_length[2]|max_length[255]',
        ),
        'RoleId' => array(
            'field' => 'RoleId',
            'name'  => '角色ID',
            'alias' => 'RoleId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL COMMENT '角色ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Account' => array(
            'field' => 'Account',
            'name'  => '登录账号',
            'alias' => 'Account',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '账号'",
            'rules' => 'trim|required|min_length[3]|max_length[255]|alpha_dash',
        ),
        'Password' => array(
            'field' => 'Password',
            'name'  => '登录密码',
            'alias' => 'Password',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '账号'",
            'rules' => 'trim|required|min_length[6]|max_length[255]',
        ),
        'Token' => array(
            'field' => 'Token',
            'name'  => '登录令牌',
            'alias' => 'Token',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT '登录令牌'",
            'rules' => 'trim|required|min_length[32]|max_length[255]|alpha_dash',
        ),
        'State' => array(
            'field' => 'State',
            'name'  => '状态',
            'alias' => 'MasterState',
            'attr'  => 'int',
            'desc'  => "tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0禁用，1启用'",
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'MasterCreated',
            'attr'  => 'datetime',
            'desc'  => "varchar(20) DEFAULT NULL COMMENT '创建时间'",
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'MasterUpdated',
            'attr'  => 'datetime',
            'desc'  => "varchar(20) DEFAULT NULL COMMENT '更新时间'",
            'rules' => 'trim|max_length[255]',
        ),
    );

    /** 添加 批量赋值字段
     * @return array
     */
    public function fillAddFields()
    {
        return array(
            'Realname',
            'RoleId',
            'Account',
            'Password',
            'Token',
            'State',
            'Created',
            'Updated',
        );
    }

    /** 修改 批量赋值字段
     * @return array
     */
    public function fillEditFields()
    {
        return array(
            'Id',
            'Realname',
            'RoleId',
            'Account',
            'State',
            'Updated',
        );
    }

    /** 修改资料 批量赋值字段
     * @return array
     */
    public function fillModifyFields()
    {
        return array(
            'Realname',
            'Account',
            'Updated',
        );
    }

    /** 修改密码 批量赋值字段
     * @return array
     */
    public function fillEditPasswdFields()
    {
        return array(
            'Password',
            'Updated',
        );
    }

    /** 登录 批量赋值字段
     * @return array
     */
    public function fillLoginFields()
    {
        return array(
            'Token',
            'Updated',
        );
    }
}