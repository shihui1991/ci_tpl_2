<?php
/**
 *  Cate 数据模型
 * @user 罗仕辉
 * @create 2018-09-11
 */

namespace models\data;

class CateData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '分类ID',
            'alias' => 'CateId',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Group' => array(
            'field' => 'Group',
            'name'  => '分组',
            'alias' => 'CateGroup',
            'attr'  => 'string',
            'rules' => 'trim|required|max_length[255]',
        ),
        'Value' => array(
            'field' => 'Value',
            'name'  => '分类值',
            'alias' => 'CateValue',
            'attr'  => 'string',
            'rules' => 'trim|required|max_length[255]',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'CateName',
            'attr'  => 'string',
            'rules' => 'trim|required|max_length[255]',
        ),
        'Constant' => array(
            'field' => 'Constant',
            'name'  => '常量名',
            'alias' => 'Constant',
            'attr'  => 'string',
            'rules' => 'trim|max_length[255]',
        ),
        'Sort' => array(
            'field' => 'Sort',
            'name'  => '排序',
            'alias' => 'CateSort',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Display' => array(
            'field' => 'Display',
            'name'  => '是否显示',
            'alias' => 'CateDisplay',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '功能说明',
            'alias' => 'CateInfos',
            'attr'  => 'string',
            'rules' => 'trim|max_length[255]',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'CateCreated',
            'attr'  => 'datetime',
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'CateUpdated',
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
            'Group',
            'Value',
            'Name',
            'Constant',
            'Sort',
            'Display',
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