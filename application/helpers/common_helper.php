<?php
if(!function_exists('makeTree'))
{
    /** 生成树形结构
     * @param array $list       原始数据
     * @param string $str       树形结构样式 如："<option value='\$id' \$selected>\$space \$name</option>"
     * @param int $parentId     一级项目ID
     * @param int $level        初始层级
     * @param array $icon       树形图标
     * @param string $nbsp      图标空格
     * @return string           树形结构字符串
     */
    function makeTree($list=array(), $str="<option value='\$id' \$selected>\$space \$name</option>", $parentId=0, $level=1, $treeIcons=array('&nbsp;┃','&nbsp;┣','&nbsp;┗'), $treeNbsp='&nbsp;'){
        $result='';
        if(empty($list)){
            return $result;
        }
        $array=getChildsAndLast($list,$parentId);
        $childs=$array['childs'];
        $last=$array['last'];
        $num=count($childs);
        if(empty($childs)){
            return $result;
        }
        $i=1;
        foreach ($childs as $child){
            $space='';
            for($j=1;$j<$level;$j++){
                if(1 == $j){
                    $space .=$treeNbsp;
                }else{
                    $space .=$treeIcons[0].$treeNbsp;
                }
            }
            if(1 != $level){
                if($i==$num){
                    $space.=$treeIcons[2];
                }else{
                    $space.=$treeIcons[1];
                }
            }
            @extract($child);
            eval("\$nstr = \"$str\";");
            $result .=$nstr;
            $result .=makeTree($last,$str,$child['id'],$level+1,$treeIcons,$treeNbsp);
            $i++;
        }
        return $result;
    }
}

if(!function_exists('getChildsAndLast')){
    /** 获取集合中的子元素
     * @param array $list       数据集合
     * @param int $parentId     上级ID
     * @return array            子元素集合
     */
    function getChildsAndLast($list, $parentId){
        $array=array();
        foreach ($list as $key=>$value){
            if($value['parentId'] == $parentId){
                $array[]=$value;
                unset($list[$key]);
            }
        }
        return array('childs'=>$array,'last'=>$list);
    }
}

if(!function_exists('batchInsertOrUpdateSql')){
    /** 批量 更新或插入数据的sql
     * @param string $table         数据表名
     * @param array $inserts        数据字段
     * @param array $values         原始数据
     * @param array|string $updates 更新字段
     * @return bool|array          返回false(条件不符)，返回array(sql语句)
     */
    function batchInsertOrUpdateSql($table='', $values=array(), $inserts=array(), $updates=array()){
        if(empty($table) || empty($inserts) || empty($values)){
            return false;
        }
        if(!empty($updates)){
            // 数据字段必须包含更新字段
            if(is_string($updates)){
                if(!in_array($updates,$inserts)){
                    return false;
                }
            }else{
                $commons= array_intersect($inserts,$updates);
                sort($commons);
                sort($updates);
                if($commons != $updates){
                    return false;
                }
            }
        }

        //数据字段
        $sql_inserts=array();
        foreach ($inserts as $insert){
            $sql_inserts[]='`'.$insert.'`';
        }
        $sql_inserts=implode(',',$sql_inserts);
        //数据分页
        $num=100;
        $page_values=array();
        foreach ($values as $k=>$value){
            $p=ceil(($k+1)/$num);
            $temp_values=array();
            foreach ($inserts as $insert){
                $temp=isset($value[$insert]) && !is_null($value[$insert])?(string)$value[$insert]:null;
                $temp_values[]="'".$temp."'";
            }
            $temp_values=implode(',',$temp_values);
            $page_values[$p][]='('.$temp_values.')';
        }
        if(!empty($updates)){
            //更新字段
            if(is_string($updates)){
                $sql_updates= ' `'.$updates.'` = values(`'.$updates.'`) ';
            }else{
                $sql_updates=array();
                foreach ($updates as $update){
                    $sql_updates[]= ' `'.$update.'` = values(`'.$update.'`) ';
                }
                $sql_updates=implode(',',$sql_updates);
            }
        }

        // 生成sql
        $sqls=array();
        foreach($page_values as $p=>$value){
            $sql_values=implode(',',$value);
            $sqls[$p]='insert into `'.$table.'` ('.$sql_inserts.') values '.$sql_values;
            if(!empty($updates)){
                $sqls[$p] .=' on duplicate key update '.$sql_updates;
            }
        }
        return $sqls;
    }
}


