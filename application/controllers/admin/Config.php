<?php
/**
 *  Config
 * @user 罗仕辉
 * @create 2018-09-15
 */

require_once APPPATH.'controllers/admin/Auth.php';

use models\logic\ConfigLogic;

class Config extends Auth
{
    public function __construct()
    {
        parent::__construct();

        $this->logicModel = ConfigLogic::instance();
    }

    /**
     *  列表
     */
    public function index()
    {
        $params=array();
        $args=array();
        // 查询参数
        // Table
        $table='';
        if(!empty($this->inputData['Table'])){
            $table=(string)$this->inputData['Table'];
            $params[]=array('Table','like',$table);
        }
        $args['Table']=$table;
        // Name
        $name='';
        if(!empty($this->inputData['Name'])){
            $name=(string)$this->inputData['Name'];
            $params[]=array('Name','like',$name);
        }
        $args['Name']=$name;
        
        $list = $this->logicModel->getAll();

        $data=array(
            'List'=>$list,
        );
        $data=array_merge($args,$data);
        
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/config/index',
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
                'admin/config/edit',
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
            $url='/admin/config';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }

    /**
     *  所有配置文件
     */
    public function file()
    {
        $list = getDirAllDirOrFile(UPLOAD_DIR.'/'.CONFIG_UPLOAD_DIR);

        $data=array(
            'List'=>$list,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/config/file',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**  更新配置
     * @throws Exception
     */
    public function update()
    {
        if(empty($this->inputData['File'])) {
            throw new Exception('请选择文件',EXIT_USER_INPUT);
        }
        $file = realpath($this->inputData['File']);
        if(false == $file){
            throw new Exception('文件不存在',EXIT_UNKNOWN_FILE);
        }
        $result = $this->logicModel->update($file);
        if($result < 1){
            throw new \Exception('配置表更新失败',EXIT_DATABASE);
        }

        $data=array();
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /** 删除配置文件
     * @throws Exception
     */
    public function remove()
    {
        if(empty($this->inputData['File'])) {
            throw new Exception('请选择文件',EXIT_USER_INPUT);
        }
        $file = realpath($this->inputData['File']);
        if(false == $file){
            throw new Exception('文件不存在',EXIT_UNKNOWN_FILE);
        }
        $result = unlink($file);
        if(false == $result){
            throw new Exception('删除失败',EXIT_CONFIG);
        }

        $data=array();
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  下载配置
     */
    public function download()
    {
        $this->logicModel->download($this->inputData);
    }

    /** 获取配置数据
     * @throws Exception
     */
    public function data()
    {
        if(empty($this->inputData['Id'])){
            throw new Exception('请选择一项',EXIT_USER_INPUT);
        }
        $id=(int)$this->inputData['Id'];
        $config=$this->logicModel->getRowById($id);
        if(empty($config['Id'])){
            throw new Exception('配置不存在',EXIT_USER_INPUT);
        }
        $dataList=$this->logicModel->getDataList($config['Table']);

        $data=array(
            'Id'=>$id,
            'Config'=>$config,
            'List'=>$dataList,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/config/data',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}