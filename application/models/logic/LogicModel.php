<?php
/**
 *  逻辑模型
 * @user 罗仕辉
 * @create 2018-09-07
 */

namespace models\logic;

use libraries\ListIterator;

class LogicModel
{
    public $databaseModel;
    public $dataModel;
    public $validatorModel;

    public function __construct()
    {

    }

    /**  获取实例
     * @return LogicModel
     */
    public static function instance()
    {
        return new static();
    }

    /** 获取全部
     * @return mixed
     */
    public function getAll()
    {
        $list=$this->databaseModel->getMany();
        if(empty($list)){
            return array();
        }
        $list=new ListIterator($list);
        $result=array();
        foreach($list as $row){
            $result[]=$this->dataModel->format($row);
        }

        return $result;
    }

    /** 通过 ID 获取数据
     * @param int $id
     * @return mixed
     */
    public function getRowById($id)
    {
        $row=$this->databaseModel->getOneByKey($id);
        if(empty($row)){
            return array();
        }
        $result=$this->dataModel->format($row);

        return $result;
    }


    /**  表单添加
     * @param array $input
     * @return mixed
     * @throws \Exception
     */
    public function add(array $input)
    {
        // 批量赋值
        $data=$this->dataModel->fill($input,'add');
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->columns,'add');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证字段唯一
        $this->checkUnique($data);
        // 新增
        $id = $this->databaseModel->insert($data);
        if(false === $id){
            throw new \Exception('保存失败',EXIT_DATABASE);
        }
        $data['Id']=$id;
        $result=$this->dataModel->format($data);

        return $result;
    }

    /** 表单修改
     * @param array $input
     * @return array
     * @throws \Exception
     */
    public function edit(array $input)
    {
        // 批量赋值
        $data=$this->dataModel->fill($input,'edit');
        // 验证模型 验证数据格式
        $vali=$this->validatorModel->validate($data,$this->dataModel->columns,'edit');
        if(true !== $vali){
            $err=array_shift($vali);
            throw new \Exception($err,EXIT_USER_INPUT);
        }
        // 验证字段唯一
        $this->checkUnique($data);
        $preRow=$this->databaseModel->getOneByKey($data['Id']);
        if(empty($preRow['Id'])){
            throw new \Exception('数据不存在',EXIT_USER_INPUT);
        }
        // 修改
        $result = $this->databaseModel->setOneByKey($data['Id'],$data);
        if(false === $result){
            throw new \Exception('保存失败',EXIT_DATABASE);
        }
        // 获取更新后数据
        $updated=array_merge($preRow,$data);
        $result=$this->dataModel->format($updated);

        return $result;
    }

    /**   验证 Name 是否唯一
     * @param array $data
     * @return bool
     */
    public function checkNameUnique(array $data)
    {
        if(!empty($data['Name'])){
            $where=array(
                array('Name','eq',$data['Name']),
            );
            if(!empty($data['Id'])){
                $where[]=array('Id','!=',$data['Id']);
            }
            $count=$this->databaseModel->getCount($where);
            if($count > 0){
                return false;
            }
        }
        return true;
    }

    /**   验证 Url 是否唯一
     * @param array $data
     * @return bool
     */
    public function checkUrlUnique(array $data)
    {
        if(!empty($data['Url'])){
            $where=array(
                array('Url','eq',$data['Url']),
            );
            if(!empty($data['Id'])){
                $where[]=array('Id','!=',$data['Id']);
            }
            $count=$this->databaseModel->getCount($where);
            if($count > 0){
                return false;
            }
        }
        return true;
    }

    /**   验证 UrlAlias 是否唯一
     * @param array $data
     * @return bool
     */
    public function checkUrlAliasUnique(array $data)
    {
        if(!empty($data['UrlAlias'])){
            $where=array(
                array('UrlAlias','eq',$data['UrlAlias']),
            );
            if(!empty($data['Id'])){
                $where[]=array('Id','!=',$data['Id']);
            }
            $count=$this->databaseModel->getCount($where);
            if($count > 0){
                return false;
            }
        }
        return true;
    }
}