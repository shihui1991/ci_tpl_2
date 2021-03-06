<?php
/**
 *  微信接口 基础功能
 * @author MSL
 * @create 2019-04-01
 */

namespace libraries\wechat;

abstract class Base
{
    public $appid; # APPID
    public $appkey; # APPSECRET
    public $token; # TOKEN
    public $encodingAESKey; # EncodingAESKey

    static protected $instances; # 实例


    /**
     * 静态方法生成实例
     *
     * @param int $k
     * @return mixed
     */
    static public function instance($k = 0)
    {
        if(empty($k)){
            $k = md5(get_called_class());
        }
        if(empty(static::$instances[$k])){
            static::$instances[$k] = new static();
        }
        return static::$instances[$k];
    }
    /**
     * 验证请求是否来自微信
     *
     * @param array $input
     * @param string signature  微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数
     * @param int timestamp 时间戳
     * @param string nonce 随机数
     * @param string echostr 随机字符串
     * @return bool|string
     */
    public function valiRequestFromWeChat($input = array())
    {
        if(empty($input)){
            $input = $_GET;
        }
        # 1）将token、timestamp、nonce三个参数进行字典序排序
        $array = array(
            $this->token,
            time(),
            $input['nonce'],
        );
        sort($array,SORT_STRING);

        # 2）将三个参数字符串拼接成一个字符串进行sha1加密
        $string = implode('',$array);
        $sign = sha1($string);

        # 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
        # 原样返回 echostr 参数内容，则接入生效，成为开发者成功，否则接入失败
        if($sign == $input['signature']){
            return $input['echostr'];
        }else{
            return false;
        }
    }

    # 获取全局调用凭证的接口地址
    # https请求方式: GET
    # https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
    # 【返回参数】
    # string access_token  获取到的凭证
    # int expires_in  凭证有效时间，单位：秒
    # int errcode  返回码 0 请求成功
    # string errmsg  错误信息
    public $baseAccessTokenURL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';

    /**
     * 获取全局 ACCESS_TOKEN
     *
     * @return bool|string
     */
    public function getBaseAccessToken()
    {
        $url = sprintf($this->baseAccessTokenURL, $this->appid, $this->appkey);
        $res = file_get_contents($url);
        $res = json_decode($res,true);

        if(empty($res['access_token'])){
            return false;
        }else{
            return $res['access_token'];
        }
    }

    # 获取微信服务器IP地址的接口地址
    # http请求方式: GET
    # https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=ACCESS_TOKEN
    # 【返回参数】
    # array ip_list  微信服务器IP地址列表
    public $serverIPsURL = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=%s';

    /**
     * 获取微信服务器IP地址
     *
     * @param $accessToken
     * @return bool|array
     */
    public function getServerIPs($accessToken)
    {
        $url = sprintf($this->serverIPsURL, $accessToken);
        $res = file_get_contents($url);
        $res = json_decode($res,true);

        if(empty($res['ip_list'])){
            return false;
        }else{
            return $res['ip_list'];
        }
    }

    # 网络检测的接口地址
    # HTTP Post请求：
    # https://api.weixin.qq.com/cgi-bin/callback/check?access_token=ACCESS_TOKEN
    # 【请求参数】
    # 必须  action 	        执行的检测动作，允许的值：dns（做域名解析）、ping（做ping检测）、all（dns和ping都做）
    # 必须  check_operator 	指定平台从某个运营商进行检测，允许的值：CHINANET（电信出口）、UNICOM（联通出口）、CAP（腾讯自建出口）、DEFAULT（根据ip来选择运营商）
    # 【返回参数】
    # array dns  dns结果列表
    # string dns.ip  解析出来的ip
    # string dns.real_operator  ip对应的运营商
    # array ping  ping结果列表
    # string ping.ip  ping的ip，执行命令为ping ip –c 1-w 1 -q
    # string ping.from_operator  ping的源头的运营商，由请求中的check_operator控制
    # string ping.package_loss  ping的丢包率，0%表示无丢包，100%表示全部丢包。因为目前仅发送一个ping包，因此取值仅有0%或者100%两种可能
    # string ping.time  ping的耗时，取ping结果的avg耗时
    public $checkNetURL = 'https://api.weixin.qq.com/cgi-bin/callback/check?access_token=%s';

    /**
     * 网络检测
     *
     * @param $accessToken
     * @param string $action
     * @param string $checkOperator
     * @return array
     */
    public function checkNet($accessToken, $action = 'all', $checkOperator = 'DEFAULT')
    {
        $data = array(
            'action'         => $action,
            'check_operator' => $checkOperator,
        );
        $url = sprintf($this->checkNetURL, $accessToken);
        $res = curlHttp($url,$data,true,1);
        $res = json_decode($res,true);

        return $res;
    }

    /**
     * 生成 MD5 签名
     *
     * @param $data
     * @return string
     */
    public function makeMd5Sign($data)
    {
        $str = http_build_query($data);
        # 拼接 appkey
        $str .= '&key=' . $this->appkey;
        # md5
        $md5Sign = strtoupper(md5($str));

        return $md5Sign;
    }

    /** 过滤值为空的参数
     * @param $data
     * @return mixed
     */
    public function filterEmptyData($data)
    {
        foreach($data as $key => $value){
            if(0 == strlen($value)){
                unset($data[$key]);
            }
        }
        ksort($data);

        return $data;
    }

    /**
     * 生成通知微信的 xml
     *
     * @param $code
     * @param $msg
     * @return string
     */
    public function makeNoticeXml($code, $msg)
    {
        return  "
<xml>
  <return_code><![CDATA[{$code}]]></return_code>
  <return_msg><![CDATA[{$msg}]]></return_msg>
</xml>
        ";
    }

    public /** curl http/https 请求
     * @param string $url
     * @param string|array $data
     * @param bool $isPost
     * @param int $execTimes
     * @param bool $makeJsonStr
     * @return mixed|string
     */
    function curlHttp($url, $data = '', $isPost = true, $execTimes = 1, $makeJsonStr = true)
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
}