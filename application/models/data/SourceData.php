<?php
/**
 *  Source 数据模型
 * @user 罗仕辉
 * @create 2018-09-12
 */

namespace models\data;

class SourceData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,desc,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '资源ID',
            'alias' => 'SourceId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '资源ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'SourceName',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 名称'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Url' => array(
            'field' => 'Url',
            'name'  => '资源地址',
            'alias' => 'SourceUrl',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 资源地址'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Cloud' => array(
            'field' => 'Cloud',
            'name'  => '云地址',
            'alias' => 'CloudUrl',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT '云存储地址'",
            'rules' => 'trim|max_length[255]',
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
            'alias' => 'SourceCreated',
            'attr'  => 'datetime',
            'desc'  => "varchar(20) DEFAULT NULL COMMENT '创建时间'",
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'SourceUpdated',
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
            'Name',
            'Url',
            'Cloud',
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
            'Name',
            'Url',
            'Cloud',
            'Infos',
            'Updated',
        );
    }
}