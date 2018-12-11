<?php
/**
 *  Cate 数据模型
 * @user 罗仕辉
 * @create 2018-09-11
 */

namespace models\data;

class CateData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,desc,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '分类ID',
            'alias' => 'CateId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '分类ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Group' => array(
            'field' => 'Group',
            'name'  => '分组',
            'alias' => 'CateGroup',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '分组'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Value' => array(
            'field' => 'Value',
            'name'  => '分类值',
            'alias' => 'CateValue',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '值'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'CateName',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '名称'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Constant' => array(
            'field' => 'Constant',
            'name'  => '常量名',
            'alias' => 'Constant',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '常量名'",
            'rules' => 'trim|max_length[255]',
        ),
        'Sort' => array(
            'field' => 'Sort',
            'name'  => '排序',
            'alias' => 'CateSort',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Display' => array(
            'field' => 'Display',
            'name'  => '是否显示',
            'alias' => 'CateDisplay',
            'attr'  => 'int',
            'desc'  => "tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示，0隐藏，1显示'",
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '功能说明',
            'alias' => 'CateInfos',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '描述'",
            'rules' => 'trim|max_length[255]',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'CateCreated',
            'attr'  => 'datetime',
            'desc'  => "varchar(20) DEFAULT NULL COMMENT '创建时间'",
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'CateUpdated',
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
            'Group',
            'Value',
            'Name',
            'Constant',
            'Sort',
            'Display',
            'Infos',
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
            'Group',
            'Value',
            'Name',
            'Constant',
            'Sort',
            'Display',
            'Infos',
            'Updated',
        );
    }
}