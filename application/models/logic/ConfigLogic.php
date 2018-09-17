<?php
/**
 *  Config 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-15
 */

namespace models\logic;

use libraries\Excel;
use libraries\ListIterator;
use models\data\ConfigData;
use models\database\mysql\ConfigMysql;
use models\database\redis\ConfigRedis;
use models\validator\ConfigValidator;

class ConfigLogic extends LogicModel
{
    protected $tplDB;
    protected $tplBackDB;
    protected $tplData;
    protected $tplValidator;

    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
        $this->databaseModel = ConfigRedis::instance();
        $this->backDB = ConfigMysql::instance();
        // mysql 为主，redis 备份
//        $this->databaseModel = ConfigMysql::instance();
//        $this->backDB = ConfigRedis::instance();

        $this->dataModel = ConfigData::instance();
        $this->validatorModel = ConfigValidator::instance();

        $this->tplDB = ConfigRedis::class;
        $this->tplBackDB = ConfigMysql::class;
        $this->tplData = ConfigData::class;
        $this->tplValidator = ConfigValidator::class;
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Table
        if(true !== $this->checkTableUnique($data)){
            $name=$this->dataModel->fieldsName['Table'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }

    /** 获取数据库模型
     * @param string $table
     * @return mixed
     */
    public function getDBModel($table)
    {
        $args['table'] = $table;
        eval("\$databaseModel = \\{$this->tplDB}::instance(\$table,\$args);");

        return $databaseModel;
    }

    /** 获取数据模型
     * @param string $table
     * @param array $columns
     * @return mixed
     */
    public function getDataModel($table, array $columns)
    {
        eval("\$dataModel = \\{$this->tplData}::instance(\$table,\$columns);");

        return $dataModel;
    }

    /** 获取验证模型
     * @param string $table
     */
    public function getValidatorModel($table)
    {
        eval("\$validatorModel = \\{$this->tplValidator}::instance(\$table);");

        return $validatorModel;
    }

    /** 批量更新配置
     * @param string $file
     * @return bool|int
     * @throws \PHPExcel_Reader_Exception
     */
    public function update($file)
    {
        // 读取 excel
        $dataList=Excel::instance()->getAllConfigDataList($file);
        // 批量导入数据
        $configList=array();
        foreach($dataList as $table => $data){
            // 实例化模型
            $databaseModel=$this->getDBModel($table);
            // 建表
            $result = $databaseModel->createTable($table,$data['columns'],true);
            if(false == $result){
                continue;
            }
            // 导入数据
            $result = $databaseModel->batchInsertUpdate($data['list']);
            if($result < 1){
                continue;
            }
            // 添加配置
            // 获取现有
            $where=array(
                array('Table','eq',$table),
            );
            $config=$this->databaseModel->getOne($where);
            // 不存在则添加，存在则更新
            $add=array(
                'Table'=>$table,
                'Name'=>$table,
                'Columns'=>$data['columns'],
                'Infos'=>$table,
            );
            if(empty($config['Id'])){
                $config = $this->dataModel->fill($add,'add');
                $config['Id'] = null;
            }
            else{
                $update = $this->dataModel->fill($add,'update');
                $config = array_merge($config,$update);
            }
            $configList[]=$config;
        }
        // 添加、更新配置表
        if(empty($configList)){
            return false;
        }
        $result = $this->databaseModel->batchInsertUpdate($configList);

        return $result;
    }

    /** 下载配置 Excel
     * @param array $input
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function download(array $input)
    {
        if(empty($input['Id'])){
            throw new \Exception('请选择一项',EXIT_USER_INPUT);
        }
        $config=$this->isFormat(true)->getRowById($input['Id']);
        if(empty($config['Id'])){
            throw new \Exception('配置不存在',EXIT_USER_INPUT);
        }
        // 实例化配置模型
        $databaseModel=$this->getDBModel($config['Table']);
        // 获取数据
        $list = $databaseModel->getMany();
        // 输入 Excel
        Excel::instance()->exportConfig($list,$config['Columns'],$config['Table'],true);
    }

    /** 获取配置数据
     * @param string $table
     * @return mixed
     */
    public function getDataList($table)
    {
        // 实例化配置模型
        $databaseModel=$this->getDBModel($table);
        // 获取数据
        $list = $databaseModel->getMany();

        return $list;
    }


}