<?php
/**
 *  Menu 数据模型
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\data;

class MenuData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '菜单ID',
            'alias' => 'MenuId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '菜单ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'ParentId' => array(
            'field' => 'ParentId',
            'name'  => '上级菜单',
            'alias' => 'MenuPid',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Url' => array(
            'field' => 'Url',
            'name'  => '路由地址',
            'alias' => 'MenuUrl',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 路由地址'",
            'rules' => 'trim|required|max_length[255]|regex_match[/^\/\S*$/]',
        ),
        'UrlAlias' => array(
            'field' => 'UrlAlias',
            'name'  => '路由别名',
            'alias' => 'UrlAlias',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT ' 路由别名'",
            'rules' => 'trim|max_length[255]|alpha_dash',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'MenuName',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 菜单名称'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Icon' => array(
            'field' => 'Icon',
            'name'  => '菜单图标',
            'alias' => 'MenuIcon',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT ' 菜单图标'",
            'rules' => 'trim|max_length[255]',
        ),
        'Ctrl' => array(
            'field' => 'Ctrl',
            'name'  => '限制',
            'alias' => 'MenuCtrl',
            'attr'  => 'int',
            'desc'  => "tinyint(1) NOT NULL DEFAULT '1' COMMENT ' 是否限制， 0否，1是'",
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'Display' => array(
            'field' => 'Display',
            'name'  => '显示',
            'alias' => 'MenuDisplay',
            'attr'  => 'int',
            'desc'  => "tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否显示，0隐藏，1显示'",
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'State' => array(
            'field' => 'State',
            'name'  => '状态',
            'alias' => 'MenuState',
            'attr'  => 'int',
            'desc'  => "tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否开启，0禁用，1开启'",
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'Sort' => array(
            'field' => 'Sort',
            'name'  => '排序',
            'alias' => 'MenuSort',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '功能说明',
            'alias' => 'MenuInfos',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT '功能说明'",
            'rules' => 'trim|max_length[255]',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'ApiCreated',
            'attr'  => 'datetime',
            'desc'  => "datetime DEFAULT NULL COMMENT '创建时间'",
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'ApiUpdated',
            'attr'  => 'datetime',
            'desc'  => "datetime DEFAULT NULL COMMENT '更新时间'",
            'rules' => 'trim|max_length[255]',
        ),
    );

    /** 添加 批量赋值字段
     * @return array
     */
    public function fillAddFields()
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
            'Created',
        );
    }

    /** 修改 批量赋值字段
     * @return array
     */
    public function fillEditFields()
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
            'Updated',
        );
    }
}