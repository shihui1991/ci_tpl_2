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
    # 【返回参数】
    # string  openid  用户唯一标识
    # string  session_key  会话密钥
    # string  unionid  用户在开放平台的唯一标识符，在满足 UnionID 下发条件的情况下会返回
    # int  errcode  错误码
    # string  errmsg  错误信息
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

    # 校验服务器所保存的登录态 session_key 是否合法的接口地址
    # https请求方式: GET
    # 为了保持 session_key 私密性，接口不明文传输 session_key，而是通过校验登录态签名完成
    # https://api.weixin.qq.com/wxa/checksession?access_token=ACCESS_TOKEN&signature=SIGNATURE&openid=OPENID&sig_method=SIG_METHOD
    # 【请求参数】
    # string access_token 接口调用凭证
    # string openid 用户唯一标识符
    # string signature 用户登录态签名
    # string sig_method 用户登录态签名的哈希方法，目前只支持 hmac_sha256
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $checkSessionURL = 'https://api.weixin.qq.com/wxa/checksession?access_token=%s&signature=%s&openid=%s&sig_method=%s';

    /**  校验服务器所保存的登录态 session_key 是否合法
     * @param $accessToken
     * @param $openid
     * @param $sessionKey
     * @return bool|false|mixed|string
     */
    public function checkSession($accessToken, $openid, $sessionKey)
    {
        $sigMethod = 'hmac_sha256';
        $data = array(
            'access_token' => $accessToken,
            'openid'       => $openid,
            'sig_method'  => $sigMethod,
        );
        $sig = $this->makeSha1Sig($data, $sessionKey);
        $data['signature'] = $sig;
        $url = sprintf($this->checkSessionURL, $accessToken, $sig, $openid, $sigMethod);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return true;
    }

    /** 数据签名校验
     * @param $rowData
     * @param $sessionKey
     * @return string
     */
    public function makeSha1Sig($rowData, $sessionKey)
    {
        if(is_array($rowData)){
            $rowData = json_encode($rowData,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        $sig = sha1($rowData.$sessionKey);

        return $sig;
    }

    public $errorCode = array(
        0 => 'OK',
        -41001 => 'Illegal session_key',
        -41002 => 'Illegal iv',
        -41003 => '解密失败',
        -41004 => '解密后得到的buffer非法',
        -41005 => 'base64加密失败',
        -41006 => 'base64解密失败',
    );

    /** 服务端解密敏感数据
     * @param $encryptedData
     * @param $sessionKey
     * @param $iv
     * @return array|int
     * 【解密算法】
     * 1. 对称解密使用的算法为 AES-128-CBC，数据采用PKCS#7填充
     * 2. 对称解密的目标密文为 Base64_Decode(encryptedData)
     * 3. 对称解密秘钥 aeskey = Base64_Decode(session_key), aeskey 是16字节
     * 4. 对称解密算法初始向量 为Base64_Decode(iv)，其中iv由数据接口返回
     */
    public function decryptData($encryptedData, $sessionKey, $iv)
    {
        # 对称解密秘钥 aeskey = Base64_Decode(session_key), aeskey 是16字节
        if (24 != strlen($sessionKey)) {
            return -41001;
        }
        $aesKey = base64_decode($sessionKey);
        #  对称解密算法初始向量 为Base64_Decode(iv)
        if (24 != strlen($iv)) {
            return -41002;
        }
        $aesIV=base64_decode($iv);
        # 对称解密的目标密文为 Base64_Decode(encryptedData)
        $aesCipher = base64_decode($encryptedData);
        # 对称解密使用的算法为 AES-128-CBC，数据采用PKCS#7填充
        $result = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        # 判断解密结果
        $data = json_decode($result,true);
        if( NULL == $data || $data['watermark']['appid'] != $data['appid']) {
            return -41003;
        }

        return $data;
    }

    # 检查一段文本是否含有违法违规内容的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/wxa/msg_sec_check?access_token=ACCESS_TOKEN
    # 【请求参数】
    # string access_token  	接口调用凭证
    # string content  	要检测的文本内容，长度不超过 500KB
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $msgSecCheckURL = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token=%s';

    /** 检查一段文本是否含有违法违规内容
     * @param $accessToken
     * @param $text
     * @return bool
     */
    public function msgSecCheck($accessToken, $text)
    {
        $url = sprintf($this->msgSecCheckURL, $accessToken);
        $data = array(
            'access_token' => $accessToken,
            'content'      => $text,
        );
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return true;
    }

    # 校验一张图片是否含有违法违规内容的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/wxa/img_sec_check?access_token=ACCESS_TOKEN
    # 【请求参数】
    # string access_token  	接口调用凭证
    # FormData media  	要检测的图片文件，格式支持PNG、JPEG、JPG、GIF，图片尺寸不超过 750px x 1334px
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $imgSecCheckURL = 'https://api.weixin.qq.com/wxa/img_sec_check?access_token=%s';

    /** 校验一张图片是否含有违法违规内容
     * @param $accessToken
     * @param $media
     * @return bool
     */
    public function imgSecCheck($accessToken, $media)
    {
        $url = sprintf($this->imgSecCheckURL, $accessToken);
        $data = array(
            'access_token' => $accessToken,
            'media'        => $media,
        );
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return true;
    }

    # 删除已经上报到微信的key-value数据的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/wxa/remove_user_storage?access_token=ACCESS_TOKEN&signature=SIGNATURE&openid=OPENID&sig_method=SIG_METHOD
    # 【请求参数】
    # string access_token 接口调用凭证
    # string openid 用户唯一标识符
    # string signature 用户登录态签名
    # string sig_method 用户登录态签名的哈希方法，如hmac_sha256
    # array key 要删除的数据key列表
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $removeUserStorageURL = 'https://api.weixin.qq.com/wxa/remove_user_storage?access_token=%s&signature=%s&openid=%s&sig_method=%s';

    /** 删除已经上报到微信的key-value数据
     * @param $accessToken
     * @param $openid
     * @param $sessionKey
     * @param $keys
     * @return bool
     */
    public function removeUserStorage($accessToken, $openid, $sessionKey, $keys)
    {
        $sigMethod = 'hmac_sha256';
        $data = array(
            'key' => $keys,
        );
        $sig = $this->makeSha1Sig($data, $sessionKey);
        $data['signature'] = $sig;
        $url = sprintf($this->removeUserStorageURL, $accessToken, $sig, $openid, $sigMethod);
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return true;
    }

    # 上报用户数据后台接口的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/wxa/set_user_storage?access_token=ACCESS_TOKEN&signature=SIGNATURE&openid=OPENID&sig_method=SIG_METHOD
    # 【请求参数】
    # string access_token 接口调用凭证
    # string openid 用户唯一标识符
    # string signature 用户登录态签名
    # string sig_method 用户登录态签名的哈希方法，如hmac_sha256
    # array kv_list 要删除的数据key列表
    # string kv_list.key  数据的key
    # string kv_list.value  数据的value
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $setUserStorageURL = 'https://api.weixin.qq.com/wxa/set_user_storage?access_token=%s&signature=%s&openid=%s&sig_method=%s';

    /** 上报用户数据后台接口
     * @param $accessToken
     * @param $openid
     * @param $sessionKey
     * @param $kvList
     * @return bool
     */
    public function setUserStorage($accessToken, $openid, $sessionKey, $kvList)
    {
        $sigMethod = 'hmac_sha256';
        $data = array(
            'kv_list' => $kvList,
        );
        $sig = $this->makeSha1Sig($data, $sessionKey);
        $data['signature'] = $sig;
        $url = sprintf($this->setUserStorageURL, $accessToken, $sig, $openid, $sigMethod);
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return true;
    }

    # 创建被分享动态消息的 activity_id的接口地址
    # https请求方式: get
    # https://api.weixin.qq.com/cgi-bin/message/wxopen/activityid/create?access_token=ACCESS_TOKEN
    # 【返回参数】
    # string activity_id 动态消息的 ID
    # int expiration_time activity_id 的过期时间戳。默认24小时后过期
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $createActivityIdURL = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/activityid/create?access_token=%s';

    /** 创建被分享动态消息的 activity_id
     * @param $accessToken
     * @return bool|false|mixed|string
     */
    public function createActivityId($accessToken)
    {
        $url = sprintf($this->createActivityIdURL, $accessToken);
        $res = file_get_contents($url);
        $res = json_decode($res,TRUE);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 修改被分享的动态消息的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/message/wxopen/updatablemsg/send?access_token=ACCESS_TOKEN
    # 【请求参数】
    # string access_token 接口调用凭证
    # string activity_id 动态消息的 ID
    # int target_state 动态消息修改后的状态 0未开始，1已开始
    # array template_info 动态消息对应的模板信息
    # array template_info.parameterList 模板中需要修改的参数
    # string template_info.parameterList.name 要修改的参数名
    # string template_info.parameterList.value 修改后的参数值
    # 【 name 的合法值】
    # member_count   target_state = 0 时必填，文字内容模板中 member_count 的值
    # room_limit     target_state = 0 时必填，文字内容模板中 room_limit 的值
    # path     target_state = 1 时必填，点击「进入」启动小程序时使用的路径。对于小游戏，没有页面的概念，可以用于传递查询字符串（query），如 "?foo=bar"
    # version_type  target_state = 1 时必填，点击「进入」启动小程序时使用的版本。有效参数值为：develop（开发版），trial（体验版），release（正式版）
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $setUpdatableMsgURL = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/updatablemsg/send?access_token=%s';

    /** 修改被分享的动态消息
     * @param $accessToken
     * @param $activityId
     * @param $targetState
     * @param $templateInfo
     * @return bool
     */
    public function setUpdatableMsg($accessToken, $activityId, $targetState, $templateInfo)
    {
        $data = array(
            'access_token' => $accessToken,
            'activity_id' => $activityId,
            'target_state' => $targetState,
            'template_info' => $templateInfo,
        );
        $url = sprintf($this->setUpdatableMsgURL, $accessToken);
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return true;
    }

    # 获取小程序二维码，适用于需要的码数量较少的业务场景.通过该接口生成的小程序码，永久有效，有数量限制
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN
    # 【请求参数】
    # string access_token 接口调用凭证
    # string path 扫码进入的小程序页面路径，最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}
    # int width 二维码的宽度，单位 px。最小 280px，最大 1280px
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    # string contentType 图片类型
    # Buffer buffer 图片二进制内容
    public $createQRCodeURL = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=%s';

    /** 获取小程序二维码
     * @param $accessToken
     * @param $path
     * @param $width
     * @return bool|mixed|string
     */
    public function createQRCode($accessToken, $path, $width = 430)
    {
        $data = array(
            'access_token' => $accessToken,
            'path'         => $path,
            'width'        => $width,
        );
        $url = sprintf($this->createQRCodeURL, $accessToken);
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 获取小程序码，适用于需要的码数量较少的业务场景。通过该接口生成的小程序码，永久有效，有数量限制
    # https请求方式: POST
    # https://api.weixin.qq.com/wxa/getwxacode?access_token=ACCESS_TOKEN
    # 【请求参数】
    # string access_token 接口调用凭证
    # string path 扫码进入的小程序页面路径，最大长度 128 字节，不能为空；对于小游戏，可以只传入 query 部分，来实现传参效果，如：传入 "?foo=bar"，即可在 wx.getLaunchOptionsSync 接口中的 query 参数获取到 {foo:"bar"}
    # int width 二维码的宽度，单位 px。最小 280px，最大 1280px
    # boolean auto_color 自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调
    # array line_color auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
    # boolean is_hyaline 是否需要透明底色，为 true 时，生成透明底色的小程序码
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    # string contentType 图片类型
    # Buffer buffer 图片二进制内容
    public $getWxaCodeURL = 'https://api.weixin.qq.com/wxa/getwxacode?access_token=%s';

    /** 获取小程序码
     * @param $accessToken
     * @param $path
     * @param $width
     * @param bool $autoColor
     * @param array $lineColor
     * @param bool $isHyaline
     * @return bool|mixed|string
     */
    public function getWxaCode($accessToken, $path, $width = 430, $autoColor = false, $lineColor = array('r'=>0,'g'=>0,'b'=>0), $isHyaline = false)
    {
        $data = array(
            'access_token' => $accessToken,
            'path'         => $path,
            'width'        => $width,
            'auto_color'   => $autoColor,
            'line_color'   => $lineColor,
            'is_hyaline'   => $isHyaline,
        );
        $url = sprintf($this->getWxaCodeURL, $accessToken);
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 获取小程序码，适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
    # https请求方式: POST
    # https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=ACCESS_TOKEN
    # 【请求参数】
    # string access_token 接口调用凭证
    # string scene 最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符最大32个可见字符，只支持数字，大小写英文以及部分特殊字符：!#$&'()*+,/:;=?@-._~，其它字符请自行编码为合法字符（因不支持%，中文无法使用 urlencode 处理，请使用其他编码方式）
    # string page 必须是已经发布的小程序存在的页面（否则报错），例如 pages/index/index, 根路径前不要填加 /,不能携带参数（参数请放在scene字段里），如果不填写这个字段，默认跳主页面
    # int width 二维码的宽度，单位 px。最小 280px，最大 1280px
    # boolean auto_color 自动配置线条颜色，如果颜色依然是黑色，则说明不建议配置主色调
    # array line_color auto_color 为 false 时生效，使用 rgb 设置颜色 例如 {"r":"xxx","g":"xxx","b":"xxx"} 十进制表示
    # boolean is_hyaline 是否需要透明底色，为 true 时，生成透明底色的小程序码
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    # string contentType 图片类型
    # Buffer buffer 图片二进制内容
    public $getUnlimitedCodeURL = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=%s';

    /** 获取小程序码
     * @param $accessToken
     * @param $scene
     * @param $page
     * @param $width
     * @param bool $autoColor
     * @param array $lineColor
     * @param bool $isHyaline
     * @return bool|mixed|string
     */
    public function getUnlimitedCode($accessToken, $scene, $page = '主页', $width = 430, $autoColor = false, $lineColor = array('r'=>0,'g'=>0,'b'=>0), $isHyaline = false)
    {
        $data = array(
            'access_token' => $accessToken,
            'scene'        => $scene,
            'page'         => $page,
            'width'        => $width,
            'auto_color'   => $autoColor,
            'line_color'   => $lineColor,
            'is_hyaline'   => $isHyaline,
        );
        $url = sprintf($this->getWxaCodeURL, $accessToken);
        $res = $this->curlHttp($url, $data, true);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && 0 != $res['errcode']){
            return false;
        }

        return $res;
    }

    # 发送客服消息给用户的接口地址
    # https请求方式: POST
    # https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN
    # 【请求参数】
    # string access_token 接口调用凭证
    # string touser 用户的 OpenID
    # string msgtype 消息类型
    # Object text 文本消息，msgtype="text" 时必填
    # string text.content  文本消息内容
    # Object image 图片消息，msgtype="image" 时必填
    # string image.media_id  发送的图片的媒体ID，通过 新增素材接口 上传图片文件获得
    # Object link 图片消息，msgtype="link" 时必填
    # string link.title 消息标题
    # string link.description 图文链接消息
    # string link.url 图文链接消息被点击后跳转的链接
    # string link.thumb_url 图文链接消息的图片链接，支持 JPG、PNG 格式，较好的效果为大图 640 X 320，小图 80 X 80
    # Object miniprogrampage 小程序卡片，msgtype="miniprogrampage" 时必填
    # string miniprogrampage.title 消息标题
    # string miniprogrampage.pagepath 小程序的页面路径，跟app.json对齐，支持参数，比如pages/index/index?foo=bar
    # string miniprogrampage.thumb_media_id 小程序消息卡片的封面， image 类型的 media_id，通过 新增素材接口 上传图片文件获得，建议大小为 520*416
    # 【返回参数】
    # int  errcode  错误码
    # string  errmsg  错误信息
    public $customerServiceMessageSendURL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s';

    /**
     * 发送客服消息给用户
     *
     * @param $accessToken
     * @param $openid
     * @param $msgType
     * @param $content
     * @return bool
     */
    public function customerServiceMessageSend($accessToken, $openid, $msgType, $content)
    {
        $url = sprintf($this->customerServiceMessageSendURL, $accessToken);
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