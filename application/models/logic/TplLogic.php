<?php
/**
 *  Tpl 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-18
 */

namespace models\logic;

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
    protected $tplConfig;


    public function __construct($table)
    {
        $this->tplConfig = ConfigLogic::instance()->getRowByTable($table);
        if(empty($this->tplConfig)){
            throw new \Exception('配置表不存在',EXIT_USER_INPUT);
        }
        $args = array();
        foreach($this->tplConfig['DBConf'] as $dbConf){
            $args[$dbConf['type']] = $dbConf;
        }
        // 数据库配置
        $mainDB = ucfirst($this->tplConfig['MainDB']);
        $backDB = ucfirst($this->tplConfig['BackDB']);
        eval("\$this->tplDB = Tpl$mainDB::class;");
        eval("\$this->tplBackDB = Tpl$backDB::class;");

        $this->tplData = TplData::class;
        $this->tplValidator = TplValidator::class;

        parent::__construct();

        $this->databaseModel = $this->getDBModel($args);
        $this->backDB = $this->getBackDBModel($args);

        $this->dataModel = $this->getDataModel($table,$this->tplConfig['Columns']);
        $this->validatorModel = $this->getValidatorModel($table);
    }

    /**  获取实例
     * @param string $table
     * @param string $k
     * @return LogicModel
     * @throws \Exception
     */
    static public function instance($table='', $k=0)
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
    public function _unset($table='', $k = 0)
    {
        if(empty($k)){
            $k=get_called_class();
        }
        if(isset(static::$objs[$table][$k])){
            unset(static::$objs[$table][$k]);
        }
    }

    /** 获取数据库模型
     * @param array $args
     * @param string $k
     * @return mixed
     */
    public function getDBModel($args,$k=0)
    {
        eval("\$databaseModel = \\{$this->tplDB}::instance(\$table,\$args,\$k);");

        return $databaseModel;
    }

    /** 获取备份数据库模型
     * @param array $args
     * @param string $k
     * @return mixed
     */
    public function getBackDBModel($args,$k=0)
    {
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
}