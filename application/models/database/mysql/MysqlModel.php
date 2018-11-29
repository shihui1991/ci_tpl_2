<?php
/**
 *  mysql 数据库模型
 * @user 罗仕辉
 * @create 2018-09-08
 */

namespace models\database\mysql;

use models\database\DatabaseModel;

class MysqlModel extends DatabaseModel
{
    public $dbConfigFile='database';       // 数据库配置文件

    public function __construct()
    {
        parent::__construct();

        // 连接数据库
        $this->dbModel=$this->CI->load->database($this->dbConfigName,true);
        $this->dbConfig = json_decode(json_encode($this->dbModel),true);
    }

    /** 执行一条原生命令
     * @param string $sql
     * @param array $arguments
     * @return mixed
     */
    public function query($sql, $arguments=array())
    {
        $query=$this->dbModel->query($sql,$arguments);

        return $query;
    }

    /** 建表
     * @param array $columns
     * @param bool $drop
     * @return mixed
     */
    public function createTable(array $columns,$drop=false)
    {
        // 设置字符集
        $sql = 'SET NAMES utf8mb4';
        $this->query($sql);
        // 删除表
        if($drop){
            $sql = "DROP TABLE IF EXISTS `{$this->table}`";
            $this->query($sql);
            $check='';
        }
        else{
            $check=' IF NOT EXISTS ';
        }

        // 设置字符集
        $sql = "SET character_set_client = utf8mb4 ;";
        $this->query($sql);
        // 建表
        $sql = "CREATE TABLE $check `{$this->table}` (";

        $fields=array();
        foreach($columns as $field=>$column){
            $fields[]="`$field` {$column['desc']}";
        }
        $sql .= implode(',',$fields);
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='{$this->table}';";

        $result = $this->query($sql);

        return $result;
    }

    /** 删除表
     * @return mixed
     */
    public function dropTable()
    {
        $sql = "DROP TABLE IF EXISTS `{$this->table}`";
        $result = $this->query($sql);

        return $result;
    }

    /** 重置 ID
     * @param int $start
     * @return mixed
     */
    public function resetId($start=1)
    {
        $sql='ALTER TABLE `'.$this->db.'`.`'.$this->table.'` AUTO_INCREMENT = '.$start;
        $result=$this->query($sql);

        return $result;
    }

    /**  处理查询条件
     * @param array $wheres  查询条件
     * @return bool
     */
    public function dealWhere(array $wheres)
    {
        if(empty($wheres)){
            return true;
        }
        $array=array('>','>=','<','<=','!=');
        foreach($wheres as $where){
            if(is_array($where)){
                switch ($where[1]){
                    case 'eq':
                        $this->dbModel->where($where[0],$where[2]);
                        break;
                    case 'in':
                        $this->dbModel->where_in($where[0],$where[2]);
                        break;
                    case 'not in':
                        $this->dbModel->where_not_in($where[0],$where[2]);
                        break;
                    case 'like':
                        $this->dbModel->like($where[0],$where[2]);
                        break;
                    case 'not like':
                        $this->dbModel->not_like($where[0],$where[2]);
                        break;
                    default:
                        if(!in_array($where[1],$array)){
                            continue;
                        }
                        $this->dbModel->where($where[0].' '.$where[1],$where[2]);
                }
            }else{
                $this->dbModel->where($where);
            }
        }
        return true;
    }

    /** 处理排序
     * @param array $orderBy
     * @return bool
     */
    public function dealOrderBy(array $orderBy)
    {
        if(empty($orderBy)){
            return true;
        }
        foreach($orderBy as $order=>$by){
            $this->dbModel->order_by($order,$by);
        }

        return true;
    }

    /** 过滤字段
     * @param array$select
     * @return bool
     */
    public function dealSelect(array $select)
    {
        if(empty($select)){
            return true;
        }
        $this->dbModel->select($select);

        return true;
    }

