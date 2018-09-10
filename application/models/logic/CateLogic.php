<?php
/**
 *  Cate 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-11
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\CateData;
use models\database\mysql\CateMysql;
use models\database\redis\CateRedis;
use models\validator\CateValidator;

class CateLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        $this->databaseModel = CateRedis::instance();
        $this->backDB = CateMysql::instance();
        $this->dataModel = CateData::instance();
        $this->validatorModel = CateValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Constant
        if(true !== $this->checkConstantUnique($data)){
            $name=$this->dataModel->fieldsName['Constant'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }

}