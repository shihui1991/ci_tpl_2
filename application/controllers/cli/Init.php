<?php
/**
 *  初始化
 * @author 罗仕辉
 * @create 2018-10-15
 */

require_once APPPATH.'controllers/Base.php';

class Init extends Base
{

    public function __construct()
    {
        parent::__construct();

        $whiteIps=array(
            '0.0.0.0',
            '127.0.0.1',
            '::1',
        );
        if(!is_cli() && !in_array($this->clientIP,$whiteIps)){
            show_404();
        }
    }

    /**  响应输出
     * @param array $data 响应数据
     * @param int $code   响应代码
     * @param string $msg 提示信息
     * @param string $url 重定向地址
     * @param array $tpls 响应模板
     */
    public function _response($data=array(),$code=EXIT_SUCCESS,$msg='请求成功',$url='', $tpls=array())
    {
        parent::_response($data,$code,$msg,$url,$tpls);

        echo $msg.PHP_EOL;
    }
}