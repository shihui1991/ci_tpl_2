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
}