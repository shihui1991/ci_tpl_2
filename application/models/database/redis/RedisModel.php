<?php
/**
 *  redis 数据库模型
 * @author 罗仕辉
 * @create 2018-09-07
 */

namespace models\database\redis;


use models\database\DatabaseModel;

class RedisModel extends DatabaseModel
{
    public $dbConfigFile='redis';       // 数据库配置文件

    public function __construct()
    {
        parent::__construct();
    }

    /** 连接数据库
     * @return mixed|void
     */
    public function connect()
    {
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

    /** 建表
     * @param array $columns
     * @param bool $drop
     * @return mixed
     */
    public function createTable(array $columns, $drop=false)
    {
        if($drop){
            $this->truncate();
        }

        return true;
    }

    /** 删除表
     * @return bool
     */
    public function dropTable()
    {
        $this->truncate();

        $table=empty($this->table)?'Id':$this->table;
        $result = $this->dbModel->hDel('Id',$table);

        return $result;
    }

    /** 重置 ID
     * @param int $start
     * @return bool
     */
    public function resetId($start=0)
    {
        $table=empty($this->table)?'Id':$this->table;
        $this->dbModel->hSet('Id',$table,$start);

        return true;
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
                if( !isset($where[0]) || !isset($data[$where[0]])){
                    return false;
                }
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
                        $str="'{$data[$where[0]]}' {$where[1]} '{$where[2]}'";
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
     * @param array $list
     */
    public function dealOrderBy(array $orderBy,array $list=array())
    {
        if(!empty($list)){
            // 默认 Id 顺序排序
            $ids=array_column($list,'Id');
            if(!empty($ids) && count($ids) == count($list)){
                $orderData=array(
                    $ids,
                    SORT_ASC,
                    &$list,
                );
                call_user_func_array('array_multisort',$orderData);
            }
            // 条件排序
            if(!empty($orderBy)){
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
        if(!empty($list) && !empty($select)){
            foreach(makeArrayIterator($list) as $i => $row){
                $temp =array();
                foreach($select as $field){
                    $temp[$field] = isset($row[$field]) ? $row[$field] : null;
                }
                $list[$i] = $temp;
            }
        }

        return $list;
    }

    /** 获取 key 匹配式
     *
     * @param array $data
     * @return string
     */
    public function getKeyPattern($data = array())
    {
        $array = array();
        if(!empty($this->table)){
            $array[] = $this->table;
        }
        if(!empty($this->primaryKey)){
            if(is_array($this->primaryKey)){
                foreach($this->primaryKey as $field){
                    $array[] = isset($data[$field]) ? $data[$field] : '*';
                }
            }else{
                $array[] = isset($data[$this->primaryKey]) ? $data[$this->primaryKey] : '*';
            }
        }
        $pattern = implode(':',$array);

        return $pattern;
    }

    /** 获取全部键名
     * @param string $pattern
     * @param array $data
     * @return array
     */
    public function getAllKeys($pattern = '', $data = array())
    {
        if(empty($pattern)){
            $pattern = $this->getKeyPattern($data);
        }
        $keys = array();
        $index = null;
        do {
            $temp = $this->dbModel->scan($index,$pattern,1000);
            $keys = array_merge($keys,$temp);
        } while ($index > 0);
        sort($keys);

        return $keys;
    }

    /** 处理列表
     * @param array $list
     * @param array $select
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param bool $distinct
     * @return array
     */
    public function dealList($list=array(), $select=array(), $orderBy=array(), $limit=0, $offset=0, $distinct=false)
    {
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
        $keys = $this->getAllKeys();

        $list=array();
        foreach(makeArrayIterator($keys) as $key){
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
        $result = $this->dealList($list,$select,$orderBy,$limit,$offset,$distinct);

        return $result;
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
            $key = $this->getRedisKey($key);
            $data=$this->dbModel->hGetAll($key);
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

        return $list;
    }

    /**  通过主键索引获取数据
     * @param mixed $key
     * @param array $select
     * @return array
     */
    public function getOneByKey($key, $select=array())
    {
        $key = $this->getRedisKey($key);
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
        $key = $this->getRedisKey($key);
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
        $key = $this->getRedisKey($key);
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
        $key = $this->getRedisKey($key);
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

    /** 字段增量
     * @param array $where
     * @param string $field
     * @param number $num
     * @param number $min
     * @param number $max
     * @return bool|float
     */
    public function incFieldByWhere($where, $field, $num, $symbol='+', $min=0, $max=INF)
    {
        $list = $this->getMany($where);
        foreach(makeArrayIterator($list) as $row){
            $key = $this->getKey($row);
            $result = $this->incFieldByKey($key,$field,$num,$symbol,$min,$max);
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
        $redisKeys = array();
        foreach(makeArrayIterator($keys) as $key){
            $redisKeys[] = $this->getRedisKey($key);
        }
        $result=$this->dbModel->del($redisKeys);
        if(false == $result){
            return false;
        }
        $result=count($redisKeys);

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
            $key=implode(':',$temp);
        }else{
            $key=$data[$this->primaryKey];
        }

        return $key;
    }

    /** 获取redis key
     * @param string $key
     * @param array $data
     * @return mixed|string
     */
    public function getRedisKey($key='', $data=array())
    {
        if(0 == strlen($key) && !empty($data)){
            $key = $this->getKey($data);
        }
        if(!empty($this->table)){
            if(!empty($this->primaryKey)){
                $key=$this->table.':'.$key;
            }else{
                $key=$this->table;
            }
        }

        return $key;
    }

    /** 更新 ID
     * @param int $id
     * @return bool|int
     */
    public function updateId($id=0)
    {
        $table=empty($this->table)?'Id':$this->table;
        if(empty($id)){
            $id=$this->dbModel->hIncrBy('Id',$table,1);
            if($id < 1){
                return false;
            }
        }else{
            $curId=$this->dbModel->hGet('Id',$table);
            if($id > $curId){
                $this->dbModel->hSet('Id',$table,$id);
            }
        }

        return $id;
    }

    /**  插入数据
     * @param array $data
     * @return mixed 成功返回 ID，失败返回 false
     */
    public function insert(array $data)
    {
        // 处理 ID
        $id=0;
        if(!empty($data['Id'])){
            $id=$data['Id'];
        }
        $id = $this->updateId($id);
        if(false === $id){
            return false;
        }
        $data['Id']=$id;

        // 获取主键值
        $key = $this->getRedisKey('',$data);
        $this->dbModel->hMSet($key,$data);

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
        foreach(makeArrayIterator($list) as $data){
            $row=array();
            if(empty($fields)){
                $row=$data;
            }else{
                foreach($fields as $field){
                    $value=isset($data[$field])?$data[$field]:null;
                    $row[$field]=$value;
                }
            }
            $id = $this->insert($row);
            if(false === $id){
                continue;
            }
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
        foreach(makeArrayIterator($list) as $preData){
            $key = $this->getRedisKey('',$preData);
            $this->dbModel->hMset($key,$data);
        }
        $result=$this->dbModel->exec();

        return array_sum($result)>0;
    }

    /**  批量更新
     * @param array $list
     * @param array $whereFields
     * @param array $updateFields
     * @return int  返回更新条数
     */
    public function batchUpdate(array $list, array $whereFields, array $updateFields)
    {
        $result=0;
        foreach(makeArrayIterator($list) as $data){
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

            $result += $this->update($update,$where);
        }

        return $result;
    }

    /**  删除数据
     * @param array $where
     * @return int 返回条数
     */
    public function delete($where=array())
    {
        $list=$this->getMany($where);
        if(empty($list)){
            return 0;
        }

        $keys=array();
        foreach(makeArrayIterator($list) as $data){
            $key = $this->getRedisKey('',$data);
            $keys[]=$key;
        }
        if(empty($keys)){
            return true;
        }
        $result=$this->dbModel->del($keys);

        return $result;
    }

    /**  清空数据
     * @return mixed
     */
    public function truncate()
    {
        $keys=$this->getAllKeys();
        if(empty($keys)){
            return true;
        }
        $result=$this->dbModel->del($keys);

        return $result;
    }
}