    /** 获取列表
     * @param array $where
     * @param array $select
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param bool $distinct
     * @return array
     */
    public function getMany($where=array(), $select=array(), $orderBy=array(), $limit=0, $offset=0, $distinct=false)
    {
        if(!empty($where)){
            $this->dealWhere($where);
        }
        if(!empty($select)){
            $this->dealSelect($select);
        }
        if(!empty($orderBy)){
            $this->dealOrderBy($orderBy);
        }
        if($limit > 0){
            $this->dbModel->limit($limit , $offset);
        }
        if(true == $distinct){
            $this->dbModel->distinct();
        }

        $this->dbModel->from($this->table);
        $query=$this->dbModel->get();

        if(false === $query){
            return array();
        }
        $list=$query->result_array();
        if(empty($list)){
            return array();
        }

        return $list;
    }

    /** 获取数据
     * @param array $where
     * @param array $select
     * @param array $orderBy
     * @param int $offset
     * @return array|mixed
     */
    public function getOne($where=array(), $select=array(), $orderBy=array(), $offset=0)
    {
        $list = $this->getMany($where,$select,$orderBy,1,$offset);
        if(empty($list)){
            return array();
        }
        $result=$list[0];

        return $result;
    }

    /**  计数
     * @param mixed $where
     * @return int
     */
    public function getCount($where=array())
    {
        if(!empty($where)){
            $this->dealWhere($where);
        }
        $this->dbModel->from($this->table);
        $query=$this->dbModel->count_all_results();
        if(false === $query){
            return 0;
        }
        $result=(int)$query;

        return $result;
    }

    /**  通过主键索引获取列表
     * @param array $keys
     * @param array $select
     * @param array $orderBy
     * @return array
     */
    public function getListByKeys(array $keys, $select=array(), $orderBy=array())
    {
        if(empty($keys)){
            return array();
        }
        $this->dbModel->where_in($this->primaryKey,$keys);
        if(!empty($select)){
            $this->dealSelect($select);
        }
        if(!empty($orderBy)){
            $this->dealOrderBy($orderBy);
        }
        $this->dbModel->from($this->table);
        $query=$this->dbModel->get();

        $result=array();
        if(false === $query){
            return array();
        }
        $list=$query->result_array();
        if(empty($list)){
            return array();
        }

        return $list;
    }

    /**  通过主键索引获取数据
     * @param mixed $key
     * @param array $select
     * @return array
     */
    public function getOneByKey($key, $select=array())
    {
        $this->dbModel->where($this->primaryKey,$key);
        if(!empty($select)){
            $this->dealSelect($select);
        }
        $this->dbModel->from($this->table);
        $this->dbModel->limit( 1 );
        $query=$this->dbModel->get();

        if(false === $query){
            return array();
        }
        $result=$query->row_array();
        if(empty($result)){
            return array();
        }

        return $result;
    }

    /**  通过主键索引更新数据
     * @param mixed $key
     * @param array $data
     * @return bool
     */
    public function setOneByKey($key, array $data)
    {
        $this->dbModel->where($this->primaryKey,$key);
        $result=$this->dbModel->update($this->table,$data);

        return $result;
    }

    /**  通过主键索引对多个字段值增减
     * @param mixed $key
     * @param array $list 字段值，field=>array(num,symbol,min,max)
     * @return mixed 成功返回最新值，失败返回false
     */
    public function incListByKey($key, array $list)
    {
        // 更新 开启事务
        $this->dbModel->trans_begin();
        $fields=array();
        foreach($list as $field=>$data){
            $fields[]=$field;
            $value=abs($data['num']);
            $this->dbModel->set("`$field`","`$field` {$data['symbol']} $value",false);
        }
        $this->dbModel->where($this->primaryKey,$key);
        $this->dbModel->update($this->table);
        // 获取最新值
        $row=$this->getOneByKey($key,$fields);
        $result=array();
        foreach($row as $field=>$value){
            // 更新后新值超过限制
            if($value < $list[$field]['min'] || $value > $list[$field]['max']){
                $this->dbModel->trans_rollback();
                $result=false;
                break;
            }
            $result[$field]=(float)$value;
        }
        if(false === $result){
            return false;
        }
        $this->dbModel->trans_commit();

        return $result;
    }

