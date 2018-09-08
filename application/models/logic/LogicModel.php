<?php
/**
 *  逻辑模型
 * @user 罗仕辉
 * @create 2018-09-07
 */

namespace models\logic;

class LogicModel
{
    public $DB;

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
            $count=$this->DB->getCount($where);
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
            $count=$this->DB->getCount($where);
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
            $count=$this->DB->getCount($where);
            if($count > 0){
                return false;
            }
        }
        return true;
    }
}