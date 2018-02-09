<?php

/**
 * 离骚 支付宝支付配置类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\AliPay;

class AliPayConfig {

    private $param = []; //参数表
    private $sand_box = false; //是否沙盒模式

    /**
     * 初始化配置
     * @param type $app_id 应用id
     * @param type $public_key 支付宝公钥
     * @param type $private_key 应用私钥
     * @param type $sign_type 签名方式
     * @param type $notify_url 回调地址
     * @param type $app_auth_token 商户token
     * @param type $sandbox 是否沙箱环境
     */

    public function __construct($app_id, $public_key, $private_key, $sign_type = "RSA2", $notify_url = '', $app_auth_token = '', $sandbox = false) {
        $this->param['app_id'] = $app_id;
        $this->param['public_key'] = $public_key;
        $this->param['private_key'] = $private_key;
        $this->param['sign_type'] = $sign_type;
        $this->param['notify_url'] = $notify_url;
        $this->param['app_auth_token'] = $app_auth_token;
        $this->sand_box = $sandbox;
    }

    /**
     * 是否为沙箱环境
     */
    public function is_sand_box() {
        return $this->sand_box;
    }

    /*
     * 设置配置
     */

    public function set($option, $value) {
        $this->param[$option] = $value;
    }

    /*
     * 获取配置
     */

    public function get($option) {
        return $this->param[$option];
    }

    public function set_app_id(string $value) {
        $this->param['app_id'] = $value;
        return TRUE;
    }

    public function set_public_key(string $value) {
        $this->param['public_key'] = $value;
        return TRUE;
    }

    public function set_private_key(string $value) {
        $this->param['private_key'] = $value;
        return TRUE;
    }

    public function set_sand_box(bool $value) {
        $this->param['sand_box'] = $value;
        return TRUE;
    }

    public function set_sign_type(string $value) {
        $this->param['sign_type'] = $value;
        return TRUE;
    }

    public function set_notify_url(string $value) {
        $this->param['notify_url'] = $value;
        return TRUE;
    }

    public function set_app_auth_token(string $value) {
        $this->param['app_auth_token'] = $value;
        return TRUE;
    }

}
