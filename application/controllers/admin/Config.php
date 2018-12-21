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
        // 处理筛选
        $filter = $this->logicModel->handleFilter($this->inputData);
        $params=$filter['Params'];
        $orderBy=$filter['OrderBy'];

        // 获取列表
        $list=$this->logicModel->getAll($params,$orderBy);

        $data=array(
            'List'=>$list,
            'FilterUrl'=>$this->requestUrl,
            'OtherBtns'=>array(),
        );
        $data = array_merge($data,$filter);
        
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        $tpls=array(
            'admin/config/index',
        );
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  新增
     * @throws \Exception
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
                'admin/config/add',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            $newRow=$this->logicModel->add($this->inputData);
            // 建表
            $tplLogic=\models\logic\TplLogic::instance($newRow['Table']);
            $result = $tplLogic->databaseModel->createTable($newRow['Columns']);
            if(false == $result){
                throw new Exception('建表失败',EXIT_DATABASE);
            }

            $data=array(
                'List'=>$newRow,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/config';
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
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

    /** 删除配置
     * @throws Exception
     */
    public function del()
    {
        if(empty($this->inputData['Ids'])){
            throw new Exception('请选择数据',EXIT_USER_INPUT);
        }
        if(is_array($this->inputData['Ids'])){
            $ids=$this->inputData['Ids'];
        }else{
            $ids=array((int)$this->inputData['Ids']);
        }

        $result=$this->logicModel->delByIds($ids);
        if(false === $result){
            throw new Exception('删除失败',EXIT_DATABASE);
        }
        // 删表
        $select=array(
            'Id',
            'Table',
        );
        $list=$this->logicModel->getListByIds($ids,$select);
        foreach($list as $row){
            \models\logic\TplLogic::instance($row['Table'])->databaseModel->dropTable();
        }

        $data=array(
            'Ids'=>$ids,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='/admin/config';
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
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
     * @throws Exception
     */
    public function download()
    {
        if(empty($this->inputData['Id'])){
            throw new Exception('请选择一项',EXIT_USER_INPUT);
        }
        $select=array(
            'Id',
            'Table',
        );
        $config=$this->logicModel->getRowById($this->inputData['Id'],$select);
        if(empty($config['Id'])){
            throw new Exception('配置不存在',EXIT_USER_INPUT);
        }
        $this->logicModel->download($config['Table']);
    }

    /** 获取配置数据
     * @throws Exception
     */
    public function data()
    {
        if(empty($this->inputData['ConfigId'])){
            throw new Exception('请选择一项',EXIT_USER_INPUT);
        }
        $configId=(int)$this->inputData['ConfigId'];
        $config=$this->logicModel->getRowById($configId);
        if(empty($config['Id'])){
            throw new Exception('配置不存在',EXIT_USER_INPUT);
        }
        $tplLogic=\models\logic\TplLogic::instance($config['Table']);
        // 处理筛选
        $filter = $tplLogic->handleFilter($this->inputData);
        $params=$filter['Params'];
        $orderBy=$filter['OrderBy'];

        // 获取列表
        $list=$tplLogic->isFormat(false)->getAll($params,$orderBy);

        $data=array(
            'ConfigId'=>$configId,
            'Config'=>$config,
            'List'=>$list,
            'FilterUrl'=>$this->requestUrl.'?ConfigId='.$configId,
            'OtherBtns'=>array(),
        );
        $data = array_merge($data,$filter);

        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='';
        // 单项配置
        if(YES == $config['Single']){
            $tpls=array(
                'admin/config/dataSingle',
            );
        }else{
            $tpls=array(
                'admin/config/data',
            );
        }
        $this->_response($data,$code,$msg,$url,$tpls);
    }

    /**
     *  添加数据
     * @throws \Exception
     */
    public function insert()
    {
        // 添加页
        if('get' == $this->input->method()){
            if(empty($this->inputData['ConfigId'])){
                throw new Exception('参数错误',EXIT_USER_INPUT);
            }
            $configId=(int)$this->inputData['ConfigId'];
            $config=$this->logicModel->getRowById($configId);
            if(empty($config['Id'])){
                throw new Exception('配置不存在',EXIT_DATABASE);
            }

            $data=array(
                'ConfigId'=>$configId,
                'Config'=>$config,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/config/insert',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            if(empty($this->inputData['ConfigId'])){
                throw new Exception('参数错误',EXIT_USER_INPUT);
            }
            $configId=(int)$this->inputData['ConfigId'];
            $config=$this->logicModel->getRowById($configId);
            if(empty($config['Id'])){
                throw new Exception('配置不存在',EXIT_DATABASE);
            }
            $tplLogic=\models\logic\TplLogic::instance($config['Table']);
            // 添加
            $newRow=$tplLogic->isFormat(false)->add($this->inputData);

            $data=array(
                'ConfigId'=>$configId,
                'List'=>$newRow,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/config/data?ConfigId='.$configId;
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }

    /**  修改数据
     * @throws Exception
     */
    public function modify()
    {
        // 修改页
        if('get' == $this->input->method()){
            if(empty($this->inputData['ConfigId'])){
                throw new Exception('参数错误',EXIT_USER_INPUT);
            }
            $configId=(int)$this->inputData['ConfigId'];
            $config=$this->logicModel->getRowById($configId);
            if(empty($config['Id'])){
                throw new Exception('配置不存在',EXIT_DATABASE);
            }
            $tplLogic=\models\logic\TplLogic::instance($config['Table']);

            if(empty($this->inputData['Key'])){
                throw new Exception('请选择数据',EXIT_USER_INPUT);
            }
            $key=$this->inputData['Key'];
            $row=$tplLogic->databaseModel->getOneByKey($key);
            if(empty($row)){
                throw new Exception('数据不存在',EXIT_DATABASE);
            }

            $data=array(
                'ConfigId'=>$configId,
                'Config'=>$config,
                'Key'=>$key,
                'List'=>$row,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='';
            $tpls=array(
                'admin/config/modify',
            );
            $this->_response($data,$code,$msg,$url,$tpls);
        }
        // 保存
        else{
            if(empty($this->inputData['ConfigId'])){
                throw new Exception('参数错误',EXIT_USER_INPUT);
            }
            $configId=(int)$this->inputData['ConfigId'];
            $config=$this->logicModel->getRowById($configId);
            if(empty($config['Id'])){
                throw new Exception('配置不存在',EXIT_DATABASE);
            }
            $tplLogic=\models\logic\TplLogic::instance($config['Table']);

            $updated=$tplLogic->isFormat(false)->edit($this->inputData);

            $data=array(
                'ConfigId'=>$configId,
                'List'=>$updated,
            );
            $code=EXIT_SUCCESS;
            $msg='请求成功';
            $url='/admin/config/data?ConfigId='.$configId;
            $tpls=array();
            $this->_response($data,$code,$msg,$url,$tpls);
        }
    }

    /** 删除数据
     * @throws Exception
     */
    public function delete()
    {
        if(empty($this->inputData['ConfigId'])){
            throw new Exception('参数错误',EXIT_USER_INPUT);
        }
        $configId=(int)$this->inputData['ConfigId'];
        $config=$this->logicModel->getRowById($configId);
        if(empty($config['Id'])){
            throw new Exception('配置不存在',EXIT_DATABASE);
        }
        $tplLogic=\models\logic\TplLogic::instance($config['Table']);

        if(empty($this->inputData['Keys'])){
            throw new Exception('请选择数据',EXIT_USER_INPUT);
        }
        if(is_array($this->inputData['Keys'])){
            $keys=$this->inputData['Keys'];
        }else{
            $keys=array((int)$this->inputData['Keys']);
        }

        $result=$tplLogic->delByIds($keys);
        if(false === $result){
            throw new Exception('删除失败',EXIT_DATABASE);
        }

        $data=array(
            'ConfigId'=>$configId,
            'Keys'=>$keys,
        );
        $code=EXIT_SUCCESS;
        $msg='请求成功';
        $url='/admin/config/data?ConfigId='.$configId;
        $tpls=array();
        $this->_response($data,$code,$msg,$url,$tpls);
    }
}