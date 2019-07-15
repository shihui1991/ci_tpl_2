<?php
namespace app\helpers;

class AceHelper
{

    /**
     * 生成导航菜单栏
     *
     * @param array $list
     * @param int $parentId
     * @param int $level
     * @param int $currentId
     * @param array $parentsIds
     * @param string $idKey
     * @param string $pKey
     * @return string
     */
    static public function makeNav(array $list, $parentId = 0 , $level = 1, $currentId = 0, $parentsIds = [], $idKey = 'id', $pKey = 'parent_id')
    {
        $li = '';
        if(empty($list)){
            return '';
        }
        $group = ArrHelper::getChildGroup($list, $parentId, $pKey);
        if(empty($group['children'])){
            return '';
        }
        foreach($group['children'] as $row){
            $name = (1 == $level) ? '<span class="menu-text">'. $row['name'] .'</span>' : $row['name'];
            $icon = (2 == $level) ? '<i class="menu-icon fa fa-caret-right"></i>' : $row['icon']; # 第二级菜单图标改为箭头
            $liClass = $row[$idKey] == $currentId ? 'active' : '';
            if(in_array($row[$idKey], $parentsIds)){
                $liClass = 'active open';
            }
            $children = static::makeNav($group['others'], $row[$idKey], $level + 1, $currentId, $parentsIds, $idKey, $pKey);
            $linkClass = $arrow = '';
            if( $children){
                $linkClass = 'dropdown-toggle';
                $arrow = '<b class="arrow fa fa-angle-down"></b>';
            }

            $li .= '<li class="'.$liClass.'"><a href="'.$row['url'].'" class="'.$linkClass.'">'.$icon.$name.$arrow.'</a><b class="arrow"></b>'.$children.'</li>';
        }

        nav:
        return '<ul class="'.(1 == $level ? 'nav nav-list' : 'submenu').'">'.$li.'</ul>';
    }

    /**
     * 生成普通表单提交按钮
     *
     * @return string
     */
    static public function formSubmitBtn()
    {
        return <<<html
<div class="clearfix form-actions">
    <div class="col-md-offset-3 col-md-9">
        <button class="btn btn-info" type="submit"><i class="ace-icon fa fa-check bigger-110"></i>保存</button>
        <button class="btn" type="reset"><i class="ace-icon fa fa-undo bigger-110"></i>重填</button>
    </div>
</div>
html;
    }

    /**
     * 生成表单输入框
     *
     * @param $model
     * @param $field
     * @param string $default
     * @param string $type
     * @param array $params
     * @return string
     */
    static public function formInput($model, $field, $default = null, $type = 'text', $params = [])
    {
        $label = $model->getFieldLabel($field);
        $params['type'] = $type;
        $params['value'] = old($field, isset($model->$field) ? $model->$field : $default);
        $args = [
            'id' => 'field-'.$field,
            'name' => $field,
            'class' => 'col-xs-10 col-sm-5',
        ];
        $params = array_merge($args, $params);

        $input = '<input ';
        foreach($params as $attr => $val){
            $input .= $attr . '="'. $val .'" ';
        }
        $input .= ' >';

        return <<<dom
<div class="form-group">
	<label class="col-sm-3 control-label no-padding-right" for="{$params['id']}"> {$label} </label>
	<div class="col-sm-9">
		{$input}
	</div>
</div>
dom;

    }

    /**
     * 生成表单单选项
     *
     * @param $model
     * @param $field
     * @param string $default
     * @param array $params
     * @return string
     */
    static public function formRadio($model, $field, $default = null, $params = [])
    {
        $label = $model->getFieldLabel($field);
        $descArr = $model->getValueDesc($field);
        $name = isset($params['name']) ? $params['name'] : $field;
        $value = old($field, isset($model->$field) ? $model->$field : $default);

        $radios = '';
        foreach($descArr as $val => $title){
            $checked = $val == $value ? ' checked ' : '';
            $radios .= <<<radio
<label>
	<input name="{$name}" value="{$val}" {$checked} type="radio" class="ace">
	<span class="lbl"> {$title} </span>
</label>
radio;

        }

        return <<<dom
<div class="form-group">
	<label class="col-sm-3 control-label no-padding-right"> {$label} </label>
	<div class="col-sm-9 radio">
		{$radios}
	</div>
</div>
dom;

    }

    /**
     * 生成表单复选项
     *
     * @param $model
     * @param $field
     * @param string $default
     * @param array $params
     * @return string
     */
    static public function formCheckbox($model, $field, $default = null, $params = [])
    {
        $label = $model->getFieldLabel($field);
        $descArr = $model->getValueDesc($field);
        $name = isset($params['name']) ? $params['name'] : $field.'[]';
        $values = old($field, isset($model->$field) ? $model->$field : $default);
        if(is_string($values)){
            $values = json_decode($values,true);
        }
        $values = $values ? $values : [];

        $checkboxes = '';
        foreach($descArr as $val => $title){
            $checked = in_array($val,$values) ? ' checked ' : '';
            $checkboxes .= <<<checkbox
<label>
	<input name="{$name}" value="{$val}" {$checked} type="checkbox" class="ace">
	<span class="lbl"> {$title} </span>
</label>
checkbox;

        }

        return <<<dom
<div class="form-group">
	<label class="col-sm-3 control-label no-padding-right"> {$label} </label>
	<div class="col-sm-9 checkbox">
		{$checkboxes}
	</div>
</div>
dom;

    }
}