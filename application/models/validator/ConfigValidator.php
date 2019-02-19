<?php
/**
 *  Config 验证模型
 * @author 罗仕辉
 * @create 2018-09-15
 */

namespace models\validator;

class ConfigValidator extends ValidatorModel
{

    /** 添加 验证字段
     * @return array
     */
    public function valiAddFields()
    {
        return array(
            'Table',
            'Name',
            'DBConf',
            'MainDB',
            'BackDB',
            'Single',
            'Columns',
            'Infos',
            'State',
        );
    }

    /** 修改 验证字段
     * @return array
     */
    public function valiEditFields()
    {
        return array(
            'Id',
            'Name',
            'DBConf',
            'MainDB',
            'BackDB',
            'Single',
            'Columns',
            'Infos',
            'State',
        );
    }

    /**
     * 字段详情 验证
     */
    public function valiColumnsRules()
    {
        $this->validator->set_rules('Columns[]', '字段详情', 'trim|required');
    }

    /**
     * 数据库配置 验证
     */
    public function valiDBConfRules()
    {
        $this->validator->set_rules('DBConf[]', '数据库配置', 'trim|required');
    }
}