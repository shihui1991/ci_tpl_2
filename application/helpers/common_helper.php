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
        $childs = makeArrayIterator($childs);
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
        $list = makeArrayIterator($list);
        foreach ($list as $key=>$value){
            if($value['parentId'] == $parentId){
                $array[]=$value;
                unset($list[$key]);
            }
        }
        return array('childs'=>$array,'last'=>$list);
    }
}

if(!function_exists('insertOrUpdateSql')){

    /** 生成插入或更新数据的sql
     * @param string $table
     * @param array $data
     * @param string|array $updateFields 覆盖更新的字段
     * @param string|array $incrFields   增量更新的字段
     * @return bool|string
     */
    function insertOrUpdateSql($table, array $data, $updateFields = array(), $incrFields = array())
    {
        if(0 == count($data)){
            return false;
        }
        $sql = "insert into `$table` set ";
        # 数据字段
        foreach($data as $key => $val){
            $val = addslashes($val);
            $sql .= " `$key` = '$val',";
        }
        $sql = rtrim($sql,', ');
        # 更新字段
        if(empty($updateFields) && empty($incrFields)){
            return $sql;
        }
        $sql .= ' on duplicate key update ';
        $updateArr = array();
        # 覆盖更新的字段
        if(!empty($updateFields)){
            if(!is_array($updateFields)){
                $updateFields = array($updateFields);
            }
            foreach($updateFields as $field){
                $updateArr[] = " `$field` = values(`$field`) ";
            }
        }
        # 增量更新字段
        if(!empty($incrFields)){
            if(!is_array($incrFields)){
                $incrFields = array($incrFields);
            }
            foreach($incrFields as $field){
                $updateArr[] = " `{$field}` = `{$field}` + values(`{$field}`) ";
            }
        }
        $sql .= implode(',',$updateArr);

        return $sql;
    }
}

