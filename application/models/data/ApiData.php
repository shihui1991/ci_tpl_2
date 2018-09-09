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
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'ApiName',
            'attr'  => 'string',
            'rules' => 'trim|required|max_length[255]',
        ),
        'Url' => array(
            'field' => 'Url',
            'name'  => '接口地址',
            'alias' => 'ApiUrl',
            'attr'  => 'string',
            'rules' => 'trim|required|max_length[255]|regex_match[/^\/\S*$/]',
        ),
        'EventId' => array(
            'field' => 'EventId',
            'name'  => '事件ID',
            'alias' => 'EventId',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Request' => array(
            'field' => 'Request',
            'name'  => '请求参数',
            'alias' => 'Request',
            'attr'  => 'array',
            'rules' => '',
        ),
        'Response' => array(
            'field' => 'Response',
            'name'  => '响应参数',
            'alias' => 'Response',
            'attr'  => 'array',
            'rules' => '',
        ),
        'State' => array(
            'field' => 'State',
            'name'  => '状态',
            'alias' => 'ApiState',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '说明',
            'alias' => 'ApiInfos',
            'attr'  => 'string',
            'rules' => '',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'ApiCreated',
            'attr'  => 'datetime',
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'ApiUpdated',
            'attr'  => 'datetime',
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