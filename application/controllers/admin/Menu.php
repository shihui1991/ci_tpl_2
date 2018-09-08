<?php
/**
 *  Menu
 * @user 罗仕辉
 * @create 2018-09-08
 */

require_once APPPATH.'controllers/admin/Auth.php';

use models\logic\MenuLogic;

class Menu extends Auth
{
    public function __construct()
    {
        parent::__construct();

    }

    /**
     *  列表
     */
    public function index()
    {
        $parentId=0;
        if(!empty($this->inputData['ParentId'])){
            $parentId=(int)$this->inputData['ParentId'];
        }
        // 获取子菜单列表
        $list=MenuLogic::instance()->getListChildByParentId($parentId);

        $data=array(
            'ParentId'=>$parentId,
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/menu/index',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  新增
     */
    public function add()
    {
        // 添加页
        if('get' == $this->input->method()){

            $parentId=0;
            if(!empty($this->inputData['ParentId'])){
                $parentId=(int)$this->inputData['ParentId'];
            }

            $data=array(
                'ParentId'=>$parentId,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/menu/add',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            $newRow=MenuLogic::instance()->add($this->inputData);

            $data=array(
                'List'=>$newRow,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/menu';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }

    /** 信息
     * @throws Exception
     */
    public function info()
    {
        if(empty($this->inputData['Id'])){
            throw new Exception('请选择数据',EXIT_USER_INPUT);
        }
        $id=(int)$this->inputData['Id'];
        $row=MenuLogic::instance()->getRowById($id);
        if(empty($row['Id'])){
            throw new Exception('数据不存在',EXIT_DATABASE);
        }

        $data=array(
            'Id'=>$id,
            'List'=>$row,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**  修改
     * @throws Exception
     */
    public function edit()
    {
        // 修改页
        if('get' == $this->input->method()){

            if(empty($this->inputData['Id'])){
                throw new Exception('请选择数据',EXIT_USER_INPUT);
            }
            $id=(int)$this->inputData['Id'];
            $row=MenuLogic::instance()->getRowById($id);
            if(empty($row['Id'])){
                throw new Exception('数据不存在',EXIT_DATABASE);
            }

            $data=array(
                'Id'=>$id,
                'List'=>$row,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/menu/edit',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            $updated=MenuLogic::instance()->edit($this->inputData);

            $data=array(
                'List'=>$updated,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/menu';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }
}