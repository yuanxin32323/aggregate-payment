<?php

/**
 * 刷卡支付接口参数（扫码枪支付）
 */

namespace LisaoPayment\AliPayConfig;

class MicroPayConfig implements \LisaoPayment\ConfigBase\InterfaceConfig {

    private $method = 'alipay.trade.pay'; //请求类型
    private $app_auth_token; //第三方应用授权
    private $param = []; //参数

    public function __construct($app_auth_token = '') {
        if ($app_auth_token) {
            $this->app_auth_token = $app_auth_token;
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
     * @param string $value 	商户订单号,64个字符以内、只能包含字母、数字、下划线；需保证在商户端不重复
     */
    public function set_out_trade_no(string $value) {
        $this->param['out_trade_no'] = $value;
    }

    /**
     * 支付场景 参考值：bar_code=条码 wave_code=声波
     * @param string $value 	25~30开头的长度为16~24位的数字，实际字符串长度以开发者获取的付款码长度为准
     */
    public function set_scene(string $value) {
        $this->param['scene'] = $value;
    }

    /**
     * 设置支付授权码
     * @param string $value 25~30开头的长度为16~24位的数字，实际字符串长度以开发者获取的付款码长度为准
     */
    public function set_auth_code(string $value) {
        $this->param['auth_code'] = $value;
    }

    /**
     * 	订单标题
     * @param string $value
     */
    public function set_subject(string $value) {
        $this->param['subject'] = $value;
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
    public function set_total_amount(int $value) {
        $this->param['total_amount'] = $value * 100;
    }

}
