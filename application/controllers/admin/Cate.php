<?php
/**
 *  Cate
 * @user 罗仕辉
 * @create 2018-09-10
 */

require_once APPPATH.'controllers/admin/Auth.php';

use models\logic\CateLogic;

class Cate extends Auth
{
    public function __construct()
    {
        parent::__construct();

        $this->logicModel = CateLogic::instance();
    }

    /**
     *  列表
     */
    public function index()
    {
        $list = $this->logicModel->getGroupList();
        
        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/cate/index',
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

            $group='';
            if(!empty($this->inputData['Group'])){
                $group = $this->inputData['Group'];
            }

            $data=array(
                'Group'=>$group,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/cate/add',
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
            $url='/admin/cate';
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
            'admin/cate/info',
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
                'admin/cate/edit',
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
            $url='/admin/cate';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }

    /** 查看内容
     * @throws Exception
     */
    public function file()
    {
        $file = realpath(APPPATH.'config/common/conf.php');
        if(false == $file){
            throw new Exception('文件不存在',EXIT_UNKNOWN_FILE);
        }
        // 逐行读取
        $list=array();
        $handle = fopen($file, "r") or exit("不能打开文件");
        while(!feof($handle))
        {
            $list[] = fgets($handle);
        }
        fclose($handle);

        $data=array(
            'Updated'=>filemtime($file),
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/cate/file',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /** 更新配置文件 config/common/conf.php
     * @throws Exception
     */
    public function update()
    {
        $result = $this->logicModel->updateConf();
        if(false == $result){
            throw new Exception('更新失败',EXIT_DATABASE);
        }

        $data=array();
        $code=EXIT_SUCCESS;
        $msg='更新成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}