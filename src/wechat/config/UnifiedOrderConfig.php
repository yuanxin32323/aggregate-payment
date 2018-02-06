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
     * 获取所有参数
     */

    public function get_all() {
        return $this->param;
    }

}
