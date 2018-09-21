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
    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
        $this->databaseModel = ConfigRedis::instance();
        $this->backDB = ConfigMysql::instance();

        $this->dataModel = ConfigData::instance();
        $this->validatorModel = ConfigValidator::instance();
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

    /** 通过 Table 获取数据
     * @param string $table
     * @return array
     */
    public function getRowByTable($table)
    {
        $where=array(
            array('Table','eq',$table),
        );
        $row=$this->databaseModel->getOne($where);
        if(empty($row['Id'])){
            return array();
        }
        if($this->isFormat){
            $result=$this->dataModel->format($row,$this->isAlias);
        }else{
            $result=$row;
        }

        return $result;
    }

    /** 批量更新配置
     * @param string $file
     * @return bool|int
     * @throws \Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function update($file)
    {
        // 读取 excel
        $dataList=Excel::instance()->getAllConfigDataList($file);
        // 批量导入配置表数据
        $configList=array();
        foreach($dataList as $table => $data){
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
            throw new \Exception('配置为空',EXIT_USER_INPUT);
        }
        $result = $this->databaseModel->batchInsertUpdate($configList);
        if($result < 1){
            throw new \Exception('导入配置表失败',EXIT_DATABASE);
        }
        // 导入配置数据
        foreach($dataList as $table=>$data){
            TplLogic::instance($table)->importData($data['list']);
        }

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
        TplLogic::instance($config['Table'])->download();
    }

    /** 获取配置数据
     * @param string $table
     * @return mixed
     */
    public function getDataList($table)
    {
        $list=TplLogic::instance($table)->getAll();

        return $list;
    }

    /** 同步所有快捷配置数据
     * @param string $act
     * @throws \Exception
     */
    public function rsyncAll($act='backup')
    {
        $list = $this->getAll();
        if(!empty($list)){
            foreach($list as $row){
                $this->rsyncData($row,$act);
            }
        }
    }

    /** 同步快捷配置数据
     * @param array $config
     * @param string $act
     * @throws \Exception
     */
    public function rsyncData(array $config,$act='backup')
    {
        $result = TplLogic::instance($config['Table'])->rsync($act);

        return $result;
    }
}