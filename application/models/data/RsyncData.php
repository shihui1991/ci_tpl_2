<?php
/**
 *  Rsync 数据模型
 * @user 罗仕辉
 * @create 2018-09-17
 */

namespace models\data;

class RsyncData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,desc,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '同步ID',
            'alias' => 'RsyncId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '同步ID' ",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'RsyncName',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 名称' ",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Instance' => array(
            'field' => 'Instance',
            'name'  => '实例',
            'alias' => 'RsyncInstance',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 实例' ",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Method' => array(
            'field' => 'Method',
            'name'  => '操作方法',
            'alias' => 'RsyncMethod',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT ' 操作方法' ",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '说明',
            'alias' => 'RoleInfos',
            'attr'  => 'string',
            'desc'  => "varchar(255) DEFAULT NULL COMMENT '说明' ",
            'rules' => 'trim|max_length[255]',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'RsyncCreated',
            'attr'  => 'datetime',
            'desc'  => "varchar(20) DEFAULT NULL COMMENT '创建时间' ",
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'RsyncUpdated',
            'attr'  => 'datetime',
            'desc'  => "varchar(20) DEFAULT NULL COMMENT '更新时间' ",
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
            'Instance',
            'Method',
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
            'Instance',
            'Method',
            'Infos',
            'Updated',
        );
    }
}