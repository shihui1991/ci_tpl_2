<?php
/**
 *  微信接口 公众号
 * @author MSL
 * @create 2019-04-01
 */

namespace libraries\wechat;


class Official extends Base
{

    public $appid; # APPID
    public $appkey; # APPSECRET
    public $token; # TOKEN
    public $encodingAESKey; # EncodingAESKey


    # 获取授权数据的接口地址
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
    public $authDataURL = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';

    /**
     * 获取微信授权登录数据
     *
     * @param string $code
     * @return array|bool
     */
    public function checkAuth($code)
    {
        $url = sprintf($this->authDataURL, $this->appid, $this->appkey, $code);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 检验授权凭证（access_token）是否有效的接口地址
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/auth?access_token=ACCESS_TOKEN&openid=OPENID
    public $accessTokenURL = 'https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s';

    /**
     * 检验授权凭证（access_token）是否有效
     *
     * @param $accessToken
     * @param $openid
     * @return bool
     */
    public function checkAccessToken($accessToken, $openid)
    {
        $url = sprintf($this->accessTokenURL, $accessToken, $openid);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 == $res['errcode']){
            return true;
        }else{
            return false;
        }
    }

    # 刷新 ACCESS_TOKEN 的接口地址
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN
    public $refreshAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';

    /**
     * 刷新 ACCESS_TOKEN
     *
     * @param $refreshToken
     * @return bool|array
     */
    public function refreshAccessToken($refreshToken)
    {
        $url = sprintf($this->refreshAccessTokenURL, $this->appid, $refreshToken);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 获取用户信息的接口地址
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
    public $userinfoURL = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';

    /**
     * 获取用户信息
     *
     * @param $accessToken
     * @param $openid
     * @return bool|false|mixed|string
     */
    public function getUserinfo($accessToken, $openid)
    {
        $url = sprintf($this->userinfoURL, $accessToken, $openid);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }
}