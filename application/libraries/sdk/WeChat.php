<?php
/**
 *  PHPExcel 功能
 * @author 罗仕辉
 * @create 2019-01-31
 */

namespace libraries\sdk;


class WeChat
{
    # 当前应用配置
    public $appid = '';
    public $appkey = '';
    public $mchid = '';
    public $payNotifyUrl = '';
    # 微信固定配置
    public $refreshTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    public $accessTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    public $userinfoUrl = 'https://api.weixin.qq.com/sns/userinfo';
    public $orderUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $orderQueryUrl = 'https://api.mch.weixin.qq.com/pay/orderquery';
    public $codeToOpenidUrl = 'https://api.weixin.qq.com/sns/jscode2session';
    # 实例
    static protected $objs;
    
    /**
     * 静态方法生成实例
     *
     * @param int $k
     */
    static public function instance($k = 0)
    {
        if(empty(static::$objs[$k])){
            static::$objs[$k] = new static();
        }
        return static::$objs[$k];
    }

    /** 获取微信授权登录数据
     * @param string $code
     * @param string $accessToken
     * @return mixed
     * @throws \Exception
     */
    public function checkAuth($code, $accessToken='')
    {
        if(empty($code)){
            throw new \Exception('请先授权微信登录',EXIT_USER_INPUT);
        }
        # Login_With_Auto 这个标识是微信登录一次之后，再次登录不验证code，直接拿token和refleshtoken请求微信验证，验证过后直接返回正确数据
        if('Login_With_Auto' == $code){
            # 检验 access_token
            if(empty($accessToken)){
                throw new \Exception('微信授权信息已过期，请重新授权登录',EXIT_USER_INPUT);
            }
            $oauth2Url = $this->refreshTokenUrl."?appid=".$this->appid."&grant_type=refresh_token&refresh_token=".$accessToken;
        }
        # 获取微信 openid,access_token
        else{
            $oauth2Url = $this->accessTokenUrl."?appid=".$this->appid."&secret=".$this->appkey."&code=".$code."&grant_type=authorization_code";
        }
        # 获取微信 openid,access_token
        $request = file_get_contents($oauth2Url);
        $authData = json_decode($request,TRUE);
        if(isset($authData['errcode']) && 0 != $authData['errcode']){
            throw new \Exception('授权失败',EXIT_USER_INPUT);
        }

        return $authData;
    }

    /** 微信小游戏通过code获取openid
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public function handleCodeToOpenid($code)
    {
        if(empty($code)){
            throw new \Exception('请先授权微信登录',EXIT_USER_INPUT);
        }
        $url = $this->codeToOpenidUrl.'?appid='.$this->appid.'&secret='.$this->appkey.'&js_code='.$code.'&grant_type=authorization_code';
        # 获取微信 openid,session_key
        $request = file_get_contents($url);
        $authData = json_decode($request,TRUE);
        if(isset($authData['errcode']) && 0 != $authData['errcode']){
            throw new \Exception('授权失败',EXIT_USER_INPUT);
        }

        return $authData['openid'];
    }

    /** 获取微信玩家信息
     * @param string $openid
     * @param string $accessToken
     * @param string $code
     * @return array
     * @throws \Exception
     */
    public function getUserinfo($openid = '', $accessToken = '', $code = '')
    {
        # 获取微信授权登录数据
        if(empty($openid)){
            $authData = $this->checkAuth($code,$accessToken);
            $openid = $authData['openid'];
            $accessToken = $authData['access_token'];
        }
        # 获取玩家信息
        $url = $this->userinfoUrl."?access_token=".$accessToken."&openid=".$openid."&lang=zh_CN";
        $res = file_get_contents($url);
        $userinfo = json_decode($res,TRUE);
        if(isset($userinfo['errcode']) && 0 != $userinfo['errcode']){
            throw new \Exception('获取玩家数据失败',EXIT_USER_INPUT);
        }
        $userinfo['access_token'] = $accessToken;

        return $userinfo;
    }

