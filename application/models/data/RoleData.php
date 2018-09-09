<?php
/**
 *  Role 数据模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\data;

class RoleData extends DataModel
{
    // 字段详情 field => [field,name,alias,attr,rules]
    public $columns=array(
        'Id' => array(
            'field' => 'Id',
            'name'  => '角色ID',
            'alias' => 'RoleId',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'ParentId' => array(
            'field' => 'ParentId',
            'name'  => '上级角色',
            'alias' => 'RolePid',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[10]|is_natural',
        ),
        'Name' => array(
            'field' => 'Name',
            'name'  => '名称',
            'alias' => 'RoleName',
            'attr'  => 'string',
            'rules' => 'trim|required|max_length[255]',
        ),
        'Admin' => array(
            'field' => 'Admin',
            'name'  => '是否超管',
            'alias' => 'RoleAdmin',
            'attr'  => 'int',
            'rules' => 'trim|required|max_length[1]|is_natural|in_list[0,1]',
        ),
        'MenuIds' => array(
            'field' => 'MenuIds',
            'name'  => '授权菜单',
            'alias' => 'RoleMenus',
            'attr'  => 'array',
            'rules' => '',
        ),
        'Infos' => array(
            'field' => 'Infos',
            'name'  => '功能说明',
            'alias' => 'RoleInfos',
            'attr'  => 'string',
            'rules' => 'trim|max_length[255]',
        ),
        'Created' => array(
            'field' => 'Created',
            'name'  => '创建时间',
            'alias' => 'RoleCreated',
            'attr'  => 'datetime',
            'rules' => 'trim|max_length[255]',
        ),
        'Updated' => array(
            'field' => 'Updated',
            'name'  => '更新时间',
            'alias' => 'RoleUpdated',
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
            'ParentId',
            'Name',
            'Admin',
            'MenuIds',
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
            'Admin',
            'MenuIds',
            'Infos',
            'Updated',
        );
    }

    /** 修改 MenuIds
     * @param mixed $value
     * @param array $data
     * @return array|string
     */
    public function setMenuIdsField($value, $data=array())
    {
        if(ADMIN_YES == $data['Admin']){
            $value='';
        }

        return $value;
    }

    /** 调整 MenuIds
     * @param array $value
     * @param array $data
     * @return array
     */
    public function getMenuIdsField($value=array(), $data=array())
    {
        if(ADMIN_YES == $data['Admin']){
            $value=array();
        }

        return $value;
    }
}