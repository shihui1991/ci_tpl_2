<?php
/**
 *  Api 逻辑模型
 * @author 罗仕辉
 * @create 2018-09-09
 */

namespace models\logic;

use models\data\ApiData;
use models\database\mysql\ApiMysql;
use models\database\redis\ApiRedis;
use models\validator\ApiValidator;

class ApiLogic extends LogicModel
{
    public function __construct()
    {
        parent::__construct();

        // redis 为主，mysql 备份
//        $this->databaseModel = ApiRedis::instance();
//        $this->backDB = ApiMysql::instance();
        // mysql 为主，redis 备份
        $this->databaseModel = ApiMysql::instance();
//        $this->backDB = ApiRedis::instance();
        $this->backDBStr = 'models\database\redis\ApiRedis';

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
        $row = $this->databaseModel->getOne($where);
        if(empty($row)){
            return array();
        }
        if($this->isFormat){
            $row = $this->dataModel->format($row,$this->isAlias);
        }

        return $row;
    }
}