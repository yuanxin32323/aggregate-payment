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
        $url = $param->get_url($this->config->get('sandbox'));
        $data = $param->get_all();
        $data['appid'] = $this->config->get('appid');
        $data['mch_id'] = $this->config->get('mch_id');
        $data['sign_type'] = $this->config->get('sign_type');
    }

    /**
     * 签名
     * @param type $data
     * @param \LisaoPayment\WxPay\WxPayConfig $config
     * @return string 
     */
    private function sign($data) {

        ksort($data);
        $str_sign = '';
        foreach ($data as $k => $v) {
            if ($v) {
                $str_sign .= $k . '=' . $v . '&';
            }
        }
        if ($this->config->get('sign_type') === "MD5") {
            $sign = strtoupper(md5($str_sign . 'key=' . $this->config->get('api_key')));
        } else {
            $sign = strtoupper(hash_hmac('sha256', $str_sign . 'key=' . $this->config->get('api_key'), $this->config->get('api_key'), FALSE));
        }
        return $sign;
    }

}
