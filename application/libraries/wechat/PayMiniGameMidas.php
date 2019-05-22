<?php
/**
 *  微信接口 小游戏米大师支付
 * @author MSL
 * @create 2019-04-02
 */

namespace libraries\wechat;


class PayMiniGameMidas extends Base
{

    public $appid;
    public $appkey;
    public $offerid; # 米大师应用ID
    public $offerKey; # 米大师密钥 正式环境
    public $offerSandboxKey; # 米大师密钥 沙箱环境
    public $env = 0; # 0 正式环境，1 沙箱环境


    /**
     * 生成米大师支付 sig 签名
     *
     * @param $data
     * @param $uri
     * @return string
     */
    public function makeMidasPaySig($data, $uri)
    {
        # 根据环境选择密钥
        $secret = 0 == $this->env ? $this->offerKey : $this->offerSandboxKey;
        # 拼接字符串
        $str = http_build_query($data);
        $format = '&org_loc=%s&method=POST&secret=%s';
        $str .= sprintf($format, $uri, $secret);
        # HMAC-SHA256 加密
        $sig = hash_hmac('sha256', $str, $secret);

        return $sig;
    }

    /**
     * 生成米大师支付 mp_sig 签名
     * @param $data
     * @param $uri
     * @param $sessionKey
     * @return string
     */
    public function makeMidasPayMpSig($data, $uri, $sessionKey)
    {
        # 拼接字符串
        $str = http_build_query($data);
        $format ='&org_loc=%s&method=POST&session_key=%s';
        $str .= sprintf($format, $uri, $sessionKey);
        # HMAC-SHA256 加密
        $mpSig = hash_hmac('sha256', $str, $sessionKey);

        return $mpSig;
    }

    /**
     * 生成米大师请求的基本数据
     *
     * @param $openid
     * @param string $clientIP
     * @return array
     */
    public function makeMidasBaseData($openid, $clientIP = '')
    {
        return array(
            'openid'   => $openid,
            'appid'    => $this->appid,
            'offer_id' => $this->offerid,
            'ts'       => time(),
            'zone_id'  => '1',
            'pf'       => 'android',
            'user_ip'  => $clientIP,
        );
    }

