<?php
namespace app\helpers;

class StrHelper
{
    /**
     * 生成时间唯一字符串（32位）
     *
     * @param bool $upper
     * @return string
     */
    static public function makeUniqid($upper = false)
    {
        $str = md5(uniqid(mt_rand(), true));
        if($upper){
            $str = strtoupper($str);
        }

        return $str;
    }

    /**
     * 生成GUID
     *
     * @return string
     */
    static public function makeGuid()
    {
        $charid =  static::makeUniqid(true);
        $hyphen = '-';
        $guid = substr($charid, 6, 2).substr($charid, 4, 2). substr($charid, 2, 2).substr($charid, 0, 2).$hyphen
            .substr($charid, 10, 2).substr($charid, 8, 2).$hyphen
            .substr($charid,14, 2).substr($charid,12, 2).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);

        return $guid;
    }

    /**
     * 生成时间随机订单号
     *
     * @param string $format
     * @param int $randLen
     * @return string
     */
    static public function makeDatetimeBillNo($format = 'ymdHis', $randLen = 8)
    {
        return date($format).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $randLen);
    }

    /**
     * 版本号比较
     *
     * @param $curVersion
     * @param $compareVersion
     * @return int 0 相等，1 左大于右 ，-1 左小于右
     */
    static public function compareVersion($curVersion, $compareVersion){
        if($curVersion === $compareVersion){
            return 0;
        }
        $curVersion = explode('.',$curVersion);
        $compareVersion = explode('.',$compareVersion);
        $res = 0;
        foreach($compareVersion as $k=>$v){
            $res = bccomp($curVersion[$k], $v);
            if(0 != $res){
                break;
            }
        }

        return $res;
    }

    /**
     * 添加到文件中
     *
     * @param $content
     * @param $file
     * @return bool|int
     */
    static public function appendToFile($content, $file)
    {
        $exists = file_exists($file);
        if( ! $exists){
            $dir = dirname($file);
            if( ! file_exists($dir)){
                mkdir($dir, 0777, true);
            }
            touch($file);
            chmod($file,0777);
        }

        return file_put_contents($file, $content."\r\n", FILE_APPEND);
    }

    /**
     * 获取文本文件迭代接口
     *
     * @param $file
     * @return \Generator
     */
    static public function makeTextYield($file)
    {
        $handle = fopen($file, "r") or exit("不能打开文件");
        while(!feof($handle))
        {
            yield fgets($handle);
        }
        fclose($handle);
    }

    /**
     * 获取 CSV 文件迭代接口
     *
     * @param $file
     * @return \Generator
     */
    static public function makeCsvYield($file)
    {
        $handle = fopen($file, "r") or exit("不能打开文件");
        while(!feof($handle))
        {
            yield fgetcsv($handle);
        }
        fclose($handle);
    }

    /**
     * 文件输出头
     *
     * @param $name
     * @param int $size
     */
    static public function outputFileHeader($name, $size = 0)
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

    /**
     * 下划线转驼峰
     *
     * @param $value
     * @return mixed|string
     */
    static public function camel($value)
    {
        $value = ucwords(str_replace('_',' ',$value));
        $value = str_replace(' ','',$value);
        $value = lcfirst($value);

        return $value;
    }

    /**
     * 驼峰转下划线
     *
     * @param $value
     * @return string|string[]|null
     */
    static public function snake($value)
    {
        if (! ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));
            $value = preg_replace('/(.)(?=[A-Z])/u', '$1'.'_', $value);
            $value = mb_strtolower($value,'UTF-8');
        }

        return $value;
    }
}