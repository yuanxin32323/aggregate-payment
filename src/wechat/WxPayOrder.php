<?php

/**
 * 离骚 微信订单类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\WxPay;

class WxPayOrder {

    private $config;

    /**
     * 初始化
     * @param \LisaoPayment\WxPay\WxPayConfig $config 配置参数
     */
    public function __construct(WxPayConfig $config) {
        $this->config = $config;
    }

    public function create_order(\LisaoPayment\WxConfig\UnifiedOrderConfig $param) {
        //获取请求地址
        if ($this->config->get('sandbox')) {
            $url = $param->get('sandbox_url');
        } else {
            $url = $param->get('product_url');
        }
    }

    /**
     * 签名计算
     * @param \LisaoPayment\WxPay\WxPayConfig $config
     */
    private function sign(\LisaoPayment\WxConfig\UnifiedOrderConfig $param, WxPayConfig $config) {
        $data = $param->get_all();
        $data['appid'] = $config->get('appid');
        $data['mch_id'] = $config->get('mch_id');
        $data['sign_type'] = $config->get('sign_type');
        ksort($data);
        $str_sign = '';
        foreach ($data as $k => $v) {
            if ($v) {
                $str_sign .= $k . '=' . $v . '&';
            }
        }
        if ($config->get('sign_type') === "MD5") {
            $sign = strtoupper(md5($str_sign . 'key=' . $config->get('api_key')));
        } else {
            $sign = strtoupper(hash_hmac('sha256', $str_sign . 'key=' . $config->get('api_key'), $config->get('api_key'), FALSE));
        }
        return $sign;
    }

}
