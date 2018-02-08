<?php

/**
 * 离骚 支付宝支付类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\AliPay;

class AliPayApi {

    private $url; //接口地址
    private $config; //配置
    private $sandbox_signkey; //沙箱秘钥

    /**
     * 初始化
     * @param \LisaoPayment\AliPay\AliPayConfig $config 配置参数
     * @throws AliPayException
     */

    public function __construct(AliPayConfig $config) {
        $this->config = $config;
        //判断appid是否存在
        if (empty($config->get('app_id'))) {
            throw new AliPayException('PARAM_ERROR', "缺少app_id参数");
        }
        //判断public_key是否存在
        if (empty($config->get('public_key'))) {
            throw new AliPayException('PARAM_ERROR', "缺少public_key参数");
        }
        //判断private_key是否存在
        if (empty($config->get('private_key'))) {
            throw new AliPayException('PARAM_ERROR', "缺少private_key参数");
        }
        //判断签名方式是否正确
        if ($config->get('sign_type') !== 'RSA2' && $config->get('sign_type') !== 'RSA') {
            throw new AliPayException('PARAM_ERROR', "签名方式错误");
        }
        //判断是否为沙箱环境
        if ($config->get('sand_box')) {
            $this->url = 'https://openapi.alipaydev.com/gateway.do';
        } else {
            $this->url = 'https://openapi.alipay.com/gateway.do';
        }
    }

    /**
     * 签名
     * @param type $data
     * @param type $api_key
     * @return type
     */
    private function sign($data, $api_key) {
        ksort($data);
        $str_sign = '';
        foreach ($data as $k => $v) {
            if ($v) {
                $str_sign .= $k . '=' . $v . '&';
            }
        }
        if ($this->config->get('sign_type') === "MD5") {
            $sign = strtoupper(md5($str_sign . 'key=' . $api_key));
        } else {
            $sign = strtoupper(hash_hmac('sha256', $str_sign . 'key=' . $api_key, $api_key, FALSE));
        }
        return $sign;
    }

    /**
     * 取随机字符
     */
    private function random_str($length = 32, $number = false) {
        $dictionary = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; //62位
        if ($number) {
            $dictionary = "0123456789";
        }
        $str = '';

        $max = strlen($dictionary) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $dictionary[rand(0, $max)];
        }
        return $str;
    }

}
