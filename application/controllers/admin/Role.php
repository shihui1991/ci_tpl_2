<?php
/**
 *  Role
 * @author 罗仕辉
 * @create 2018-09-09
 */

require_once APPPATH.'controllers/admin/Auth.php';

use models\logic\RoleLogic;

class Role extends Auth
{
    public function __construct()
    {
        parent::__construct();

        $this->logicModel = RoleLogic::instance();
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
        $list=$this->logicModel->getListChildByParentId($parentId);

        $data=array(
            'ParentId'=>$parentId,
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/role/index',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  全部
     */
    public function all()
    {
        $list=$this->logicModel->getAll();

        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array();
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
                'admin/role/add',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            $newRow=$this->logicModel->add($this->inputData);

            $data=array(
                'List'=>$newRow,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/role';
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
        $row=$this->logicModel->getRowById($id);
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
            $row=$this->logicModel->getRowById($id);
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
                'admin/role/edit',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            $updated=$this->logicModel->edit($this->inputData);

            $data=array(
                'List'=>$updated,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/role';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }
}