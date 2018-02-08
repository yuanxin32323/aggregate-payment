<?php

/**
 * 刷卡支付接口参数（扫码枪支付）
 */

namespace LisaoPayment\WxConfig;

class MicroPayConfig implements \LisaoPayment\ConfigBase\InterfaceConfig {

    private $product_url = 'https://api.mch.weixin.qq.com/pay/micropay'; //生产环境请求接口
    private $sandbox_url = 'https://api.mch.weixin.qq.com/sandboxnew/pay/micropay'; //沙箱环境请求接口
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
     * @param type $option 参数名
     * @param type $value 值
     * @return boolean
     */
    public function set(string $option, $value) {
        $this->param[$option] = $value;
        return TRUE;
    }

    /**
     * 设置所有参数 - 一般用于设置一些非必需参数
     * @param array $param 参数数组
     * @return boolean
     */
    public function set_all(array $param) {
        foreach ($param as $k => $v) {
            $this->param[$k] = $v;
        }
        return TRUE;
    }

    /**
     * 获取参数
     * @param string $option 参数名
     * @return type 值
     */
    public function get(string $option) {
        return $this->param[$option];
    }

    /**
     * 获取所有参数
     * @return array 值
     */
    public function get_all() {
        return $this->param;
    }

    /**
     * 获取网关地址
     * @param bool $sandbox 是否为沙箱环境
     * @return type
     */
    public function get_url(bool $sandbox = false) {
        if ($sandbox) {
            return $this->sandbox_url;
        } else {
            return $this->product_url;
        }
    }

    /**
     * 设置商户订单号
     * @param string $value 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母_-|*@ ，且在同一个商户号下唯一
     */
    public function set_out_trade_no(string $value) {
        $this->param['out_trade_no'] = $value;
    }

    /**
     * 设置随机串
     * @param string $value 随机字符串，不长于32位。
     */
    public function set_nonce_str(string $value) {
        $this->param['nonce_str'] = $value;
    }

    /**
     * 设置商品描述
     * @param string $value 商品简单描述
     */
    public function set_body(string $value) {
        $this->param['body'] = $value;
    }

    /**
     * 设置订单总金额
     * @param int $value 订单总金额，只能为整数，单位：分
     */
    public function set_total_fee(int $value) {
        $this->param['total_fee'] = $value;
    }

    /**
     * 设置支付授权码
     * @param string $value 扫码支付授权码，设备读取用户微信中的条码或者二维码信息
      （注：用户刷卡条形码规则：18位纯数字，以10、11、12、13、14、15开头）
     */
    public function set_auth_code(string $value) {
        $this->param['auth_code'] = $value;
    }

}
