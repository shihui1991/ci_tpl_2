<?php
/**
 *  Tpl 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-18
 */

namespace models\logic;

use libraries\Excel;
use libraries\ListIterator;
use models\data\TplData;
use models\database\mysql\TplMysql;
use models\database\redis\TplRedis;
use models\validator\TplValidator;

class TplLogic extends LogicModel
{
    protected $tplDB;
    protected $tplBackDB;
    protected $tplData;
    protected $tplValidator;


    public function __construct($table)
    {
        // redis 为主，mysql 备份
        $this->tplDB = TplRedis::class;
        $this->tplBackDB = TplMysql::class;

        $this->tplData = TplData::class;
        $this->tplValidator = TplValidator::class;

        $config = ConfigLogic::instance()->getRowByTable($table);
        if(empty($config)){
            throw new \Exception('配置表不存在',EXIT_USER_INPUT);
        }

        parent::__construct();

        $this->databaseModel = $this->getDBModel($table);
        $this->backDB = $this->getBackDBModel($table);

        $this->dataModel = $this->getDataModel($table,$config['Columns']);
        $this->validatorModel = $this->getValidatorModel($table);
    }

    /**  获取实例
     * @param string $table
     * @param string $k
     * @return LogicModel
     * @throws \Exception
     */
    static public function instance($table, $k=0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(empty(static::$objs[$table][$k])){
            static::$objs[$table][$k] = new static($table);
        }
        return static::$objs[$table][$k];
    }

    /** 销毁实例
     * @param string $table
     * @param string $k
     */
    public function _unset($table, $k = 0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(isset(static::$objs[$table][$k])){
            unset(static::$objs[$table][$k]);
        }
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        return true;
    }

    /** 获取数据库模型
     * @param string $table
     * @param string $k
     * @return mixed
     */
    public function getDBModel($table,$k=0)
    {
        $args['table'] = $table;
        eval("\$databaseModel = \\{$this->tplDB}::instance(\$table,\$args,\$k);");

        return $databaseModel;
    }

    /** 获取备份数据库模型
     * @param string $table
     * @param string $k
     * @return mixed
     */
    public function getBackDBModel($table,$k=0)
    {
        $args['table'] = $table;
        eval("\$backDBModel = \\{$this->tplBackDB}::instance(\$table,\$args,\$k);");

        return $backDBModel;
    }

    /** 获取数据模型
     * @param string $table
     * @param array $columns
     * @param string $k
     * @return mixed
     */
    public function getDataModel($table, array $columns,$k=0)
    {
        eval("\$dataModel = \\{$this->tplData}::instance(\$table,\$columns,\$k);");

        return $dataModel;
    }

    /** 获取验证模型
     * @param string $table
     * @param string $k
     */
    public function getValidatorModel($table,$k=0)
    {
        eval("\$validatorModel = \\{$this->tplValidator}::instance(\$table,\$k);");

        return $validatorModel;
    }

    /** 导入数据
     * @param array $list
     * @return bool|int
     */
    public function importData(array $list)
    {
        // 建表
        $result = $this->databaseModel->createTable($this->dataModel->getColumns(),true);
        if(false == $result){
            return false;
        }
        if(empty($list)){
            return 0;
        }
        // 导入数据
        $result = $this->databaseModel->batchInsertUpdate($list);

        return $result;
    }

    /** 导出Excel
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function download()
    {
        // 获取数据
        $list = $this->databaseModel->getMany();
        // 输入 Excel
        Excel::instance()->exportConfig($list,$this->dataModel->getColumns(),$this->databaseModel->table,true);
    }
}