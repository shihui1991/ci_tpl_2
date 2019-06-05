<?php
/**
 *  微信接口 支付
 * @author MSL
 * @create 2019-04-01
 */

namespace libraries\wechat;


class Pay extends Base
{

    public $appid; # APPID
    public $appkey; # APPSECRET
    public $token; # TOKEN
    public $encodingAESKey; # EncodingAESKey
    public $mchid; # 商户号
    public $payDoneNotifyURL; # 支付成功回调地址

    public $orderUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $orderQueryUrl = 'https://api.mch.weixin.qq.com/pay/orderquery';

    # 交易状态
    static public $tradeState = array(
        'SUCCESS'    => '支付成功',
        'REFUND'     => '转入退款',
        'NOTPAY'     => '未支付',
        'CLOSED'     => '已关闭',
        'REVOKED'    => '已撤销',
        'USERPAYING' => '用户支付中',
        'PAYERROR'   => '支付失败',
    );

    # 统一下单的接口地址
    # https请求方式: POST
    # https://api.mch.weixin.qq.com/pay/unifiedorder
    public $unifiedOrderURL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    # 查询订单的接口地址
    # https请求方式: POST
    # https://api.mch.weixin.qq.com/pay/orderquery
    public $orderQueryURL = 'https://api.mch.weixin.qq.com/pay/orderquery';

    /**
     * 查询订单
     *
     * @param $outTradeNo
     * @param string $transactionId
     * @return array
     */
    public function queryOrder($outTradeNo, $transactionId = '')
    {
        $nonceStr = makeUniqueStr(true); # 随机字符串
        # 请求参数
        $data = array(
            'appid'          => $this->appid,    # 应用ID
            'mch_id'         => $this->mchid,    # 商户号
            'transaction_id' => $transactionId,  # 微信订单号，优先使用
            'out_trade_no'   => $outTradeNo,     # 商户系统内部的订单号
            'nonce_str'      => $nonceStr,       # 随机字符串，不长于32位
        );
        # 生成 MD5 签名
        $data = $this->filterEmptyData($data);
        $data['sign'] = $this->makeMd5Sign($data);
        $data['sign_type'] = 'MD5';
        # 请求参数转为 xml
        $dataXml = arrayToSimpleXml($data,'<xml/>');
        # 查询订单
        $resXml = $this->curlHttp($this->orderQueryURL,$dataXml,true,2);
        # 解析查询结果
        $result = simpleXmlToArray($resXml);

        return $result;
    }
}