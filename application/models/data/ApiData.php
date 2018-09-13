<?php
/**
 *  Api 数据模型
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\data;

class ApiData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '接口ID',
            'alias' => 'ApiId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '接口ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'ApiName',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '名称'",
            'rules' => 'trim|required|max_length[255]',
        ),
        'Url' => array(
            'field' => 'Url',
            'name'  => '接口地址',
            'alias' => 'ApiUrl',
            'attr'  => 'string',
            'desc'  => "varchar(255) NOT NULL COMMENT '接口URL'",
            'rules' => 'trim|required|max_length[255]|regex_match[/^\/\S*$/]',
        ),
        'EventId' => array(
            'field' => 'EventId',
            'name'  => '事件ID',
            'alias' => 'EventId',
            'attr'  => 'int',
            'desc'  => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '事件ID'",
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Request' => array(
            'field' => 'Request',
            'name'  => '请求参数',
            'alias' => 'Request',
            'attr'  => 'array',
            'desc'  => "text NOT NULL COMMENT '请求参数'",
            'rules' => '',
        ),
        'Response' => array(
            'field' => 'Response',
            'name'  => '响应参数',
            'alias' => 'Response',
            'attr'  => 'array',
            'desc'  => "text NOT NULL COMMENT '响应参数'",
            'rules' => '',
        ),
        'State' => array(
            'field' => 'State',
            'name'  => '状态',
            'alias' => 'ApiState',
            'attr'  => 'int',
            'desc'  => "tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0禁用，1启用'",
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '说明',
            'alias' => 'ApiInfos',
            'attr'  => 'string',
            'desc'  => "text COMMENT '说明'",
            'rules' => '',
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
            'Name',
            'Url',
            'EventId',
            'Request',
            'Response',
            'Example',
            'State',
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
            'Name',
            'Url',
            'EventId',
            'Request',
            'Response',
            'Example',
            'State',
            'Infos',
            'Updated',
        );
    }
}