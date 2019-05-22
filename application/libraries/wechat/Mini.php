<?php
/**
 *  微信接口 小游戏/小程序
 * @author MSL
 * @create 2019-04-01
 */

namespace libraries\wechat;


class Mini extends Base
{

    public $appid; # APPID
    public $appkey; # APPSECRET
    public $token; # TOKEN
    public $encodingAESKey; # EncodingAESKey

    public $codeToOpenidUrl = 'https://api.weixin.qq.com/sns/jscode2session';
    public $sendCustomMsgUrl = 'https://api.weixin.qq.com/cgi-bin/message/custom/send';

    # 登录凭证校验的接口地址
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code
    public $jscodeToSessionURL = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';

    /**
     * 登录凭证校验
     *
     * @param $code
     * @return bool|false|mixed|string
     */
    public function jscodeToSession($code)
    {
        $url = sprintf($this->jscodeToSessionURL, $this->appid, $this->appkey, $code);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 发送客服消息给用户的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN
    public $customerServiceMessageURL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s';

    /**
     * 发送客服消息给用户
     *
     * @param $accessToken
     * @param $openid
     * @param $msgType
     * @param $content
     * @return bool
     */
    public function sendCustomerServiceMessage($accessToken, $openid, $msgType, $content)
    {
        $url = sprintf($this->customerServiceMessageURL, $accessToken);
        $data = array(
            'access_token' => $accessToken,
            'touser'       => $openid,
            'msgtype'      => $msgType,
            $msgType       => $content,
        );
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return true;
    }
}