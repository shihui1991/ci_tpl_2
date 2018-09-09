<?php
/**
 *  登录与退出
 * @user 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/admin/Init.php';

use models\logic\MasterLogic;

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

    /**
     *  登录
     */
    public function login()
    {
        $list=MasterLogic::instance()->login($this->inputData);

        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='登录成功';
        $url='/admin/homel';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     * 退出
     */
    public function logout()
    {
        unset($_SESSION['Master']);

        $data=array();
        $code=EXIT_SUCCESS;
        $msg='退出成功';
        $url='/admin';
        $tpls=array(
            'admin/login',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}