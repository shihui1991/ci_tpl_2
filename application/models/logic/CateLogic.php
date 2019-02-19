<?php
/**
 *  Cate 逻辑模型
 * @author 罗仕辉
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

        // redis 为主，mysql 备份
//        $this->databaseModel = CateRedis::instance();
//        $this->backDB = CateMysql::instance();
        // mysql 为主，redis 备份
        $this->databaseModel = CateMysql::instance();
//        $this->backDB = CateRedis::instance();
        $this->backDBStr = 'models\database\redis\CateRedis';

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
            'Constant'=>ORDER_BY_ASC,
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
            if($this->isFormat){
                $row=$this->dataModel->format($row,$this->isAlias);
            }

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

    /** 更新配置文件 config/common/conf.php
     * @return bool|int
     */
    public function updateConf()
    {
        $path=APPPATH.'config/common/';
        $file=$path.'conf.php';
        // 备份
        $backup='';
        if(file_exists($file)){
            $backup = $file.'.'.date('YmdHis',filemtime($file));
            exec('cp -a '.$file.' '.$backup);
        }
        // 获取配置
        $where=array();
        $select=array();
        $orderBy=array(
            'Group'=>ORDER_BY_ASC,
            'Sort'=>ORDER_BY_ASC,
            'Value'=>ORDER_BY_ASC,
            'Display'=>ORDER_BY_DESC,
            'Constant'=>ORDER_BY_ASC,
        );
        $list=$this->databaseModel->getMany($where,$select,$orderBy);
        // 文件头
        $str = <<<FFF
<?php
/**
 *  动态配置
 */

FFF;
        // 文件体
        if(!empty($list)){
            $list = new ListIterator($list);
            foreach($list as $row){
                $str .= <<<"FFF"

/* {$row['Group']} - {$row['Name']} */
defined('{$row['Constant']}')      OR define('{$row['Constant']}', '{$row['Value']}');

FFF;
            }
        }
        // 写入
        $result= file_put_contents($file,$str);
        if($backup){
            if(false === $result){
                // 失败回滚
                exec('mv -f '.$backup.' '.$file);
            }
            else{
                // 成功删除备份
//                exec('rm -rf '.$backup);
            }
        }

        return $result;
    }
}