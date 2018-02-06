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
     * 设置所有参数
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
     * 获取用户端ip
     */

    public function get_client_ip() {
        foreach (array(
    'HTTP_CLIENT_IP',
    'HTTP_X_FORWARDED_FOR',
    'HTTP_X_FORWARDED',
    'HTTP_X_CLUSTER_CLIENT_IP',
    'HTTP_FORWARDED_FOR',
    'HTTP_FORWARDED',
    'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    //会过滤掉保留地址和私有地址段的IP，例如 127.0.0.1会被过滤
                    //也可以修改成正则验证IP
                    if ((bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 |
                                    FILTER_FLAG_NO_PRIV_RANGE |
                                    FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }
        return null;
    }

}
