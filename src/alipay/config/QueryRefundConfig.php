<?php

/**
 * 统一收单交易退款查询
 */

namespace LisaoPayment\AliPayConfig;

class QueryRefundConfig implements \LisaoPayment\ConfigBase\InterfaceConfig {

    public $method = 'alipay.trade.fastpay.refund.query'; //请求类型
    private $param = []; //参数

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
     * 获取接口类型
     * @return type
     */
    public function get_method() {
        return $this->method;
    }

    /**
     * 商户订单号
     * @param string $value 	和支付宝交易号不能同时为空。trade_no,out_trade_no如果同时存在优先取trade_no
     */
    public function set_out_trade_no(string $value) {
        $this->param['out_trade_no'] = $value;
    }

    /**
     * 支付宝交易号
     * @param string $value 	和商户订单号不能同时为空。trade_no,out_trade_no如果同时存在优先取trade_no
     */
    public function set_trade_no(string $value) {
        $this->param['trade_no'] = $value;
    }

    /**
     * 退款id
     * @param string $value 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
     */
    public function set_out_request_no(string $value) {
        $this->param['out_request_no'] = $value;
    }

}
