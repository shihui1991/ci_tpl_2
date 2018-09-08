<?php
/**
 *  redis 数据库模型
 * @user 罗仕辉
 * @create 2018-09-07
 */

namespace models\database\redis;

use libraries\ListIterator;
use models\database\DatabaseModel;

class RedisModel extends DatabaseModel
{
    public $dbConfigFile='redis';       // 数据库配置文件

    public function __construct()
    {
        parent::__construct();

        // 加载配置
        $this->CI->load->config($this->dbConfigFile,TRUE);
        $configs=$this->CI->config->item($this->dbConfigFile);
        $this->dbConfig=$configs[$this->dbConfigName];
        // 连接数据库
        $this->dbModel = new \Redis();
        $this->dbModel->connect($this->dbConfig['hostname'], $this->dbConfig['port'], $this->dbConfig['timeout']);

        if($this->dbConfig['password'])
        {
            $this->dbModel->auth($this->dbConfig['password']);
        }
        $this->dbModel->select($this->db);
    }

    /** 执行一条原生命令
     * @param string $cmd
     * @param array $arguments
     * @return mixed
     */
    public function query($cmd, $arguments=array())
    {
        $query=$this->dbModel->rawCommand($cmd,$arguments);

        return $query;
    }

    /**  处理查询条件
     * @param array $wheres  查询条件
     * @param array $data        处理数据
     * @return bool|array
     */
    public function dealWhere(array $wheres,$data=array())
    {
        $result=true;
        if(empty($wheres)){
            return $result;
        }
        $array=array('>','>=','<','<=','!=');
        foreach($wheres as $where){
            if(is_array($where)){
                switch ($where[1]){
                    case 'eq':
                        $result=$data[$where[0]]==$where[2];
                        break;
                    case 'in':
                        $result=in_array($data[$where[0]],$where[2]);
                        break;
                    case 'not in':
                        $result=!in_array($data[$where[0]],$where[2]);
                        break;
                    case 'like':
                        $pos=strpos($data[$where[0]],$where[2]);
                        $result=is_numeric($pos);
                        break;
                    case 'not like':
                        $pos=strpos($data[$where[0]],$where[2]);
                        $result=is_bool($pos);
                        break;
                    default:
                        if(!in_array($where[1],$array)){
                            continue;
                        }
                        $str="{$data[$where[0]]} $where[1] $where[2]";
                        eval("\$result=$str;");
                }
            }else{
                eval("\$result=$where;");
            }

            if(false == $result){
                break;
            }
        }

        return $result;
    }

    /**  处理排序
     * @param array $orderBy
     */
    public function dealOrderBy(array $orderBy,array $list)
    {
        if(!empty($orderBy) && !empty($list)){
            $orderData=array();
            foreach($orderBy as $order=>$by){
                $orderData[]=array_column($list,$order);
                if('ASC' == strtoupper($by)){
                    $orderData[]=SORT_ASC;
                }else{
                    $orderData[]=SORT_DESC;
                }
            }
            $orderData[] = & $list;
            call_user_func_array('array_multisort',$orderData);
        }

        return $list;
    }

    /** 过滤字段
     * @param array $list
     * @param array $select
     * @return array
     */
    public function dealSelect(array $list, $select=array())
    {
        $result = $list;
        if(!empty($list) && !empty($select)){
            $result=array();
            $list=new ListIterator($list);
            foreach($list as $data){
                $array=array();
                foreach($select as $field){
                    $array[$field]=$data[$field];
                }
                $result[]=$array;
            }
        }

        return $result;
    }

