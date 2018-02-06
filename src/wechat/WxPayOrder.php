<?php

/**
 * 离骚 微信订单类
 * @version 1.0 @ 2018-02-05
 * @see https://github.com/yuanxin32323
 */

namespace LisaoPayment\WxPay;

class WxPayOrder {

    private $config;

    /**
     * 初始化
     * @param \LisaoPayment\WxPay\WxPayConfig $config 配置参数
     */
    public function __construct(WxPayConfig $config) {
        $this->config = $config;
    }

    /*
     * 创建订单
     */

    public function create_order() {
        $param = [
            'appid' => $this->config->get('app_id'),
            'mch_id' => $this->config->get('mch_id'),
        ];
    }

}
