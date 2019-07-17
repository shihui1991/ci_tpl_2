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
        if(empty($list)){
            return '';
        }
        $group = ArrHelper::getChildGroup($list, $parentId, $pKey);
        if(empty($group['children'])){
            return '';
        }
        $li = '';
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
     * 生成表单字段输入域
     *
     * @param $label
     * @param $content
     * @param array $params
     * @return string
     */
    static public function formFieldDom($label, $content, array $params)
    {
        $groupClass = isset($params['groupClass']) ? $params['groupClass'] : 'form-group';
        $errorClass = isset($params['error']) ? 'has-error' : '';
        $error = isset($params['error']) ? $params['error'] : '';
        $divClass = isset($params['divClass']) ? $params['divClass'] : 'col-sm-9';
        $labelClass = isset($params['labelClass']) ? $params['labelClass'] : 'col-sm-3 control-label no-padding-right';

        return <<<dom
<div class="{$groupClass} {$errorClass}">
	<label class="{$labelClass}" for="{$params['id']}"> {$label} </label>
	<div class="{$divClass}">
		{$content}
		<div class="help-block col-xs-12 col-sm-reset inline">{$error}</div>
	</div>
</div>
dom;

    }

    /**
     * 生成表单输入框
     *
     * @param $label
     * @param $field
     * @param null $value
     * @param string $type
     * @param array $params
     * @param array $others
     * @return string
     */
    static public function formInput($label, $field, $value = null, $type = 'text', $params = [], $others = [])
    {
        # 基本参数
        $params['type'] = $type;
        $params['value'] = htmlspecialchars($value);
        $args = [
            'id' => 'field-'.$field,
            'name' => $field,
            'class' => 'col-xs-10 col-sm-5',
        ];
        $params = array_merge($args, $params);
        # 输入框
        $input = '<input ';
        foreach($params as $attr => $val){
            $input .= $attr . '="'. $val .'" ';
        }
        $input .= ' >';
        $others['id'] = $params['id'];

        return static::formFieldDom($label, $input, $others);
    }

    /**
     * 生成表单选项
     *
     * @param $type
     * @param $label
     * @param $name
     * @param array $valsDesc
     * @param null $value
     * @param array $others
     * @return string
     */
    static public function formCheckboxOrRadio($type, $label, $name, array $valsDesc, $value = null, $others = [])
    {
        $inputs = '';
        foreach($valsDesc as $val => $title){
            $checked = '';
            if( !is_null($value)
                && (('radio' == $type && $val == $value)
                    || ('checkbox' == $type && in_array($val, $value)))
            ){
                $checked = 'checked';
            }
            $inputs .= <<<inputs
<label>
	<input name="{$name}" value="{$val}" {$checked} type="{$type}" class="ace">
	<span class="lbl"> {$title} </span>
</label>
inputs;

        }
        $others['id'] = '';
        $others['divClass'] = isset($others['divClass']) ? $others['divClass'] : "col-sm-9 {$type}";

        return static::formFieldDom($label, $inputs, $others);
    }

    /**
     * 生成表单单选项
     *
     * @param $label
     * @param $field
     * @param array $valsDesc
     * @param null $value
     * @param array $params
     * @param array $others
     * @return string
     */
    static public function formRadio($label, $field, array $valsDesc, $value = null, $params = [], $others = [])
    {
        $name = isset($params['name']) ? $params['name'] : $field;

        return static::formCheckboxOrRadio('radio', $label, $name, $valsDesc, $value, $others);
    }

    /**
     * 生成表单复选项
     *
     * @param $label
     * @param $field
     * @param array $valsDesc
     * @param null $values
     * @param array $params
     * @param array $others
     * @return string
     */
    static public function formCheckbox($label, $field, array $valsDesc, $values = null, $params = [], $others = [])
    {
        $name = isset($params['name']) ? $params['name'] : $field.'[]';
        if(is_string($values)){
            $values = json_decode($values,true);
        }
        $values = $values ? $values : [];

        return static::formCheckboxOrRadio('checkbox', $label, $name, $valsDesc, $values, $others);

    }

    /**
     * 生成普通表单日期输入框
     *
     * @param $model
     * @param $field
     * @param string $type
     * @param array $params
     * @param array $others
     * @return string
     */
    static public function formDate($label, $field, $value = null, $params = [], $others = [])
    {
        $args = [
            'class' => 'col-xs-10 col-sm-5 date-picker',
            'data-date-format' => 'yyyy-mm-dd',
        ];
        $params = array_merge($args, $params);

        return static::formInput($label, $field, $value, 'text', $params, $others);
    }

    /**
     * 生成普通表单文本域
     *
     * @param $label
     * @param $field
     * @param null $value
     * @param array $params
     * @param array $others
     * @return string
     */
    static public function formTextarea($label, $field, $value = null, $params = [], $others = [])
    {
        # 基本参数
        $args = [
            'id'    => 'field-'.$field,
            'name'  => $field,
            'class' => 'col-xs-10 col-sm-5',
        ];
        $params = array_merge($args, $params);
        # 文本域
        $textarea = '<textarea ';
        foreach($params as $attr => $val){
            $textarea .= ' '.$attr.'="'.$val.'" ';
        }
        $textarea .= '>' . htmlspecialchars($value) . '</textarea>';
        $others['id'] = $params['id'];

        return static::formFieldDom($label, $textarea, $others);
    }

    /**
     * 生成普通表单选项框
     *
     * @param $label
     * @param $field
     * @param $options
     * @param array $params
     * @param array $others
     * @return string
     */
    static public function formSelect($label, $field, $options, $params = [], $others = [])
    {
        # 基本参数
        $args = [
            'id'    => 'field-'.$field,
            'name'  => $field,
            'class' => 'col-xs-10 col-sm-5',
        ];
        $params = array_merge($args, $params);
        # 选择框
        $select = '<select ';
        foreach($params as $attr => $val){
            $select .= $attr . '="'. $val .'" ';
        }
        $select .= ' >' . $options . '</select>';
        $others['id'] = $params['id'];

        return static::formFieldDom($label, $select, $others);
    }

    /**
     * 生成普通表单简单选项框
     *
     * @param $label
     * @param $field
     * @param $valsDesc
     * @param null $value
     * @param array $params
     * @param array $others
     * @return string
     */
    static public function formSimpleSelect($label, $field, $valsDesc, $value = null, $params = [], $others = [])
    {
        if(is_string($value)){
            $value = json_decode($value,true);
        }
        $value = $value ? $value : [];
        # 选项
        $options = '';
        foreach($valsDesc as $val => $title){
            $selected = in_array($val, $value) ? 'selected' : '';
            $options .= '<option value="'.$val.'" '.$selected.'>'.$title.'</option>';
        }

        return static::formSelect($label, $field, $options, $params, $others);
    }
}