    /**  通过主键索引对字段值增减
     * @param string $key
     * @param string $field
     * @param number $num
     * @param string $symbol
     * @param number $min
     * @param number $max
     * @return mixed  成功返回最新值，失败返回false
     */
    public function incFieldByKey($key, $field, $num=1, $symbol='+', $min=0, $max=INF)
    {
        // 更新 开启事务
        $this->dbModel->trans_begin();
        $num=abs($num);
        $this->dbModel->set("`$field`","`$field` $symbol $num",false);
        $this->dbModel->where($this->primaryKey,$key);
        $this->dbModel->update($this->table);
        // 获取最新值
        $row=$this->getOneByKey($key,array($field));
        $result=(float)$row[$field];
        // 更新后新值超过限制
        if($row[$field] < $min || $row[$field] > $max){
            $this->dbModel->trans_rollback();
            $result=false;
        }
        if(false === $result){
            return false;
        }
        $this->dbModel->trans_commit();

        return $result;
    }

    /**  通过主键索引删除数据
     * @param array $keys
     * @return mixed 成功返回条数，失败返回false
     */
    public function deleteByKeys(array $keys)
    {
        if(empty($keys)){
            return false;
        }
        $this->dbModel->where_in($this->primaryKey,$keys);
        $this->dbModel->delete($this->table);
        $result=$this->dbModel->affected_rows();
        if(false == $result){
            return false;
        }
        $result=(int)$result;

        return $result;
    }

    /**  获取主键值
     * @param array $data
     * @return mixed
     */
    public function getKey(array $data)
    {
        $key=$data[$this->primaryKey];
        return $key;
    }

    /**  插入数据
     * @param array $data
     * @return mixed 成功返回 ID，失败返回 false
     */
    public function insert(array $data)
    {
        $result=$this->dbModel->insert($this->table,$data);
        if(false == $result){
            return false;
        }
        $id = $this->dbModel->insert_id();

        return (int)$id;
    }

    /**  批量插入或更新
     * @param array $list
     * @param array $fields
     * @return int 返回条数
     */
    public function batchInsertUpdate(array $list,array $fields=array())
    {
        $result=0;
        if(empty($fields)){
            $fields=array_keys($list[0]);
        }
        $sqls=batchInsertOrUpdateSql($this->table, $list, $fields, $fields);
        if(false === $sqls){
            return 0;
        }
        foreach($sqls as $sql){
            $this->query($sql);
            $result += $this->dbModel->affected_rows();
        }

        return (int)$result;
    }

    /**  更新数据
     * @param array $data
     * @param array $where
     * @return bool 是否成功
     */
    public function update(array $data, $where=array())
    {
        if(!empty($where)){
            $this->dealWhere($where);
        }
        $result=$this->dbModel->update($this->table,$data);

        return $result;
    }

    /**  批量更新
     * @param array $list
     * @param array $whereFields
     * @param array $updateFields
     * @return int  返回更新条数
     */
    public function batchUpdate(array $list, array $whereFields, array $updateFields)
    {
        if(empty($list)){
            return 0;
        }
        $insertFields=array_keys($list[0]);
        $result=0;
        $sqls=batchUpdateSql($this->table, $list, $insertFields, $updateFields, $whereFields);
        if(false === $sqls){
            return 0;
        }
        foreach($sqls as $i=>$sql){
            $this->query($sql);
            if($i > 1){
                $result += $this->dbModel->affected_rows();
            }
        }

        return (int)$result;
    }

    /**  删除数据
     * @param array $where
     * @return int 返回条数
     */
    public function delete($where=array())
    {
        if(!empty($where)){
            $this->dealWhere($where);
        }
        $result=$this->dbModel->delete($this->table);
        return $result;
    }

    /**  清空数据
     * @return mixed
     */
    public function truncate()
    {
        $result=$this->dbModel->truncate($this->table);
        return $result;
    }
}