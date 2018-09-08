<?php
/**
 *  初始化
 * @user 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/Base.php';

class Init extends Base
{

    public function __construct()
    {
        parent::__construct();

    }

    /**  响应输出
     * @param array $data 响应数据
     * @param int $code   响应代码
     * @param string $msg 提示信息
     * @param string $url 重定向地址
     * @param array $tpls 响应模板
     */
    public function _response(array $data=array(),$code=EXIT_SUCCESS,$msg='请求成功',$url='', $tpls=array())
    {
        $this->outputData=array(
            'data'=>$data,
            'code'=>$code,
            'msg'=>$msg,
            'url'=>$url,
        );
        $resp=json_encode($this->outputData);
        echo $resp;
    }
}