<?php
/**
 *  Source 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-12
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\SourceData;
use models\database\mysql\SourceMysql;
use models\database\redis\SourceRedis;
use models\validator\SourceValidator;

class SourceLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        $this->databaseModel = SourceRedis::instance();
        $this->backDB = SourceMysql::instance();

//        $this->databaseModel = SourceMysql::instance();
//        $this->backDB = SourceRedis::instance();

        $this->dataModel = SourceData::instance();
        $this->validatorModel = SourceValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Url
        if(true !== $this->checkUrlUnique($data)){
            $name=$this->dataModel->fieldsName['Url'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }
}