if(!function_exists('batchInsertOrUpdateSql')){
    /** 批量 更新或插入数据的sql
     * @param string $table         数据表名
     * @param array $inserts        数据字段
     * @param array $values         原始数据
     * @param array|string $updates 覆盖更新字段
     * @param array|string $incrs   增量更新字段
     * @return bool|array          返回false(条件不符)，返回array(sql语句)
     */
    function batchInsertOrUpdateSql($table='', $values=array(), $inserts=array(), $updates=array(), $incrs=array()){
        if(empty($table) || empty($inserts) || empty($values)){
            return false;
        }
        if(!empty($updates)){
            // 数据字段必须包含覆盖更新字段
            $checked = checkLieInList($updates, $inserts);
            if(false == $checked){
                return false;
            }
        }
        if(!empty($incrs)){
            // 数据字段必须包含增量更新字段
            $checked = checkLieInList($incrs, $inserts);
            if(false == $checked){
                return false;
            }
        }

        //数据字段
        $sql_inserts=array();
        foreach ($inserts as $insert){
            $sql_inserts[]=" `$insert` ";
        }
        $sql_inserts=implode(',',$sql_inserts);
        //数据分页
        $num=100;
        $page_values=array();
        $values=array_values($values);
        $count = count($values);
        for($i=0;$i<$count;$i++){
            $p=ceil(($i+1)/$num);
            $temp_values=array();
            foreach ($inserts as $insert){
                $temp=isset($values[$i][$insert]) && !is_null($values[$i][$insert])?(string)$values[$i][$insert]:null;
                $temp = addslashes($temp);
                $temp_values[]=" '$temp' ";
            }
            $temp_values=implode(',',$temp_values);
            $page_values[$p][]=" ($temp_values) ";
        }
        $updateSql = '';
        if(!empty($updates) || !empty($incrs)){
            $updateSql = ' on duplicate key update ';
        }
        $sql_updates=array();
        // 覆盖更新的字段
        if(!empty($updates)){
            if(is_string($updates)){
                $sql_updates[] = " `$updates` = values(`$updates`) ";
            }else{
                foreach ($updates as $update){
                    $sql_updates[] = " `$update` = values(`$update`) ";
                }
            }
        }
        // 增量更新字段
        if(!empty($incrs)){
            if(is_string($incrs)){
                $sql_updates[]= " `{$incrs}` = `{$incrs}` + values(`{$incrs}`) ";
            }else{
                foreach ($incrs as $incr){
                    $sql_updates[]= " `{$incr}` = `{$incr}` + values(`{$incr}`) ";
                }
            }
        }
        $updateSql .= implode(',',$sql_updates);

        // 生成sql
        $sqls=array();
        for($i=0;$i<$p;$i++){
            $sql_values=implode(',',$page_values[$i+1]);
            $sqls[$i]="insert into `$table` ($sql_inserts) values $sql_values $updateSql";
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
        $checked = checkLieInList($updates, $inserts);
        if(false == $checked){
            return false;
        }
        // 数据字段必须包含条件字段
        $checked = checkLieInList($wheres, $inserts);
        if(false == $checked){
            return false;
        }

        //数据字段
        $sql_inserts=array();
        foreach ($inserts as $insert){
            $sql_inserts[]=" $insert ";
        }
        $sql_inserts=implode(',',$sql_inserts);
        /* ++++++++++ 创建虚拟表 ++++++++++ */
        //创建虚拟表 表名
        $temp_table=" `{$table}_temp` ";
        //创建虚拟表 sql
        $sqls[] = " create temporary table $temp_table as ( select $sql_inserts from `$table` where 1<>1 ) ";
        /* ++++++++++ 添加数据 ++++++++++ */
        //数据分页
        $num=100;
        $page_values=array();
        $values=array_values($values);
        $count = count($values);
        for($i=0;$i<$count;$i++){
            $p=ceil(($i+1)/$num);
            $temp_values=array();
            foreach ($inserts as $insert){
                $temp=isset($values[$i][$insert]) && !is_null($values[$i][$insert])?(string)$values[$i][$insert]:null;
                $temp = addslashes($temp);
                $temp_values[]=" '$temp' ";
            }
            $temp_values=implode(',',$temp_values);
            $page_values[$p][]=" ($temp_values) ";
        }
        //插入数据 sql
        for($i=0;$i<$p;$i++){
            $sql_values=implode(',',$page_values[$i+1]);
            $sqls[]=" insert into $temp_table ($sql_inserts) values $sql_values ";
        }
        /* ++++++++++ 批量更新 ++++++++++ */
        //更新字段
        if(is_string($updates)){
            $sql_updates= " `$table`.`$updates` = `$temp_table`.`$updates` ";
        }else{
            $sql_updates=array();
            foreach ($updates as $update){
                $sql_updates[] = " `$table`.`$update` = `$temp_table`.`$update` ";
            }
            $sql_updates=implode(',',$sql_updates);
        }
        //条件字段
        if(is_string($wheres)){
            $sql_wheres= " `$table`.`$wheres` = `$temp_table`.`$wheres` ";
        }else{
            $sql_wheres=array();
            foreach ($wheres as $where){
                $sql_wheres[] = " `$table`.`$where` = `$temp_table`.`$where` ";
            }
            $sql_wheres=implode(' and ',$sql_wheres);
        }
        //更新数据 sql
        $sqls[]="update `$table` , $temp_table set $sql_updates where $sql_wheres ";
        return $sqls;
    }
}

if(!function_exists('checkLieInList')){

    /** 检查元素是否包含于列表中
     * @param mixed $check
     * @param array $list
     * @return bool
     */
    function checkLieInList($check, array $list)
    {
        if(is_string($check)){
            if(!in_array($check,$list)){
                return false;
            }
        }else{
            $commons= array_intersect($check,$list);
            sort($commons);
            sort($check);
            if($commons != $check){
                return false;
            }
        }

        return true;
    }
}

if(!function_exists('createGuid')){
    /** 生成GUID
     * @return string
     */
    function createGuid(){
        $charid = makeUniqueStr(true);
        $hyphen = chr(45);// "-"
        $guid = substr($charid, 6, 2).substr($charid, 4, 2).
            substr($charid, 2, 2).substr($charid, 0, 2).$hyphen
            .substr($charid, 10, 2).substr($charid, 8, 2).$hyphen
            .substr($charid,14, 2).substr($charid,12, 2).$hyphen
            .substr($charid,16, 4).$hyphen.substr($charid,20,12);
        return $guid;
    }
}

if(!function_exists('makeUniqueStr')){
    /** 生成时间随机字符串
     * @return string
     */
    function makeUniqueStr($upper=false){
        $guidStr = md5(uniqid(mt_rand(), true));
        if($upper){
            $guidStr = strtoupper($guidStr);
        }
        return $guidStr;
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

if(!function_exists('curlHttp')){
    /** curl http/https 请求
     * @param string $url
     * @param string|array $data
     * @param bool $isPost
     * @param int $execTimes
     * @return mixed|string
     */
    function curlHttp($url, $data = '', $isPost = true, $execTimes = 1)
    {
        # 检测是不是 https
        $ssl = false;
        $http = parse_url($url,PHP_URL_SCHEME);
        if('https' == $http){
            $ssl = true;
        }
        # 检测 url 中是否已存在参数
        $mark = strpos($url,'?');
        # 将参数转为请求字符串
        if(is_array($data)){
            $data = http_build_query($data);
        }
        # 处理 GET 请求的参数
        if(false == $isPost){
            $conn = '&';
            if(false === $mark){
                $conn = '?';
            }
            $url .= $conn . $data;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if($ssl){
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        if($isPost) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        # 特殊接口可能会多次请求才能得到正确返回结果，例：微信APP支付
        for($i = 0 ; $i < $execTimes ; $i++){
            $res = curl_exec($ch);
        }
        if (curl_errno($ch)) {
            $res = curl_error($ch);
        }
        curl_close($ch);

        return $res;
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
     * @param string $dir
     */
    function recordLog($log, $fileName='', $dir='debug')
    {
        $path=APPPATH.'logs/'.date('Y_m_d').'/'.$dir;
        if(!file_exists($path)){
            mkdir($path,DIR_WRITE_MODE,true);
        }
        $file=$path.'/'.$fileName.'_'.date('Y_m_d');
        file_put_contents($file,$log,FILE_APPEND);
    }
}

if(!function_exists('debugLog')){
    /** 记录调试信息日志
     * @param string $log
     * @param string $fileName
     * @param string $dir
     */
    function debugLog($file, $line, $desc, $info, $fileName='', $dir='debug')
    {
        $log="[".date('Y-m-d H:i:s')."][$file][$line][$desc]$info \r\n";
        recordLog($log,$fileName,$dir);
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

            $result[$key]=$val;
        }
        return $result;
    }
}

if(!function_exists('getDirAllDirOrFile')){
    /** 获取目录下全部目录及文件
     * @param string $dir
     * @return mixed
     */
    function getDirAllDirOrFile($dir='.')
    {
        // 验证目录有效性
        if(false == ($realDir = realpath($dir))){
            return array();
        }
        if(false == is_dir($realDir)){
            return array();
        }
        // 验证打开目录函数是否可用
        if(!function_usable('readdir')){
            return array();
        }
        // 打开目录
        if(false == ($handle = opendir($realDir))){
            return array();
        }
        // 遍历目录
        $result=array();
        clearstatcache(); // 清理文件信息缓存
        while (false !== ($file = readdir($handle))){
            if(in_array($file,array('.','..'))){
                continue;
            }
            $path = $dir.'/'.$file;
            $realPath=$realDir.'/'.$file;
            $isDir=is_dir($path);
            $size=makeFileSize(filesize($realPath));
            $key = str_replace('/','_',$realPath);
            $result[$key]=array(
                'File'=>$file,
                'IsDir'=>(int)$isDir,
                'Dir'=>$dir,
                'Path'=>$path,
                'RealDir'=>$realDir,
                'RealPath'=>$realPath,
                'Size'=>$size,
                'Updated'=>date('Y-m-d H:i:s',filemtime($realPath)),
            );

            if($isDir){
                $array = getDirAllDirOrFile($path);
                $result = array_merge($result,$array);
            }
        }
        // 关闭目录
        closedir($handle);
        ksort($result);

        return $result;
    }
}

if(!function_exists('getDirAllFile')){
    /** 获取目录下全部文件
     * @param string $dir
     * @return mixed
     */
    function getDirAllFile($dir='.')
    {
        // 验证目录有效性
        if(false == ($realDir = realpath($dir))){
            return array();
        }
        if(false == is_dir($realDir)){
            return array();
        }
        // 验证打开目录函数是否可用
        if(!function_usable('readdir')){
            return array();
        }
        // 打开目录
        if(false == ($handle = opendir($realDir))){
            return array();
        }
        // 遍历目录
        $result=array();
        clearstatcache(); // 清理文件信息缓存
        while (false !== ($file = readdir($handle))){
            if(in_array($file,array('.','..'))){
                continue;
            }
            $path = $dir.'/'.$file;
            $realPath=$realDir.'/'.$file;
            $isDir=is_dir($path);

            if($isDir){
                $array = getDirAllFile($path);
                $result = array_merge($result,$array);
            }else{
                $pathinfo=pathinfo($file);
                $size=makeFileSize(filesize($realPath));
                $key = str_replace('/','_',$realPath);
                $result[$key]=array(
                    'File'=>$file,
                    'Ext'=>$pathinfo['extension'],
                    'Dir'=>$dir,
                    'Path'=>$path,
                    'RealDir'=>$realDir,
                    'RealPath'=>$realPath,
                    'Size'=>$size,
                    'Updated'=>date('Y-m-d H:i:s',filemtime($realPath)),
                );
            }
        }
        // 关闭目录
        closedir($handle);
        ksort($result);

        return $result;
    }
}

if(!function_exists('makeFileSize')){
    /** 文件大小转换
     * @param int $size
     * @param int $digits
     * @return string
     */
    function makeFileSize($size, $digits=2){
        $unit= array('','K','M','G','T','P');
        $base= 1024;
        $i = floor(log($size,$base));
        $n = count($unit);
        if($i >= $n){
            $i=$n-1;
        }
        $result = round($size/pow($base,$i),$digits).' '.$unit[$i] . 'B';

        return $result;
    }
}


if(!function_exists('makeDateBillNo')){
    /** 生成 20 位日期订单号
     * @return string
     */
    function makeDateBillNo()
    {
        $billNo = date('ymdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

        return $billNo;
    }
}

if(!function_exists('makeArrayIterator')){
    /** 生成数组迭代器
     * @return string
     */
    function makeArrayIterator($array = array())
    {
        $obj = new ArrayObject($array);
        $iterator = $obj->getIterator();

        return $iterator;
    }
}

if(!function_exists('makeTextYield')){
    /**
     * 文本文件迭代生成器
     */
    function makeTextYield($file)
    {
        $handle = fopen($file, "r") or exit("不能打开文件");
        while(!feof($handle))
        {
            yield fgets($handle);
        }
        fclose($handle);
    }
}

if(!function_exists('makeCsvYield')){
    /**
     * CSV文件迭代生成器
     */
    function makeCsvYield($file)
    {
        $handle = fopen($file, "r") or exit("不能打开文件");
        while(!feof($handle))
        {
            yield fgetcsv($handle);
        }
        fclose($handle);
    }
}

if(!function_exists('outputHeaderForFile')){
    /**
     * 文件输出头
     */
    function outputHeaderForFile($name,$size=0)
    {
        // Redirect output to a client’s web browser (Excel5)
        header ( "Content-Type: application/octet-stream" );
        header ( "Content-Transfer-Encoding: binary" );
        Header ( "Accept-Ranges: bytes ");
        header ('Content-Disposition: attachment;filename="'.$name.'"');
        if($size > 0){
            header ( 'Content-Length: ' . $size);
        }

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        header ('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header ('Cache-Control: max-age=1');
        header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0 ");
    }
}

if(!function_exists('getPositionFromTaoBaoIssueByIp')){
    /**
     * 通过IP获取淘宝IP库中的省市位置
     */
    function getPositionFromTaoBaoIssueByIp($ip)
    {
        $issueUrl = 'http://ip.taobao.com/service/getIpInfo.php';
        $url = $issueUrl.'?ip='.urlencode($ip);
        $res = file_get_contents($url);
        $res = json_decode($res,true);
        # 0 成功，1 失败
        if(0 == $res['code']){
            return $res['data'];
        }else{
            return false;
        }
    }
}

if(!function_exists('arrayToSimpleXml')){

    /** 将数组转换为Xml
     * @param $array
     * @param string $root
     * @param null|object $xml
     * @return mixed
     */
    function arrayToSimpleXml($array, $root='<root/>', $xml = null)
    {
        # 创建 xml 文档对象
        if(null === $xml){
            $xml = new SimpleXMLElement($root);
        }
        # 迭代数组添加到xml 目录下
        $array = makeArrayIterator($array);
        foreach($array as $key=>$value){
            if(is_array($value)){
                arrayToSimpleXml($value,$key,$xml->addChild($key));
            }else{
                $xml->addChild($key,$value);
            }
        }

        # 返回 xml
        return $xml->asXML();
    }
}

if(!function_exists('simpleXmlToArray')){

    /** 将Xml 转换为数组
     * @param $xml
     * @param bool $toArr
     * @return mixed|SimpleXMLElement
     */
    function simpleXmlToArray($xml, $toArr = true)
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