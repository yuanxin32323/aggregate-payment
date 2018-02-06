<?php

/*
 * 统一下单接口参数
 */

namespace LisaoPayment\WxConfig;

class UnifiedOrderConfig implements \LisaoPayment\ConfigBase\UnifiedOrderConfig {

    private $product_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder'; //生产环境请求接口
    private $sandbox_url = 'https://api.mch.weixin.qq.com/sandboxnew/pay/unifiedorder'; //沙箱环境请求接口
    private $param = []; //参数

    /*
     * 设置参数
     */

    public function set($option, $value) {
        $this->param[$option] = $value;
        return TRUE;
    }

    /*
     * 获取参数
     */

    public function get($option) {
        return $this->param[$option];
    }

    /**
     * 签名计算
     * @param \LisaoPayment\WxPay\WxPayConfig $config
     */
    public function sign(\LisaoPayment\WxPay\WxPayConfig $config) {
        $data = $this->param;
        $data['appid'] = $config->get('app_id');
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
