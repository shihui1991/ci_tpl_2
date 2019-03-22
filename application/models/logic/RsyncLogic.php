<?php
/**
 *  Rsync 逻辑模型
 * @author 罗仕辉
 * @create 2018-09-17
 */

namespace models\logic;


use models\data\RsyncData;
use models\database\mysql\RsyncMysql;
use models\database\redis\RsyncRedis;
use models\validator\RsyncValidator;

class RsyncLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
//        $this->databaseModel = RsyncRedis::instance();
//        $this->backDB = RsyncMysql::instance();
        // mysql 为主，redis 备份
        $this->databaseModel = RsyncMysql::instance();
//        $this->backDB = RsyncRedis::instance();
        $this->backDBStr = 'models\database\redis\RsyncRedis';

        $this->dataModel = RsyncData::instance();
        $this->validatorModel = RsyncValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Name
        if(true !== $this->checkNameUnique($data)){
            $name=$this->dataModel->fieldsName['Name'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }
        // 验证 Instance
        if(true !== $this->checkInstanceUnique($data)){
            $name=$this->dataModel->fieldsName['Instance'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }
}