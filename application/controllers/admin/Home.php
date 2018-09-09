<?php
/**
 *  Home
 * @user 罗仕辉
 * @create 2018-09-09
 */

require_once APPPATH.'controllers/admin/Auth.php';

use models\logic\MenuLogic;

class Home extends Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data=array(
            'Master'=>$this->master,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/home',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  获取导航子菜单
     */
    public function nav()
    {
        $parentId=0;
        if(!empty($this->inputData['ParentId'])){
            $parentId=(int)$this->inputData['ParentId'];
        }
        $ids=array();
        $ctrl=false;
        if(ADMIN_NO == $this->master['IsAdmin']){
            $ids=$this->master['MenuIds'];
            $ctrl=true;
        }
        $navList=MenuLogic::instance()->getNavList($parentId,$ids,$ctrl);

        $data=array(
            'ParentId'=>$parentId,
            'List'=>$navList,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /** 文件上传
     * @throws Exception
     */
    public function upload()
    {
        $data=$this->_upload();

        $code=EXIT_SUCCESS;
        $msg='上传成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}