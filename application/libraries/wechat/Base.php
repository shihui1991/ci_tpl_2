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
    # 必须 action 	        执行的检测动作，允许的值：dns（做域名解析）、ping（做ping检测）、all（dns和ping都做）
    # 必须 check_operator 	指定平台从某个运营商进行检测，允许的值：CHINANET（电信出口）、UNICOM（联通出口）、CAP（腾讯自建出口）、DEFAULT（根据ip来选择运营商）
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
}