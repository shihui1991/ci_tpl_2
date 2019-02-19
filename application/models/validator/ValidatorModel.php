<?php
/**
 *  验证模型
 * @author 罗仕辉
 * @create 2018-09-08
 */

namespace models\validator;

abstract class ValidatorModel
{
    protected $CI;
    protected $validator;
    static protected $objs;


    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->library('form_validation');
        $this->validator = $this->CI->form_validation;
    }

    /**  获取实例
     * @param int $k
     * @return ValidatorModel
     */
    static public function instance($k=0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(empty(static::$objs[$k])){
            static::$objs[$k] = new static();
        }
        return static::$objs[$k];
    }

    /** 销毁实例
     * @param string $k
     */
    public function _unset($k = 0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(isset(static::$objs[$k])){
            unset(static::$objs[$k]);
        }
    }

    /**  设置验证规则
     * @param array $columns
     * @param string $method
     */
    public function setValiRules(array $columns, $method='')
    {
        // 获取验证字段
        if(empty($method)){
            $fields=array_column($columns,'field');
        }
        else{
            $method='vali'.ucfirst($method).'Fields';
            if(method_exists($this,$method)){
                $fields=$this->$method();
            }else{
                $fields=array_column($columns,'field');
            }
        }

        // 设置验证规则
        foreach($fields as $field){
            // 特定验证规则
            $func='vali'.ucfirst($field).'Rules';
            if(method_exists($this,$func)){
                $this->$func();
            }else{
                if(empty($columns[$field]['rules'])){
                    continue;
                }
                $this->validator->set_rules($field, $columns[$field]['name'], $columns[$field]['rules']);
            }
        }
    }

    /**
     *  设置验证提示
     */
    public function setCommonMsg()
    {
        $this->validator->set_message('required', '{field}：不能为空');
        $this->validator->set_message('matches', '{field}：必须和 {param} 相同');
        $this->validator->set_message('regex_match', '{field}：格式错误');
        $this->validator->set_message('differs', '{field}：不能和 {param} 相同');
        $this->validator->set_message('is_unique', '{field}：已存在');
        $this->validator->set_message('min_length', '{field}：最小长度不能小于 {param} 位');
        $this->validator->set_message('max_length', '{field}：最大长度不能超过 {param} 位');
        $this->validator->set_message('exact_length', '{field}：长度必须是 {param} 位');
        $this->validator->set_message('greater_than', '{field}：必须大于 {param}');
        $this->validator->set_message('greater_than_equal_to', '{field}：必须大于或等于 {param}');
        $this->validator->set_message('less_than', '{field}：必须小于 {param}');
        $this->validator->set_message('less_than_equal_to', '{field}：必须小于或等于 {param}');
        $this->validator->set_message('in_list', '{field}：请选择正确选项');
        $this->validator->set_message('alpha', '{field}：只能包含字母');
        $this->validator->set_message('alpha_numeric', '{field}：只能包含字母和数字');
        $this->validator->set_message('alpha_numeric_spaces', '{field}：只能包含字母、数字和空格');
        $this->validator->set_message('alpha_dash', '{field}：只能包含字母、数字、下划线和破折号');
        $this->validator->set_message('numeric', '{field}：请输入数字');
        $this->validator->set_message('integer', '{field}：请输入整数');
        $this->validator->set_message('decimal', '{field}：请输入十进制数字');
        $this->validator->set_message('is_natural', '{field}：请输入自然数');
        $this->validator->set_message('is_natural_no_zero', '{field}：请输入非零自然数');
        $this->validator->set_message('valid_url', '{field}：请输入 URL 格式');
        $this->validator->set_message('valid_email', '{field}：请输入 email 格式');
        $this->validator->set_message('valid_emails', '{field}：请输入 email 格式（地址之间用逗号分割）');
        $this->validator->set_message('valid_ip', '{field}：请输入 IP 格式');
        $this->validator->set_message('valid_base64', '{field}：请输入 base64 编码字符');
    }

    /**  验证
     * @param array $data     请求数据
     * @param string $columns  验证策略
     * @param string $method  验证策略
     * @return array|bool
     */
    public function validate(array $data, array $columns, $method)
    {
        $this->validator->reset_validation(); // 重置验证规则

        $this->validator->set_data($data); // 设置验证数据
        $this->setCommonMsg();  // 设置验证信息
        $this->setValiRules($columns,$method); // 设置相关验证规则

        $result=$this->validator->run(); // 验证
        if($result){
            return true;
        }else{
            return $this->validator->error_array();
        }
    }

}