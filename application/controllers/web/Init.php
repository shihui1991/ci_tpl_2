<?php
/**
 *  初始化
 * @author 罗仕辉
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
    public function _response($data=array(),$code=EXIT_SUCCESS,$msg='请求成功',$url='', $tpls=array())
    {
        parent::_response($data,$code,$msg,$url,$tpls);

        if(empty($url)){
            if(empty($tpls)){
                echo '<script>alert("'.$msg.'");location=document.referrer;</script>';
            }else{
                $this->load->view('layout/head',$this->outputData);

                if(is_string($tpls)){
                    $this->load->view($tpls,$this->outputData);
                }else{
                    foreach($tpls as $tpl){
                        $this->load->view($tpl,$this->outputData);
                    }
                }
                $this->load->view('layout/foot',$this->outputData);
            }
        }else{
            echo '<script>alert("'.$msg.'");location.href="'.$url.'"</script>';
        }
    }
}