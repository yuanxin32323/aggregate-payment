<?php

/**
 * 离骚 微信支付配置类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\WxPay;

class WxPayConfig {

    private $appid; //微信公众号开发者app_id。
    private $mch_id; //商户号
    private $api_key; //商户秘钥
    private $sign_type = "MD5"; //签名方式 MD5 HMAC-SHA256
    private $sandbox = false; //是否沙盒模式

    /*
     * 初始化配置
     */

    public function __construct($app_id, $mch_id, $api_key, $sign_type = "MD5", $sandbox = false) {
        $this->appid = $app_id;
        $this->mch_id = $mch_id;
        $this->api_key = $api_key;
        $this->sandbox = $sandbox;
        $this->sign_type = $sign_type ?: 'MD5';
    }

    /*
     * 设置配置
     */

    public function set($option, $value) {
        if (is_array($option)) {
            foreach ($option as $val) {
                $this->$val = $value;
            }
        } else {
            $this->$option = $value;
        }
    }

    /*
     * 获取配置
     */

    public function get($option) {
        return $this->$option;
    }

    public function set_app_id(string $value) {
        $this->appid = $value;
        return TRUE;
    }

    public function set_mch_id(string $value) {
        $this->mch_id = $value;
        return TRUE;
    }

    public function set_api_key(string $value) {
        $this->api_key = $value;
        return TRUE;
    }

    public function set_sand_box(bool $value) {
        $this->sandbox = $value;
        return TRUE;
    }

    public function set_sign_type(string $value) {
        $this->sign_type = $value;
        return TRUE;
    }

}