    /** 米大师接口的请求结果
     * @param $data
     * @param $baseAccessToken
     * @param $uri
     * @param $urlFormat
     * @param $sessionKey
     * @return bool|mixed|string
     */
    public function getMidasApiRes($data, $baseAccessToken, $uri, $urlFormat, $sessionKey)
    {
        $url = sprintf($urlFormat,$baseAccessToken);
        # 整理数据
        $data = $this->filterEmptyData($data);
        $data['sig'] = $this->makeMidasPaySig($data,$uri);
        $data['access_token'] = $baseAccessToken;
        $data['mp_sig'] = $this->makeMidasPayMpSig($data, $uri, $sessionKey);
        # 请求
        $res = $this->curlHttp($url,$data,true,1);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 获取游戏币余额的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/midas/getbalance?access_token=ACCESS_TOKEN
    # https://api.weixin.qq.com/cgi-bin/midas/sandbox/getbalance?access_token=ACCESS_TOKEN
    public $getBalanceURI = '/cgi-bin/midas/getbalance';
    public $getBalanceURL = 'https://api.weixin.qq.com/cgi-bin/midas/getbalance?access_token=%s';
    public $getBalanceSandboxURI = '/cgi-bin/midas/sandbox/getbalance';
    public $getBalanceSandboxURL = 'https://api.weixin.qq.com/cgi-bin/midas/sandbox/getbalance?access_token=%s';

    /**
     * 获取游戏币余额
     *
     * @param $baseAccessToken
     * @param $openid
     * @param $sessionKey
     * @param string $clientIP
     * @return bool|mixed|string
     */
    public function getMidasBalance($baseAccessToken, $openid, $sessionKey, $clientIP = '')
    {
        # 根据环境选择接口地址
        $uri = $this->getBalanceSandboxURI;
        $urlFormat = $this->getBalanceSandboxURL;
        if(0 == $this->env){
            $uri = $this->getBalanceURI;
            $urlFormat = $this->getBalanceURL;
        }
        # 请求参数
        $data = $this->makeMidasBaseData($openid, $clientIP);
        # 请求结果
        $res = $this->getMidasApiRes($data, $baseAccessToken, $uri, $urlFormat, $sessionKey);

        return $res;
    }

    # 扣除游戏币的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/midas/pay?access_token=ACCESS_TOKEN
    # https://api.weixin.qq.com/cgi-bin/midas/sandbox/pay?access_token=ACCESS_TOKEN
    public $midasPayURI = '/cgi-bin/midas/pay';
    public $midasPayURL = 'https://api.weixin.qq.com/cgi-bin/midas/pay?access_token=%s';
    public $midasPaySandboxURI = '/cgi-bin/midas/sandbox/pay';
    public $midasPaySandboxURL = 'https://api.weixin.qq.com/cgi-bin/midas/sandbox/pay?access_token=%s';

    /**
     * 扣除游戏币
     *
     * @param $baseAccessToken
     * @param $openid
     * @param $sessionKey
     * @param $amount
     * @param $billNo
     * @param string $clientIP
     * @param string $payItem
     * @param string $remark
     * @return bool|mixed|string
     */
    public function midasPay($baseAccessToken, $openid, $sessionKey, $amount, $billNo, $clientIP = '', $payItem = '', $remark = '')
    {
        # 根据环境选择接口地址
        $uri = $this->midasPaySandboxURI;
        $urlFormat = $this->midasPaySandboxURL;
        if(0 == $this->env){
            $uri = $this->midasPayURI;
            $urlFormat = $this->midasPayURL;
        }
        # 请求参数
        $data = $this->makeMidasBaseData($openid, $clientIP);
        $other = array(
            'amt'        => $amount,
            'bill_no'    => $billNo,
            'pay_item'   => $payItem,
            'app_remark' => $remark,
        );
        $data = array_merge($data,$other);
        # 请求结果
        $res = $this->getMidasApiRes($data, $baseAccessToken, $uri, $urlFormat, $sessionKey);

        return $res;
    }

    # 取消订单的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/midas/cancelpay?access_token=ACCESS_TOKEN
    # https://api.weixin.qq.com/cgi-bin/midas/sandbox/cancelpay?access_token=ACCESS_TOKEN
    public $midasCancelPayURI = '/cgi-bin/midas/cancelpay';
    public $midasCancelPayURL = 'https://api.weixin.qq.com/cgi-bin/midas/cancelpay?access_token=%s';
    public $midasCancelPaySandboxURI = '/cgi-bin/midas/sandbox/cancelpay';
    public $midasCancelPaySandboxURL = 'https://api.weixin.qq.com/cgi-bin/midas/sandbox/cancelpay?access_token=%s';

    /**
     * 取消订单
     *
     * @param $baseAccessToken
     * @param $openid
     * @param $sessionKey
     * @param $billNo
     * @param string $clientIP
     * @param string $payItem
     * @return bool|mixed|string
     */
    public function midasCancelPay($baseAccessToken, $openid, $sessionKey, $billNo, $clientIP = '', $payItem = '')
    {
        # 根据环境选择接口地址
        $uri = $this->midasCancelPaySandboxURI;
        $urlFormat = $this->midasCancelPaySandboxURL;
        if(0 == $this->env){
            $uri = $this->midasCancelPayURI;
            $urlFormat = $this->midasCancelPayURL;
        }
        # 请求参数
        $data = $this->makeMidasBaseData($openid, $clientIP);
        $other = array(
            'bill_no'  => $billNo,
            'pay_item' => $payItem,
        );
        $data = array_merge($data,$other);
        # 请求结果
        $res = $this->getMidasApiRes($data, $baseAccessToken, $uri, $urlFormat, $sessionKey);

        return $res;
    }

    # 给用户赠送游戏币的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/midas/present?access_token=ACCESS_TOKEN
    # https://api.weixin.qq.com/cgi-bin/midas/sandbox/present?access_token=ACCESS_TOKEN
    public $midasPresentURI = '/cgi-bin/midas/present';
    public $midasPresentURL = 'https://api.weixin.qq.com/cgi-bin/midas/present?access_token=%s';
    public $midasPresentSandboxURI = '/cgi-bin/midas/sandbox/present';
    public $midasPresentSandboxURL = 'https://api.weixin.qq.com/cgi-bin/midas/sandbox/present?access_token=%s';

    /**
     * 给用户赠送游戏币
     *
     * @param $baseAccessToken
     * @param $openid
     * @param $sessionKey
     * @param $billNo
     * @param $amount
     * @param string $clientIP
     * @return bool|mixed|string
     */
    public function midasPresent($baseAccessToken, $openid, $sessionKey, $billNo, $amount, $clientIP = '')
    {
        # 根据环境选择接口地址
        $uri = $this->midasPresentSandboxURI;
        $urlFormat = $this->midasPresentSandboxURL;
        if(0 == $this->env){
            $uri = $this->midasPresentURI;
            $urlFormat = $this->midasPresentURL;
        }
        # 请求参数
        $data = $this->makeMidasBaseData($openid, $clientIP);
        $other = array(
            'bill_no'        => $billNo,
            'present_counts' => $amount,
        );
        $data = array_merge($data,$other);
        # 请求结果
        $res = $this->getMidasApiRes($data, $baseAccessToken, $uri, $urlFormat, $sessionKey);

        return $res;
    }
}