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


    # 用户同意授权，获取code的跳转地址
    # https请求方式：GET
    # https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
    # 【请求参数】
    # string redirect_uri 授权后重定向的回调链接地址， 请使用 urlEncode 对链接进行处理
    # string scope 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且， 即使在未关注的情况下，只要用户授权，也能获取其信息 ）
    # string state 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字
    # 如果用户同意授权，页面将跳转至 redirect_uri/?code=CODE&state=STATE
    public $authorizeURL = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';

    /** 跳转至用户授权
     * @param string $redirect
     * @param string $scope
     * @param string $state
     */
    public function authorize($redirect, $scope = 'snsapi_base', $state = '123')
    {
        $url = sprintf($this->authorizeURL, $this->appid, urlencode($redirect), $scope, $state);
        header('Location:'.$url);
        exit;
    }

    # 通过code换取网页授权access_token的接口地址
    # 如果网页授权的作用域为snsapi_base，则本步骤中获取到网页授权access_token的同时，也获取到了openid，snsapi_base式的网页授权流程即到此为止
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
    # 【返回参数】
    # string access_token  网页授权接口调用凭证
    # int expires_in  access_token接口调用凭证超时时间，单位（秒）
    # string refresh_token  用户刷新access_token
    # string openid  用户唯一标识，请注意，在未关注公众号时，用户访问公众号的网页，也会产生一个用户和公众号唯一的OpenID
    # string scope  用户授权的作用域，使用逗号（,）分隔
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $accessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';

    /** 通过code换取网页授权access_token
     * @param string $code
     * @return array|bool
     */
    public function getAccessToken($code)
    {
        $url = sprintf($this->accessTokenURL, $this->appid, $this->appkey, $code);
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
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $authURL = 'https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s';

    /**
     * 检验授权凭证（access_token）是否有效
     *
     * @param $accessToken
     * @param $openid
     * @return bool
     */
    public function checkAuth($accessToken, $openid)
    {
        $url = sprintf($this->authURL, $accessToken, $openid);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 == $res['errcode']){
            return true;
        }else{
            return false;
        }
    }

    # 刷新 ACCESS_TOKEN 的接口地址
    # 由于access_token拥有较短的有效期，当access_token超时后，可以使用refresh_token进行刷新，refresh_token有效期为30天，当refresh_token失效之后，需要用户重新授权
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN
    # 【返回参数】
    # string access_token  网页授权接口调用凭证
    # int expires_in  access_token接口调用凭证超时时间，单位（秒）
    # string refresh_token  用户刷新access_token
    # string openid  用户唯一标识
    # string scope  用户授权的作用域，使用逗号（,）分隔
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $refreshTokenURL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';

    /**
     * 刷新 ACCESS_TOKEN
     * @param $refreshToken
     * @return bool|array
     */
    public function refreshToken($refreshToken)
    {
        $url = sprintf($this->refreshTokenURL, $this->appid, $refreshToken);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 拉取用户信息(需scope为 snsapi_userinfo)的接口地址
    # https请求方式: GET
    # https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
    # 【返回参数】
    # string openid  用户唯一标识
    # string nickname  用户昵称
    # int sex  用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
    # string province  用户个人资料填写的省份
    # string city  普通用户个人资料填写的城市
    # string country  国家，如中国为CN
    # string headimgurl  用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效
    # array  privilege  用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
    # string  unionid  只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $userinfoURL = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';

    /**
     * 获取用户信息
     * @param $accessToken
     * @param $openid
     * @return mixed
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