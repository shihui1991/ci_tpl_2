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

    /** 获取分组列表
     * @return array
     */
    public function getGroupList()
    {
        $where=array();
        $select=array();
        $orderBy=array(
            'Group'=>ORDER_BY_ASC,
            'Sort'=>ORDER_BY_ASC,
            'Value'=>ORDER_BY_ASC,
            'Display'=>ORDER_BY_DESC,
        );
        $list=$this->databaseModel->getMany($where,$select,$orderBy);
        if(empty($list)){
            return array();
        }
        $list=new ListIterator($list);
        $groupList=array();
        $cateList=array();
        $groups=array();
        foreach($list as $row){
            $row=$this->dataModel->format($row);

            if(!in_array($row['Group'],$groups)){
                $groups[]=$row['Group'];
                $groupList[]=$row;
            }
            $cateList[$row['Group']][]=$row;
        }
        $result=array(
            'GroupList'=>$groupList,
            'CateList'=>$cateList,
        );

        return $result;
    }
}