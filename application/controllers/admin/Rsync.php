<?php
/**
 *  Rsync
 * @author 罗仕辉
 * @create 2018-09-17
 */

require_once APPPATH.'controllers/admin/Auth.php';

use models\logic\RsyncLogic;

class Rsync extends Auth
{
    public function __construct()
    {
        parent::__construct();

        $this->logicModel = RsyncLogic::instance();
    }

    /**
     *  列表
     */
    public function index()
    {
        $list=$this->logicModel->getAll();

        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/rsync/index',
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
            
            $data=array();
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/rsync/add',
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
            $url='/admin/rsync';
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
        $tpls=array(
            'admin/rsync/info',
        );
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
                'admin/rsync/edit',
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
            $url='/admin/rsync';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }

    /** 同步
     * @throws Exception
     */
    public function act()
    {
        if(empty($this->inputData['Act']) || !in_array($this->inputData['Act'],array('backup','restore'))){
            throw new Exception('操作错误',EXIT_USER_INPUT);
        }
        $act=$this->inputData['Act'];
        // 全部
        if(empty($this->inputData['Id'])){
            $list=$this->logicModel->getAll();
        }
        // 指定一项
        else{
            $id=(int)$this->inputData['Id'];
            $row=$this->logicModel->getRowById($id);
            if(empty($row['Id'])){
                throw new Exception('数据不存在',EXIT_DATABASE);
            }
            $list[]=$row;
        }
        if(empty($list)){
            throw new Exception('没有添加同步模块',EXIT_DATABASE);
        }
        foreach($list as $row){
            eval("{$row['Instance']}->{$row['Method']}('{$act}');");
        }

        $data=array();
        $code=EXIT_SUCCESS;
        $msg='同步完成';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}