    /** 下单，并返回支付参数
     * @param string $outTradeNo 本地订单号
     * @param string $body  商品描述
     * @param number $totalFee 总金额，元
     * @param string $clientIp 终端IP
     * @return array
     * @throws \Exception
     */
    public function createOrder($outTradeNo, $body, $totalFee, $clientIp)
    {
        $nonceStr = makeUniqueStr(true); # 随机字符串
        # 请求参数
        $data = array(
            'appid' => $this->appid,                       # 应用ID
            'mch_id' => $this->mchid,                      # 商户号
            'device_info' => 'WEB',                        # 设备号，默认请传"WEB"
            'nonce_str' => $nonceStr,                      # 随机字符串，不长于32位
            'body' => $body,                               # 商品描述 天天爱消除-游戏充值
            'out_trade_no' => $outTradeNo,                 # 商户订单号
            'total_fee' => ceil($totalFee * 100),    # 总金额，分
            'spbill_create_ip' => $clientIp,               # 终端IP
            'notify_url' => $this->payNotifyUrl,           # 通知地址
            'trade_type' => 'APP',                         # 交易类型，APP
            'sign_type' => 'MD5',                          # 签名类型，默认为 MD5
        );
        # 生成 MD5 签名
        $sign = $this->makeMd5Sign($data);
        $data['sign'] = $sign;
        # 请求参数转为 xml
        $dataXml = arrayToSimpleXml($data,'<xml/>');
        # 下单
        $resXml = curlHttp($this->orderUrl,$dataXml,true,2);
        # 解析下单结果
        $result = simpleXmlToArray($resXml);
        if('SUCCESS' != $result['return_code']){
            throw new \Exception('下单失败',EXIT__AUTO_MIN);
        }
        # 支付参数
        $timeStamp = (string)time();
        $payData = array(
            'appid' => $this->appid,               # 应用ID
            'partnerid' => $this->mchid,           # 商户号
            'prepayid' => $result['prepay_id'],    # 预支付交易会话ID
            'package' => 'Sign=WXPay',             # 扩展字段，暂填写固定值Sign=WXPay
            'noncestr' => $nonceStr,               # 随机字符串，不长于32位
            'timestamp' => $timeStamp,             # 时间戳
        );
        # 生成 MD5 签名
        $paySign = $this->makeMd5Sign($payData);
        $payData['sign'] = $paySign;

        return $payData;
    }

    /** 查询订单
     * @param $outTradeNo
     * @param string $transactionId
     * @return array
     * @throws \Exception
     */
    public function queryOrder($outTradeNo, $transactionId = '')
    {
        $nonceStr = makeUniqueStr(true); # 随机字符串
        # 请求参数
        $data = array(
            'appid' => $this->appid,               # 应用ID
            'mch_id' => $this->mchid,              # 商户号
            'transaction_id' => $transactionId,    # 微信订单号，优先使用
            'out_trade_no' => $outTradeNo,         # 商户系统内部的订单号
            'nonce_str' => $nonceStr,              # 随机字符串，不长于32位
        );
        # 生成 MD5 签名
        $sign = $this->makeMd5Sign($data);
        $data['sign'] = $sign;
        # 请求参数转为 xml
        $dataXml = arrayToSimpleXml($data,'<xml/>');
        # 查询订单
        $resXml = curlHttp($this->orderQueryUrl,$dataXml,true,2);
        # 解析查询结果
        $result = simpleXmlToArray($resXml);
        if('SUCCESS' != $result['return_code']){
            throw new \Exception('查询失败',EXIT__AUTO_MIN);
        }
        if('SUCCESS' != $result['result_code']){
            throw new \Exception('订单不存在',EXIT__AUTO_MIN);
        }

        return $result;
    }

    /** 生成 MD5 签名
     * @param $data
     * @return string
     */
    public function makeMd5Sign($data)
    {
        # 参数的值为空不参与签名
        foreach($data as $key => $value){
            if(0 == strlen($value)){
                unset($data[$key]);
            }
        }
        # 参数名ASCII码从小到大排序（字典序）
        ksort($data);
        # 对参数按照key=value的格式
        $str = http_build_query($data);
        # 拼接 appkey
        $signString = $str. '&key='.$this->appkey;
        # md5
        $md5Sign = strtoupper(md5($signString));

        return $md5Sign;
    }

    /** 生成通知微信的 xml
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