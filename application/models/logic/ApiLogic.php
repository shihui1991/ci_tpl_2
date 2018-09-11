<?php
/**
 *  Api 逻辑模型
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\logic;

use libraries\ListIterator;
use models\data\ApiData;
use models\database\mysql\ApiMysql;
use models\database\redis\ApiRedis;
use models\validator\ApiValidator;

class ApiLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        $this->databaseModel = ApiRedis::instance();
        $this->backDB = ApiMysql::instance();
        $this->dataModel = ApiData::instance();
        $this->validatorModel = ApiValidator::instance();
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

    /** 通过 Url 获取接口配置信息
     * @param string $url
     * @return array
     */
    public function getRowByUrl($url)
    {
        $where=array(
            array('Url','eq',$url),
        );
        $select=array(
            'Id',
            'Name',
            'Url',
            'EventId',
            'State',
            'Infos',
        );
        $row = $this->databaseModel->getOne($where,$select);
        if(empty($row)){
            return array();
        }
        $result=$this->dataModel->format($row);

        return $result;
    }
}