if(!function_exists('batchUpdateSql')){
    /** 虚拟表批量更新数据 sql
     * @param string $table         数据表名
     * @param array $inserts        数据字段
     * @param array $values         原始数据
     * @param array|string $updates  更新字段
     * @param array|string $wheres   条件字段
     * @return bool|string          返回false(条件不符)，返回string(sql语句)
     */
    function batchUpdateSql($table='', $values=array(), $inserts=array(), $updates=array(), $wheres='Id'){
        if(empty($table) || empty($inserts) || empty($values) || empty($updates) || empty($wheres)){
            return false;
        }
        // 数据字段必须包含更新字段
        if(is_string($updates)){
            if(!in_array($updates,$inserts)){
                return false;
            }
        }else{
            $commons= array_intersect($inserts,$updates);
            sort($commons);
            sort($updates);
            if($commons != $updates){
                return false;
            }
        }
        // 数据字段必须包含条件字段
        if(is_string($wheres)){
            if(!in_array($wheres,$inserts)){
                return false;
            }
        }else{
            $commons= array_intersect($inserts,$wheres);
            sort($commons);
            sort($wheres);
            if($commons != $wheres){
                return false;
            }
        }
        //数据字段
        $sql_inserts=array();
        foreach ($inserts as $insert){
            $sql_inserts[]='`'.$insert.'`';
        }
        $sql_inserts=implode(',',$sql_inserts);
        /* ++++++++++ 创建虚拟表 ++++++++++ */
        //创建虚拟表 表名
        $temp_table='`'.$table.'_temp`';
        //创建虚拟表 sql
        $sqls[]='create temporary table '.$temp_table.' as ( select '.$sql_inserts.' from `'.$table.'` where 1<>1 )';
        /* ++++++++++ 添加数据 ++++++++++ */
        //数据分页
        $num=100;
        $page_values=array();
        foreach ($values as $k=>$value){
            $p=ceil(($k+1)/$num);
            $temp_values=array();
            foreach ($inserts as $insert){
                $temp=isset($value[$insert]) && !is_null($value[$insert])?(string)$value[$insert]:null;
                $temp_values[]="'".$temp."'";
            }
            $temp_values=implode(',',$temp_values);
            $page_values[$p][]='('.$temp_values.')';
        }
        //插入数据 sql
        foreach($page_values as $p=>$value){
            $sql_values=implode(',',$value);
            $sqls[]='insert into '.$temp_table.' ('.$sql_inserts.') values '.$sql_values;
        }
        /* ++++++++++ 批量更新 ++++++++++ */
        //更新字段
        if(is_string($updates)){
            $sql_updates= '`'.$table.'`.`'.$updates.'`='.$temp_table.'.`'.$updates.'`';
        }else{
            $sql_updates=array();
            foreach ($updates as $update){
                $sql_updates[]= '`'.$table.'`.`'.$update.'`='.$temp_table.'.`'.$update.'`';
            }
            $sql_updates=implode(',',$sql_updates);
        }
        //条件字段
        if(is_string($wheres)){
            $sql_wheres= '`'.$table.'`.`'.$wheres.'`='.$temp_table.'.`'.$wheres.'`';
        }else{
            $sql_wheres=array();
            foreach ($wheres as $where){
                $sql_wheres[]= '`'.$table.'`.`'.$where.'`='.$temp_table.'.`'.$where.'`';
            }
            $sql_wheres=implode(' and ',$sql_wheres);
        }
        //更新数据 sql
        $sqls[]='update `'.$table.'`,'.$temp_table.' set '.$sql_updates.' where '.$sql_wheres;
        return $sqls;
    }
}


