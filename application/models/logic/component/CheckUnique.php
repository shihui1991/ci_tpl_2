<?php
/**
 *  验证 唯一
 * @user 罗仕辉
 * @create 2018-09-09
 */

namespace models\logic\component;

trait CheckUnique
{
    
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

    /**   验证 Account 是否唯一
     * @param array $data
     * @return bool
     */
    public function checkAccountUnique(array $data)
    {
        if(!empty($data['Account'])){
            $where=array(
                array('Account','eq',$data['Account']),
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

    /**   验证 Token 是否唯一
     * @param array $data
     * @return bool
     */
    public function checkTokenUnique(array $data)
    {
        if(!empty($data['Token'])){
            $where=array(
                array('Token','eq',$data['Token']),
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