    /**  获取列表
     * @param array $where
     * @param array $select
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param bool $distinct
     * @return array
     */
    public function getMany($where=array(), $select=array(), $orderBy=array(), $limit=0, $offset=0,$distinct=false)
    {
        if(!empty($this->table)){
            if(!empty($this->primaryKey)){
                $k=$this->table.':*';
            }else{
                $k=$this->table;
            }
        }else{
            $k='*';
        }
        $keys=$this->dbModel->keys($k);
        if(empty($keys)){
            return array();
        }
        sort($keys);
        $keys=new ListIterator($keys); // 使用迭代器

        $list=array();
        foreach($keys as $key){
            $data=$this->dbModel->hGetAll($key);

            // 查询条件
            if(!empty($where)){
                $result=$this->dealWhere($where,$data);
                if(false == $result){
                    continue;
                }
            }
            ksort($data);
            $list[]=$data;
        }
        if(empty($list)){
            return array();
        }
        // 排序
        $list=$this->dealOrderBy($orderBy,$list);
        // 过滤查询字段
        $list=$this->dealSelect($list,$select);
        // 唯一
        if($distinct){
            $list=array_values(array_unique($list));
        }
        // 截取位置
        if($offset){
            $list=array_slice($list,$offset);
        }
        // 限制条数
        if($limit > 0){
            $list=array_slice($list,0,$limit);
        }

        return $list;
    }

    /**  获取数据
     * @param array $where
     * @param array $select
     * @param array $orderBy
     * @param int $offset
     * @return array
     */
    public function getOne($where=array(), $select=array(), $orderBy=array(), $offset=0)
    {
        $list=$this->getMany($where, $select, $orderBy, 1, $offset);
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
        $list=$this->getMany($where);
        if(empty($list)){
            return 0;
        }
        $result=count($list);

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
        $list=array();
        foreach($keys as $key){
            if(!empty($this->table)){
                $key=$this->table.':'.$key;
            }
            $data=$this->dbModel->hGetAll($key);
            // 查询条件
            if(!empty($where)){
                $result=$this->dealWhere($where,$data);
                if(false == $result){
                    continue;
                }
            }
            $list[]=$data;
        }
        if(empty($list)){
            return array();
        }
        // 排序
        $list=$this->dealOrderBy($orderBy,$list);
        // 过滤查询字段
        $list=$this->dealSelect($list,$select);

        return $list;
    }

