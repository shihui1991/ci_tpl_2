<?php
namespace app\helpers;


class ArrHelper
{
    /**
     * 获取数组迭代接口
     *
     * @param array $array
     * @return \ArrayIterator
     */
    static public function getIterator(array $array)
    {
        return (new \ArrayObject($array))->getIterator();
    }

    /**
     * 获取数组所有下级及其他的分组列表
     *
     * @param array $list
     * @param int $parentId
     * @param string $pKey
     * @return array
     */
    static public function getChildGroup(array $list, $parentId = 0, $pKey = 'parent_id')
    {
        $group = [
            'children' => [],
            'others' => [],
        ];
        if(empty($list)){
            return $group;
        }
        foreach (static::getIterator($list) as $row){
            $row[$pKey] == $parentId ? $group['children'][] = $row : $group['others'][] = $row;
        }

        return $group;
    }

    /**
     * 检验元素是否包含于列表中
     *
     * @param $check
     * @param array $list
     * @return bool
     */
    static public function isInList($check, array $list)
    {
        if( ! is_array($check)){
            return in_array($check, $list);
        }
        $common = array_intersect($check, $list);
        sort($common);
        sort($check);

        return $common == $check;
    }

    /**
     * 数值格式化
     *
     * @param array $array
     * @return array
     */
    static public function formatNumeric(array $array)
    {
        if(empty($array)){
            return $array;
        }
        foreach($array as $key => & $val){
            $val = is_array($val) ? static::formatNumeric($val) : (is_numeric($val) ? (double)$val : $val);
        }

        return $array;
    }

    /**
     * 数组转换为 XML
     *
     * @param $array
     * @param string $root
     * @param null $xml
     * @return mixed
     */
    static public function arrayToSimpleXml($array, $root = '<root/>', $xml = null)
    {
        # 创建 xml 文档对象
        if(null === $xml){
            $xml = new \SimpleXMLElement($root);
        }
        # 迭代数组添加到xml 目录下
        foreach(static::getIterator($array) as $key => $value){
            if(is_array($value)){
                static::arrayToSimpleXml($value, $key, $xml->addChild($key));
            }else{
                $xml->addChild($key, $value);
            }
        }

        # 返回 xml
        return $xml->asXML();
    }

    /**
     * XML 转换为数组或对象
     *
     * @param $xml
     * @param bool $toArr
     * @return mixed|\SimpleXMLElement
     */
    static public function simpleXmlToArray($xml, $toArr = true)
    {
        # 禁止引用外部xml实体
        $disableLibxmlEntityLoader = libxml_disable_entity_loader(true);
        # 把 XML 字符串载入对象中
        $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_disable_entity_loader($disableLibxmlEntityLoader);
        # 转为数组
        if($toArr){
            return json_decode(json_encode($obj),true);
        }

        return $obj;
    }
}