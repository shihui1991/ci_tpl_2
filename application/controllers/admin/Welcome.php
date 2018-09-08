<?php
/**
 *  登录与退出
 * @user 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/admin/Init.php';

class Welcome extends Init
{
    public function __construct()
    {
        parent::__construct();

    }

    /**
     *  登录页
     */
    public function index()
    {
        $data=array();
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/login',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}