    /**  通过主键索引获取数据
     * @param mixed $key
     * @param array $select
     * @return array
     */
    public function getOneByKey($key, $select=array())
    {
        if(!empty($this->table)){
            $key=$this->table.':'.$key;
        }

        if(empty($select)){
            $result=$this->dbModel->hGetAll($key);
        }else{
            $result=$this->dbModel->hMGet($key,$select);
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
        if(!empty($this->table)){
            $key=$this->table.':'.$key;
        }
        $this->dbModel->hMSet($key,$data);

        return true;
    }

    /**  通过主键索引对多个字段值增减
     * @param mixed $key
     * @param array $list 字段值，field=>array(num,symbol,min,max)
     * @return mixed 成功返回最新值，失败返回false
     */
    public function incListByKey($key, array $list)
    {
        if(!empty($this->table)){
            $key=$this->table.':'.$key;
        }

        $temp=array();
        $result=array();
        foreach($list as $field=>$data){
            $num=$data['symbol'].abs($data['num']);
            $value=$this->dbModel->hIncrByFloat($key,$field,$num);
            $temp[$field]=$data;
            if($value < $data['min'] || $value > $data['max']){
                $result=false;
                break;
            }
            $result[$field]=$value;
        }
        // 失败回滚
        if(false === $result){
            foreach ($temp as $field=>$data){
                $num=$data['symbol'].abs($data['num']);
                $this->dbModel->hIncrByFloat($key,$field,-$num);
            }
        }else{
            $this->dbModel->hSet($key,'Updated',date('Y-m-d H:i:s'));
        }

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
        if(!empty($this->table)){
            $key=$this->table.':'.$key;
        }
        $num=$symbol.abs($num);
        $result=$this->dbModel->hIncrByFloat($key,$field,$num);
        if($result < $min || $result > $max){
            $result=false;
        }
        if(false === $result){
            $this->dbModel->hIncrByFloat($key,$field,-$num);
        }

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
        if(!empty($this->table)){
            foreach($keys as &$key){
                $key=$this->table.':'.$key;
            }
        }
        $result=$this->dbModel->del($keys);
        if(false == $result){
            return false;
        }
        $result=count($keys);

        return $result;
    }

    /**  获取主键值
     * @param array $data
     * @return mixed
     */
    public function getKey(array $data)
    {
        if(is_array($this->primaryKey)){
            $temp=array();
            foreach($this->primaryKey as $field){
                $temp[]=$data[$field];
            }
            $key=implode('_',$temp);
        }else{
            $key=$data[$this->primaryKey];
        }

        return $key;
    }

    /**  插入数据
     * @param array $data
     * @return mixed 成功返回 ID，失败返回 false
     */
    public function insert(array $data)
    {
        $table=empty($this->table)?'Id':$this->table;
        $id=$this->dbModel->hIncrBy('Id',$table,1);
        if($id < 1){
            return false;
        }
        $data['Id']=$id;
        // 获取主键值
        $key=$this->getKey($data);
        if(!empty($this->table)){
            $key=$this->table.':'.$key;
        }
        $this->dbModel->hMSet($key,$data);

        return (int)$id;
    }

    /**  批量插入
     * @param array $list
     * @return int 返回条数
     */
    public function batchInsert(array $list)
    {
        $result=0;
        $table=empty($this->table)?'Id':$this->table;
        foreach($list as $data){
            $id=$this->dbModel->hIncrBy('Id',$table,1);
            $data['Id']=$id;
            // 获取主键值
            $key=$this->getKey($data);
            if(!empty($this->table)){
                $key=$this->table.':'.$key;
            }
            $this->dbModel->hMSet($key,$data);
            $result++;
        }

        return $result;
    }

    /**  更新数据
     * @param array $data
     * @param array $where
     * @return bool 是否成功
     */
    public function update(array $data, $where=array())
    {
        $list=$this->getMany($where);
        if(empty($list)){
            return false;
        }

        $this->dbModel->multi();
        $list=new ListIterator($list);
        foreach($list as $preData){
            $key=$this->getKey($preData);
            if(!empty($this->table)){
                $key=$this->table.':'.$key;
            }

            $this->dbModel->hMset($key,$data);
        }
        $result=$this->dbModel->exec();

        return array_sum($result)>0;
    }

    /**  批量更新
     * @param array $list
     * @param array $whereFields
     * @param array $updateFields
     * @param array $insertFields
     * @return int  返回更新条数
     */
    public function batchUpdate(array $list, array $whereFields, array $updateFields, array $insertFields)
    {
        $result=0;
        foreach($list as $data){
            // 条件
            $where=array();
            foreach($whereFields as $field){
                $where[]=array($field,'eq',$data[$field]);
            }
            // 更新数据
            $update=array();
            foreach($updateFields as $field){
                $update[$field]=$data[$field];
            }

            $preList=$this->getMany($where);
            $preList=new ListIterator($preList);
            foreach($preList as $preData){
                $key=$this->getKey($preData);
                if(!empty($this->table)){
                    $key=$this->table.':'.$key;
                }

                $this->dbModel->hMset($key,$update);
                $result++;
            }
        }

        return $result;
    }

    /**  删除数据
     * @param array $where
     * @return int 返回条数
     */
    public function delete($where=array())
    {
        $list=$this->getMany(array(),$where);

        $keys=array();
        $list=new ListIterator($list);
        foreach($list as $data){
            $key=$this->getKey($data);
            if(!empty($this->table)){
                $key=$this->table.':'.$key;
            }

            $keys[]=$key;
        }
        $result=$this->dbModel->del($keys);

        return $result;
    }

    /**  清空数据
     * @return mixed
     */
    public function truncate()
    {
        if(!empty($this->table)){
            if(!empty($this->primaryKey)){
                $key=$this->table.':*';
            }else{
                $key=$this->table;
            }
        }else{
            $key='*';
        }

        $keys=$this->dbModel->keys($key);
        $result=$this->dbModel->del($keys);
        return $result;
    }
}