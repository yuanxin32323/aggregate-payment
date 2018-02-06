<?php

/**
 * 离骚 微信支付配置类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\WxPay;

class WxPayConfig {

    private $app_id; //微信公众号开发者app_id。
    private $mch_id; //商户号
    private $api_key; //商户秘钥
    private $sandbox = false; //是否沙盒模式

    /*
     * 初始化配置
     */

    public function __construct($app_id = '', $mch_id = '', $api_key = '', $sandbox = false) {
        $this->app_id = $app_id;
        $this->mch_id = $mch_id;
        $this->api_key = $api_key;
        $this->sandbox = $sandbox;
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

}
