<?php
namespace app\helpers;

class MysqlHelper
{
    /**
     * 处理字段
     *
     * @param array $fields
     * @return array
     */
    static public function handleFields(array $fields)
    {
        foreach($fields as & $field){
            $field = "`{$field}`";
        }

        return $fields;
    }

    /**
     * 获取插入部分的 sql
     *
     * @param $table
     * @param array $fields
     * @return string
     */
    static public function getInsertPartSql($table, array $fields)
    {
        $inserts = implode(',', static::handleFields($fields));

        return "INSERT INTO `{$table}` (".$inserts.") " ;
    }

    /**
     * 获取插入数值部分的 sql
     *
     * @param $fields
     * @param $row
     * @return string
     */
    static public function getValuesPartSql($fields, $row)
    {
        $values = [];
        foreach($fields as $field){
            $val = isset($row[$field]) ? $row[$field] : null;
            $values[] = is_string($val) ? "'".addslashes($val)."'" : $val;
        }

        return '(' .implode(',', $values). ')';
    }

    /**
     * 获取插入数据时更新部分的 sql
     *
     * @param array $updateFields
     * @param array $incrFields
     * @return string
     */
    static public function getUpdatePartSqlForInsert(array $updateFields = [], array $incrFields = [])
    {
        if(empty($updateFields) && empty($incrFields)){
            return '';
        }
        $updates = [];
        # 覆盖更新
        foreach($updateFields as $field){
            if(in_array($field, $incrFields)) continue;
            $updates[] = "`{$field}`=VALUES(`{$field}`)";
        }
        # 增量更新
        foreach($incrFields as $field){
            $updates[] = "`{$field}`=VALUES(`{$field}`)+`{$field}`";
        }

        return ' ON DUPLICATE KEY UPDATE ' . implode(',',$updates);
    }

    /**
     * 生成插入或更新的 sql
     *
     * @param $table
     * @param $row
     * @param array $updateFields 覆盖更新字段
     * @param array $incrFields 增量更新字段
     * @return string
     */
    static public function insertOrUpdateSql($table, array $row, array $updateFields = [], array $incrFields = [])
    {
        $insertFields = array_keys($row);
        $insertSql = static::getInsertPartSql($table, $insertFields);
        $valuesSql = static::getValuesPartSql($insertFields, $row);
        $updateSql = static::getUpdatePartSqlForInsert($updateFields, $incrFields);

        return $insertSql . ' VALUES ' . $valuesSql . $updateSql;
    }

    /**
     * 生成批量插入或更新的 sqls 列表
     *
     * @param $table
     * @param array $list
     * @param array $updateFields
     * @param array $incrFields
     * @param array $insertFields
     * @param int $num
     * @return array
     */
    static public function batchInsertOrUpdateSqls($table, array $list, array $updateFields = [], array $incrFields = [], array $insertFields = [], $num = 100)
    {
        # 插入数据字段
        if(empty($insertFields)){
            $insertFields = array_keys($list[0]);
        }
        $insertSql = static::getInsertPartSql($table, $insertFields) ;
        $updateSql = static::getUpdatePartSqlForInsert($updateFields, $incrFields);
        # 插入数据
        $values = [];
        $count = count($list);
        for($i = 0; $i < $count; $i ++){
            $page = ceil(($i + 1) / $num);
            $values[$page][] = static::getValuesPartSql($insertFields, $list[$i]);
        }
        # sqls
        $sqls = [];
        for($p = 1; $p <= $page; $p ++){
            $sqls[] = $insertSql . ' VALUES ' . implode(',',$values[$p]) . $updateSql;
        }

        return $sqls;
    }

    /**
     * 批量更新的 sqls 列表
     *
     * @param $table
     * @param array $list
     * @param array $updateFields
     * @param array $whereFields
     * @param array $insertFields
     * @param int $num
     * @return array
     */
    static public function batchUpdateSqls($table, array $list, array $updateFields, array $whereFields = ['id'], array $insertFields = [], $num = 100)
    {
        # 插入数据字段
        if(empty($insertFields)){
            $insertFields = array_keys($list[0]);
        }
        # 虚拟表
        $tempTable = $table.'_temp';
        $inserts = implode(',', static::handleFields($insertFields));
        $tableSql = "CREATE TEMPORARY TABLE `{$tempTable}` AS (SELECT {$inserts} FROM `{$table}` WHERE 1<>1 )";
        # 批量插入数据的 sqls
        $sqls = static::batchInsertOrUpdateSqls($tempTable,$list,[],[],$insertFields,$num);
        array_unshift($sqls, $tableSql);
        # 虚拟表更新
        $updates = [];
        foreach($updateFields as $field){
            $updates[] = "`$table`.`$field`=`$tempTable`.`$field`";
        }
        $updateSql = implode(',', $updates);
        $wheres = [];
        foreach($whereFields as $field){
            $wheres[] = "`$table`.`$field`=`$tempTable`.`$field`";
        }
        $whereSql = implode(' AND ', $wheres);
        $sqls[] = "UPDATE `{$table}`,`{$tempTable}` SET {$updateSql} WHERE {$whereSql}";

        return $sqls;
    }
}