if(!function_exists('createGuid')){
    /** 生成GUID
     * @return string
     */
    function createGuid(){
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $guid = substr($charid, 6, 2).substr($charid, 4, 2).
            substr($charid, 2, 2).substr($charid, 0, 2).$hyphen
            .substr($charid, 10, 2).substr($charid, 8, 2).$hyphen
            .substr($charid,14, 2).substr($charid,12, 2).$hyphen
            .substr($charid,16, 4).$hyphen.substr($charid,20,12);
        return $guid;
    }
}

if(!function_exists('bigRMB')){
    /** 人民币大写
     * @param $ns
     * @return mixed
     */
    function bigRMB($ns) {
        static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
        $cnyunits=array("","圆","角","分"),
        $grees=array("","拾","佰","仟","万","拾","佰","仟","亿");
        @list($ns1,$ns2)=explode(".",$ns,2);
        $ns2=array_filter(array($ns2[1],$ns2[0])); //转为数组
        $arrayTemp=_cny_map_unit(str_split($ns1),$grees);
        $ret=array_merge($ns2,array(implode("",$arrayTemp),"")); //处理整数
        $arrayTemp=_cny_map_unit($ret,$cnyunits);
        $ret=implode("",array_reverse($arrayTemp)); 	//处理小数
        return str_replace(array_keys($cnums),$cnums,$ret);
    }
    function _cny_map_unit($list,$units) {
        $ul=count($units);
        $xs=array();
        foreach (array_reverse($list) as $x) {
            $l=count($xs);
            if ($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l)%$ul]);
            else $n=is_numeric(@$xs[0][0])?$x:'';
            array_unshift($xs,$n);
        }
        return $xs;
    }
}

if(!function_exists('httpsCurl')){
    /**  https 请求
     * @param string $url
     * @param string $data
     * @return bool|mixed
     */
    function httpsCurl($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        // 设置超时时间
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        if (curl_error($ch)) {
            $result=false;
        }
        curl_close($ch);

        return $result;
    }
}

if(!function_exists('httpCurl')){
    /**  http 请求
     * @param string $url
     * @param string $data
     * @return bool|mixed
     */
    function httpCurl($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch , CURLOPT_POST , 1);
        //设置超时时间
        curl_setopt($ch , CURLOPT_TIMEOUT , 3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch , CURLOPT_POSTFIELDS , $data);
        $result=curl_exec($ch);
        if(curl_error($ch)){
            $result=false;
        }
        curl_close($ch);
        return $result;
    }
}


if(!function_exists('compareVersion')){
    /**  比较两个版本号大小
     * @param string $curVersion  当前版本
     * @param string $minVersion  最低版本
     * @return int
     */
    function compareVersion($curVersion, $minVersion){
        $curVersion=explode('.',$curVersion);
        $minVersion=explode('.',$minVersion);
        $res=VALUE_LEFT_EQ_RIGHT;
        foreach($minVersion as $k=>$v){
            $res=bccomp($curVersion[$k],$v);
            if(VALUE_LEFT_EQ_RIGHT != $res){
                break;
            }
        }
        return $res;
    }
}

if(!function_exists('recordLog')){
    /** 记录文件日志
     * @param string $log
     * @param string $fileName
     */
    function recordLog($log, $fileName='debug')
    {
        $path=FCPATH.'logs/'.date('Y_m_d');
        if(!file_exists($path)){
            mkdir($path,DIR_WRITE_MODE,true);
        }
        $file=$path.'/'.$fileName.'_'.date('Y_m_d');
        file_put_contents($file,$log,FILE_APPEND);
    }
}

if(!function_exists('formatArray')){
    /** 格式化数组
     * @param array $array
     * @return array
     */
    function formatArray(array $array)
    {
        if(empty($array)){
            return $array;
        }
        $result=array();
        foreach($array as $key=>$val){
            // 格式化 键名
            if(is_numeric($key)){
                $key=(int)$key;
            }else{
                $key=(string)$key;
            }
            // 格式化 值
            if(is_array($val)){
                $val=formatArray($val);
            }
            elseif (is_numeric($val)){
                if(is_int($val)){
                    $val=(int)$val;
                }else{
                    $val=(double)$val;
                }
            }
            else{
                $val=(string)$val;
            }
            $result[$key]=$val;
        }
        return $result;
    }
}