<?php

/**
 * 离骚 支付宝支付配置类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\AliPay;

class AliPayConfig {

    private $app_id; //支付宝应用id
    private $private_key; //商户应用私钥
    private $public_key; //支付宝公钥
    private $sign_type = "RSA2"; //签名方式 RSA2 RSA
    private $sand_box = false; //是否沙盒模式
    private $notify_url; //回调地址
    private $app_auth_token; //授权

    /**
     * 初始化配置
     * @param type $app_id 应用id
     * @param type $public_key 支付宝公钥
     * @param type $private_key 商户应用私钥
     * @param type $sign_type 签名方式 RSA2 RSA
     * @param type $sandbox
     */

    public function __construct($app_id, $public_key, $private_key, $sign_type = "RSA2", $notify_url = '', $app_auth_token = '', $sandbox = false) {
        $this->app_id = $app_id;
        $this->private_key = $private_key;
        $this->public_key = $public_key;
        $this->sand_box = $sandbox;
        $this->sign_type = $sign_type ?: 'RSA2';
        $this->notify_url = $notify_url;
        $this->app_auth_token = $app_auth_token;
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
        $this->app_id = $value;
        return TRUE;
    }

    public function set_public_key(string $value) {
        $this->public_key = $value;
        return TRUE;
    }

    public function set_private_key(string $value) {
        $this->private_key = $value;
        return TRUE;
    }

    public function set_sand_box(bool $value) {
        $this->sand_box = $value;
        return TRUE;
    }

    public function set_sign_type(string $value) {
        $this->sign_type = $value;
        return TRUE;
    }

    public function set_notify_url(string $value) {
        $this->notify_url = $value;
        return TRUE;
    }

    public function set_app_auth_token(string $value) {
        $this->app_auth_token = $value;
        return TRUE;
    }

}
