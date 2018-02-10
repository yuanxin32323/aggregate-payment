<?php

/**
 * 统一收单线下交易预创建
 */

namespace LisaoPayment\AliPayConfig;

class PreCreateOrderConfig implements \LisaoPayment\ConfigBase\InterfaceConfig {

    public $method = 'alipay.trade.precreate'; //请求类型
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
     * 设置商户订单号
     * @param string $value 	商户订单号,64个字符以内、只能包含字母、数字、下划线；需保证在商户端不重复
     */
    public function set_out_trade_no(string $value) {
        $this->param['out_trade_no'] = $value;
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
        $this->param['total_amount'] = $value / 100;
    }
  
}
