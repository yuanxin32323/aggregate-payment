<?php

/*
 * 统一下单接口参数
 */

namespace LisaoPayment\WxConfig;

class UnifiedOrderConfig implements \LisaoPayment\ConfigBase\InterfaceConfig {

    private $product_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder'; //生产环境请求接口
    private $sandbox_url = 'https://api.mch.weixin.qq.com/sandboxnew/pay/unifiedorder'; //沙箱环境请求接口
    private $param = []; //参数

    public function __construct($sub_mch_id = '', $sub_appid = '') {
        if ($sub_mch_id) {
            $this->param['sub_mch_id'] = $sub_mch_id;
        }
        if ($sub_appid) {
            $this->param['sub_appid'] = $sub_appid;
        }
    }

    /**
     * 设置参数 - 一般用于设置一些非必需参数
     * @param type $option
     * @param type $value
     * @return boolean
     */
    public function set($option, $value) {
        $this->param[$option] = $value;
        return TRUE;
    }

    /**
     * 设置所有参数 - 一般用于设置一些非必需参数
     * @param type $option
     * @param type $value
     * @return boolean
     */
    public function set_all(array $param) {
        foreach ($param as $k => $v) {
            $this->param[$k] = $v;
        }
        return TRUE;
    }

    /*
     * 获取参数
     */

    public function get($option) {
        return $this->param[$option];
    }

    /*
     * 获取所有参数
     */

    public function get_all() {
        return $this->param;
    }

    /*
     * 获取网关地址
     */

    public function get_url($sandbox = false) {
        if ($sandbox) {
            return $this->sandbox_url;
        } else {
            return $this->product_url;
        }
    }

    /*
     * 设置订单号
     */

    public function set_out_trade_no(string $value) {
        $this->param['out_trade_no'] = $value;
    }

    /*
     * 设置随机串
     */

    public function set_nonce_str(string $value) {
        $this->param['nonce_str'] = $value;
    }

    /*
     * 设置订单总金额
     * 单位：分
     */

    public function set_total_fee(int $value) {
        $this->param['total_fee'] = $value;
    }

    /*
     * 设置回调地址
     */

    public function set_notify_url(string $value) {
        $this->param['notify_url'] = $value;
    }

    /**
     * 设置支付类型
     */
    public function set_trade_type(string $value) {
        $this->param['trade_type'] = $value;
    }

}
