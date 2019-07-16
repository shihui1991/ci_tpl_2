<?php
namespace app\helpers;


class DomHelper
{

    /**
     * 获取普通表单字段
     *
     * @param $model
     * @param $field
     * @param string $method
     * @param null $value
     * @param array $params
     * @param array $others
     * @return mixed
     */
    static public function getFormField($model, $field, $method = 'formInput', $value = null, $params = [], $others = [])
    {
        $label = $model->getFieldLabel($field);
        $value = isset($model->$field) ? $model->$field : $value;
        $valsDesc = $model->getValueDesc($field);

        switch ($method){
            case 'formRadio':
            case 'formCheckbox':
                return AceHelper::$method($label, $field, $valsDesc, $value, $params, $others);
                break;
            case 'formSimpleSelect':
                $valsDesc = $params['valsDesc'];
                unset($params['valsDesc']);
                return AceHelper::$method($label, $field, $valsDesc, $value, $params, $others);
                break;
            case 'formSelect':
                $options = $params['options'];
                unset($params['options']);
                return AceHelper::$method($label, $field, $options, $params, $others);
                break;
            case 'formDate':
            case 'formTextarea':
                return AceHelper::$method($label, $field, $value, $params, $others);
                break;
            default:
                $type = isset($params['type']) ? $params['type'] : 'text';
                unset($params['type']);
                return AceHelper::$method($label, $field, $value, $type, $params, $others);

        }
    }
}