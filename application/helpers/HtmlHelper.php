<?php
namespace app\helpers;

class HtmlHelper
{
    /**
     * 生成树形结构
     *
     * @param array $list
     * @param string $tpl
     * @param int $parentId
     * @param int $level
     * @param array $icons
     * @param string $nbsp
     * @param string $idKey
     * @param string $pKey
     * @return string
     */
    static public function makeTree(
        array $list,
        $tpl = "<option value='\$id' \$selected>\$space \$name</option>",
        $parentId = 0,
        $level = 1,
        $icons = ['&nbsp;┃','&nbsp;┣','&nbsp;┗'],
        $nbsp = '&nbsp;',
        $idKey = 'id',
        $pKey = 'parent_id'
    ){
        $tree = '';
        if(empty($list)){
            return $tree;
        }
        $group = ArrHelper::getChildGroup($list, $parentId, $pKey);
        $count = count($group['children']);
        if(0 == $count){
            return $tree;
        }
        $i = 1;
        foreach(ArrHelper::getIterator($group['children']) as $row){
            $space = '';
            for($j = 1; $j < $level; $j ++){
                $space .= (1 == $j) ? $nbsp : $icons[0].$nbsp;
            }
            if(1 != $level){
                $space .= ($i == $count) ? $icons[2] : $icons[1];
            }
            @ extract($row);
            eval("\$tree .= \"$tpl\";");
            $tree .= static::makeTree($group['others'], $tpl, $row[$idKey], $level + 1, $icons, $nbsp, $idKey, $pKey);
            $i ++ ;
        }

        return $tree;
    }
}