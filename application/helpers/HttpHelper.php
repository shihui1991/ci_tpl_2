<?php
namespace app\helpers;

class HttpHelper
{
    /**
     * curl 请求
     *
     * @param $url
     * @param string $data
     * @param bool $isPost
     * @param int $execTimes
     * @param bool $makeJsonStr
     * @return bool|string
     */
    static public function curl($url, $data = '', $isPost = true, $execTimes = 1, $makeJsonStr = true)
    {
        # 检测是不是 https
        $ssl = false;
        $http = parse_url($url,PHP_URL_SCHEME);
        if('https' == $http){
            $ssl = true;
        }
        # 检测 url 中是否已存在参数
        $mark = strpos($url,'?');
        # 处理 POST 请求的参数
        $header = array();
        if($isPost){
            if(is_array($data) && $makeJsonStr){
                $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            }
            if($makeJsonStr){
                $header[] = 'Content-Type: application/json';
            }
        }
        # 处理 GET 请求的参数
        else{
            # 将参数转为请求字符串
            if(is_array($data)){
                $data = http_build_query($data);
            }
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
        if( ! empty($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
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

    /**
     * 获取IP信息，淘宝数据库
     *
     * @param $ip
     * @return bool
     */
    static public function getIpInfo($ip)
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