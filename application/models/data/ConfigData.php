<?php
/**
 *  Config 数据模型
 * @user 罗仕辉
 * @create 2018-09-15
 */

namespace models\data;

class ConfigData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '配置ID',
            'alias' => 'ConfigId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '配置ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Table' => array(
            'field' => 'Table',
            'name'  => '表名',
            'alias' => 'ConfigTable',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 表名'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'ConfigName',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT ' 名称'",
            'rules' => 'trim|max_length[255]',
        ),
        'Columns' => array(
            'field' => 'Columns',
            'name'  => '字段详情',
            'alias' => 'ConfigColumns',
            'attr'  => 'json',
            'desc'  => "text COMMENT '字段详情'",
            'rules' => '',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '说明',
            'alias' => 'RoleInfos',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT '说明'",
            'rules' => 'trim|max_length[255]',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'ConfigCreated',
            'attr'  => 'datetime',
            'desc'  => "varchar(20) DEFAULT NULL COMMENT '创建时间'",
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'ConfigUpdated',
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
            'Table',
            'Name',
            'Columns',
            'Infos',
            'Created',
            'Updated',
        );
    }

    /** 批量更新 批量赋值字段
     * @return array
     */
    public function fillUpdateFields()
    {
        return array(
            'Columns',
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
            'Name',
            'Columns',
            'Infos',
            'Updated',
        );
    }
}