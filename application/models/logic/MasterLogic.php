<?php
/**
 *  Master 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\MasterData;
use models\database\redis\MasterRedis;
use models\validator\MasterValidator;

class MasterLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        $this->databaseModel = MasterRedis::instance();
        $this->dataModel = MasterData::instance();
        $this->validatorModel = MasterValidator::instance();
    }

    /**  验证 唯一
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function checkUnique(array $data)
    {
        // 验证 Account
        if(true !== $this->checkAccountUnique($data)){
            $name=$this->dataModel->fieldsName['Account'];
            throw new \Exception($name.' 已存在',EXIT_USER_INPUT);
        }

        return true;
    }
}